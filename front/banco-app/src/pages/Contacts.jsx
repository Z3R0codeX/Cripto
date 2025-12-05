import React, { useState, useEffect } from 'react';
import { useBank } from '../context/BankContext';

export default function Contacts() {
    const { contacts, loadContacts, addContact, editContact, removeContact, user, loading } = useBank();
    const [name, setName] = useState('');
    const [account, setAccount] = useState('');
    const [editingId, setEditingId] = useState(null);

    useEffect(() => { loadContacts(); }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        // Prevent submission if not authenticated
        if (!user) {
            alert('Debes iniciar sesión para agregar contactos.');
            return;
        }

        let success;
        if (editingId) {
            success = await editContact(editingId, name, account);
            if (success) {
                setEditingId(null);
                alert('Contacto actualizado');
            }
        } else {
            success = await addContact(name, account);
            if (success) alert('Contacto agregado');
        }
        if (success) { setName(''); setAccount(''); }
    };

    const startEdit = (c) => {
        setEditingId(c.id);
        setName(c.name);
        setAccount(c.account_number);
    };

    return (
        <div>
            <div style={{ marginBottom: 12 }}>
                {loading ? (
                    <em>Cargando estado de sesión...</em>
                ) : user ? (
                    <div>Autenticado como: <strong>{user.name || user.email}</strong> (id: {user.id})</div>
                ) : (
                    <div style={{ color: 'darkred' }}>No autenticado — inicia sesión para agregar contactos</div>
                )}
            </div>
            <h2>Gestión de Contactos</h2>
            <form onSubmit={handleSubmit} className="form-inline">
                <input placeholder="Nombre" value={name} onChange={e => setName(e.target.value)} required />
                <input placeholder="Nº Cuenta" value={account} onChange={e => setAccount(e.target.value)} required />
                <button type="submit" className="btn-primary">
                    {editingId ? "Actualizar" : "Agregar"}
                </button>
                {editingId && <button type="button" onClick={() => {setEditingId(null); setName(''); setAccount('')}}>Cancelar</button>}
            </form>

            <table className="contacts-table">
                <thead>
                    <tr><th>Nombre</th><th>Cuenta</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    {(contacts || []).filter(c => !(user && (c.contact_user_id === user.id))).map(c => (
                        <tr key={c.id}>
                            <td>{c.name}</td>
                            <td>{c.account_number}</td>
                            <td>
                                <button onClick={() => startEdit(c)} className="btn-sm">Editar</button>
                                <button onClick={async () => {
                                    const ok = await removeContact(c.id);
                                    if (ok) alert('Contacto eliminado');
                                }} className="btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}