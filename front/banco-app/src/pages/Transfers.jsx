import React, { useState, useEffect } from 'react';
import { useBank } from '../context/BankContext';

export default function Transfer() {
    const { contacts, loadContacts, transfer } = useBank();
    const [amount, setAmount] = useState('');
    const [contactId, setContactId] = useState('');

    useEffect(() => { loadContacts(); }, []);

    const handleTransfer = async (e) => {
        e.preventDefault();
        
        // Verifica que se haya seleccionado un contacto
        if (!contactId) {
            alert("Por favor, selecciona un destinatario.");
            return;
        }

        const success = await transfer(amount, contactId);
        
        if (success) {
            alert(`Transferencia de $${amount} realizada con éxito.`);
            setAmount('');
            setContactId('');
        }
    };

    // Función para manejar el formateo del monto al salir del campo
    const handleAmountBlur = (e) => {
        const v = parseFloat(e.target.value || 0);
        // Formatear a dos decimales, si es un número válido
        if (!isNaN(v)) setAmount(v.toFixed(2));
    };
    
    // Obtener la información del contacto seleccionado para mostrar en el formulario
    const selectedContact = contacts.find(c => c.id === parseInt(contactId));

    return (
        // Contenedor principal de la vista de Transferencia
        <div className="card">
            
            <header style={{ marginBottom: '30px', borderBottom: '1px solid #eee', paddingBottom: '15px' }}>
                <h2>💸 Nueva Transferencia</h2>
                <p style={{ color: 'var(--text-muted)', margin: 0 }}>
                    Envía dinero a tus contactos guardados de forma rápida y segura.
                </p>
            </header>
            
            {/* Contenedor para limitar el ancho del formulario */}
            <div className="form-container">
                <form onSubmit={handleTransfer}>
                    
                    {/* Campo de Monto */}
                    <label style={{ display: 'block', fontWeight: '500', marginBottom: '5px' }}>Monto a transferir (USD):</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        placeholder="0.00"
                        value={amount}
                        onChange={e => setAmount(e.target.value)}
                        onBlur={handleAmountBlur}
                        required
                    />
                    
                    {/* Campo de Destinatario */}
                    <label style={{ display: 'block', fontWeight: '500', marginBottom: '5px' }}>Selecciona el Destinatario:</label>
                    <select 
                        value={contactId} 
                        onChange={e => setContactId(e.target.value)} 
                        required
                    >
                        <option value="">-- Selecciona un contacto --</option>
                        {contacts.map(c => (
                            <option key={c.id} value={c.id}>
                                {c.name} (Cuenta: {c.account_number})
                            </option>
                        ))}
                    </select>

                    {/* Mostrar detalles del contacto seleccionado */}
                    {selectedContact && (
                        <div style={{ padding: '15px', border: '1px solid #eee', borderRadius: '8px', backgroundColor: 'var(--col-white-3)', marginBottom: '20px' }}>
                            <p style={{ margin: '0', fontWeight: '600', color: 'var(--col-vibrant-purple)' }}>
                                Destinatario: {selectedContact.name}
                            </p>
                            <p style={{ margin: '5px 0 0', fontSize: '0.9rem', color: 'var(--text-muted)' }}>
                                Nº Cuenta: {selectedContact.account_number}
                            </p>
                        </div>
                    )}
                    
                    <button 
                        type="submit" 
                        className="btn-primary" 
                        style={{ marginTop: '20px' }}
                        disabled={!contactId || parseFloat(amount) <= 0}
                    >
                        Transferir Dinero
                    </button>
                </form>
            </div>
            
        </div>
    );
}