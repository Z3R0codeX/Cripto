import React, { useState, useEffect } from 'react';
import { useBank } from '../context/BankContext';

export default function Transfer() {
    const { contacts, loadContacts, transfer } = useBank();
    const [amount, setAmount] = useState('');
    const [contactId, setContactId] = useState('');

    useEffect(() => { loadContacts(); }, []);

    const handleTransfer = async (e) => {
        e.preventDefault();
        const success = await transfer(amount, contactId);
        if (success) setAmount('');
    };

    return (
        <div className="card">
            <h2>Nueva Transferencia</h2>
            <form onSubmit={handleTransfer}>
                <label>Monto a transferir:</label>
                <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    value={amount}
                    onChange={e => setAmount(e.target.value)}
                    onBlur={e => {
                        const v = parseFloat(e.target.value || 0);
                        if (!isNaN(v)) setAmount(v.toFixed(2));
                    }}
                    required
                />
                
                <label>Destinatario:</label>
                <select value={contactId} onChange={e => setContactId(e.target.value)} required>
                    <option value="">Selecciona un contacto</option>
                    {contacts.map(c => (
                        <option key={c.id} value={c.id}>{c.name}</option>
                    ))}
                </select>
                
                <button type="submit" className="btn-primary">Transferir Dinero</button>
            </form>
        </div>
    );
}