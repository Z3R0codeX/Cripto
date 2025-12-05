<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Contacto;
use App\Models\Wallet;
use App\Models\Transaccion;
use App\Models\User;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Transfer money from authenticated user to a contact's user wallet.
     * Expects: amount (numeric), contact_id (ID_CONTACTO)
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'amount' => ['required','numeric'],
            'contact_id' => ['required','integer','exists:crypto_contactos,ID_CONTACTO']
        ]);

        $amount = (float) $validated['amount'];
        if ($amount <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Monto inválido'], 422);
        }

        $contact = Contacto::find($validated['contact_id']);
        if (!$contact || $contact->user_id != $userId) {
            return response()->json(['status' => 'error', 'message' => 'Contacto no válido'], 403);
        }

        $recipientUserId = $contact->contacto_user_id;

        // Determine contact name to use in descriptions
        $contactName = $contact->contactUser?->name ?? null;

        // Perform transfer using user.balance as source of truth
        try {
            $result = DB::transaction(function () use ($userId, $recipientUserId, $amount, $contactName) {
                // Lock user rows
                $senderUser = User::where('id', $userId)->lockForUpdate()->first();
                $recipientUser = User::where('id', $recipientUserId)->lockForUpdate()->first();

                if (!$senderUser || !$recipientUser) {
                    throw new \RuntimeException('Usuario no encontrado');
                }

                if ((float)$senderUser->balance < $amount) {
                    throw new \RuntimeException('Fondos insuficientes');
                }

                // store previous balances
                $prevSender = (string) $senderUser->balance;
                $prevRecipient = (string) $recipientUser->balance;

                // update user balances
                $senderUser->balance = (float)$senderUser->balance - $amount;
                $senderUser->save();

                $recipientUser->balance = (float)$recipientUser->balance + $amount;
                $recipientUser->save();

                // Optionally sync wallets/transacciones if wallets exist
                $txOut = null; $txIn = null; $senderWallet = null; $recipientWallet = null;

                // pick primary wallet for sender and recipient if present
                $senderWallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();
                $recipientWallet = Wallet::where('user_id', $recipientUserId)->lockForUpdate()->first();

                if ($senderWallet) {
                    // adjust sender wallet proportionally (simple approach: deduct from first wallet)
                    $senderPrev = (string) $senderWallet->SALDO;
                    $senderWallet->SALDO = $senderWallet->SALDO - $amount;
                    $senderWallet->save();
                    $toName = $contactName ?? ('user_id: ' . $recipientUserId);
                    $txOut = Transaccion::create([
                        'ID_WALLET' => $senderWallet->ID_WALLET,
                        'TIPO' => 'transfer_out',
                        'MONTO' => $amount,
                        'DESCRIPCION' => $toName
                    ]);
                }

                if ($recipientWallet) {
                    $recipientPrev = (string) $recipientWallet->SALDO;
                    $recipientWallet->SALDO = $recipientWallet->SALDO + $amount;
                    $recipientWallet->save();
                    $fromName = $senderUser->name ?? ('user_id: ' . $userId);
                    $txIn = Transaccion::create([
                        'ID_WALLET' => $recipientWallet->ID_WALLET,
                        'TIPO' => 'transfer_in',
                        'MONTO' => $amount,
                        'DESCRIPCION' => $fromName
                    ]);
                }

                return [
                    'sender_user' => $senderUser,
                    'recipient_user' => $recipientUser,
                    'sender_wallet' => $senderWallet,
                    'recipient_wallet' => $recipientWallet,
                    'tx_out' => $txOut,
                    'tx_in' => $txIn,
                ];
            });

            return response()->json([
                'status' => 'ok',
                'message' => 'Transferencia realizada',
                'new_balance' => (string) $result['sender_user']->balance,
                'transaction_out' => $result['tx_out'],
                'transaction_in' => $result['tx_in']
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error en la transferencia', 'detail' => $e->getMessage()], 500);
        }
    }
}
