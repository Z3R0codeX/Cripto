// src/pages/Home.jsx
import React, { useEffect } from 'react';
import { useBank } from '../context/BankContext';
import { Link } from 'react-router-dom';

export default function Home() {
    const { user, transactions, loadTransactions, contacts, loadContacts } = useBank();

    // Load real transactions from API on mount
    useEffect(() => { loadContacts(); loadTransactions(); }, []);

    const recentTransactions = (transactions || []).slice(0, 5).map(tx => {
        const raw = Number(tx.MONTO || tx.monto || 0);
        let tipo = (tx.TIPO || tx.tipo || '').toLowerCase();
        let type = tipo.includes('out') ? 'Enviado' : (tipo.includes('in') ? 'Recibido' : 'Otro');
        // Determine signed amount: negative for out, positive for in
        const amount = tipo.includes('out') ? -Math.abs(raw) : Math.abs(raw);
        // Prefer to display the contact name when we can match it
        let name = tx.DESCRIPCION || (tx.wallet && tx.wallet.ID_WALLET) || 'Movimiento';
        try {
            // Try to find a contact whose name or account number matches the description or wallet info
            const match = (contacts || []).find(c => {
                if (!c) return false;
                const desc = (tx.DESCRIPCION || '').toString();
                if (!desc) return false;
                if ((c.name || '') === desc) return true;
                if ((c.account_number || '') === desc) return true;
                // Also allow matching by email contained in wallet or description
                if (tx.wallet && c.account_number && tx.wallet.ID_WALLET && tx.wallet.ID_WALLET.toString() === c.account_number) return true;
                return false;
            });
            if (match) name = match.name;
        } catch (e) { /* ignore */ }
        const date = tx.created_at ? new Date(tx.created_at).toLocaleDateString() : '';
        return { id: tx.ID_TRANSACCION || tx.id, type, name, amount, date };
    });

    return (
        <div>
            <h2 style={{ marginBottom: '20px' }}>Resumen General</h2>
            
            <div className="dashboard-grid">
                
                {/* TARJETA 1: SALDO PRINCIPAL (La más importante) */}
                <div className="card balance-card">
                    <h3>Saldo Disponible</h3>
                    <div className="amount">${user?.balance?.toLocaleString()}</div>
                    <p className="account-number">Cuenta: **** {user?.account_number?.slice(-4) || '0000'}</p>
                </div>

                {/* TARJETA 2: ACCIONES RÁPIDAS */}
                <div className="card actions-card">
                    <h3>¿Qué quieres hacer?</h3>
                    <div className="actions-grid">
                        <Link to="transfer" className="action-btn">
                            <span className="icon">💸</span>
                            Transferir
                        </Link>
                        <Link to="contacts" className="action-btn">
                            <span className="icon">👥</span>
                            Contactos
                        </Link>
                        <Link to="profile" className="action-btn">
                            <span className="icon">⚙️</span>
                            Ajustes
                        </Link>
                    </div>
                </div>

                {/* TARJETA 3: ÚLTIMOS MOVIMIENTOS (Ocupa ancho completo visualmente si es necesario) */}
                <div className="card history-card" style={{ gridColumn: '1 / -1' }}>
                    <h3>Últimos Movimientos</h3>
                    <table className="contacts-table">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Fecha</th>
                                <th style={{ textAlign: 'right' }}>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            {recentTransactions.map(t => (
                                <tr key={t.id}>
                                    <td>
                                        <strong style={{ color: t.amount > 0 ? '#2ecc71' : '#e74c3c' }}>
                                            {t.type}
                                        </strong> - {t.name}
                                    </td>
                                    <td>{t.date}</td>
                                    <td style={{ 
                                        textAlign: 'right', 
                                        fontWeight: 'bold',
                                        color: t.amount > 0 ? '#2ecc71' : '#e74c3c' 
                                    }}>
                                        {t.amount > 0 ? '+' : ''}{Math.abs(t.amount).toFixed(2)}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    );
}