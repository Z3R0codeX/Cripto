import React, { useState, useEffect } from 'react';
import { useBank } from '../context/BankContext';

export default function Contacts() {
    const { contacts, loadContacts, addContact, editContact, removeContact, user, loading } = useBank();
    const [name, setName] = useState('');
    const [account, setAccount] = useState('');
    const [editingId, setEditingId] = useState(null);

    // Cargar contactos al montar el componente
    useEffect(() => { loadContacts(); }, []);

    // Reinicia los campos de edición
    const resetForm = () => {
        setEditingId(null);
        setName('');
        setAccount('');
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        if (!user) {
            alert('Debes iniciar sesión para gestionar contactos.');
            return;
        }

        let success = false;
        if (editingId) {
            success = await editContact(editingId, name, account);
            if (success) {
                alert('Contacto actualizado con éxito.');
            }
        } else {
            success = await addContact(name, account);
            if (success) alert('Contacto agregado con éxito.');
        }
        
        if (success) { resetForm(); }
    };

    const startEdit = (c) => {
        setEditingId(c.id);
        setName(c.name);
        setAccount(c.account_number);
    };

    const handleRemove = async (id) => {
        if (window.confirm('¿Estás seguro de que quieres eliminar este contacto?')) {
            const ok = await removeContact(id);
            if (ok) alert('Contacto eliminado.');
        }
    };

    // Filtra los contactos para no mostrar los del propio usuario (si el ID de usuario existe)
    const filteredContacts = (contacts || []).filter(c => !(user && c.contact_user_id === user.id));

    return (
        // Contenedor principal de la vista de contactos dentro del dashboard
        <div className="card">
            
            <header style={{ marginBottom: '30px', borderBottom: '1px solid #eee', paddingBottom: '15px' }}>
                <h2>Gestión de Contactos 👥</h2>
                <p style={{ color: 'var(--text-muted)', margin: 0 }}>
                    Aquí puedes añadir, editar y eliminar destinatarios para tus transferencias.
                </p>
                {/* Indicador de autenticación más discreto */}
                {loading ? (
                    <em style={{ color: 'var(--text-muted)' }}>Verificando sesión...</em>
                ) : !user && (
                    <div style={{ color: 'var(--col-hot-pink)', fontWeight: '600', marginTop: '10px' }}>
                        ⚠️ Inicia sesión para agregar contactos.
                    </div>
                )}
            </header>

            {/* --- SECCIÓN 1: Formulario de Agregar/Editar --- */}
            <div className="contacts-section">
                <h4>{editingId ? "✏️ Editar Contacto" : "➕ Añadir Nuevo Contacto"}</h4>
                
                <form onSubmit={handleSubmit} className="form-inline">
                    <input 
                        placeholder="Nombre completo o alias" 
                        value={name} 
                        onChange={e => setName(e.target.value)} 
                        required 
                        disabled={!user}
                    />
                    <input 
                        placeholder="Número de Cuenta (Ej: 1234567890)" 
                        value={account} 
                        onChange={e => setAccount(e.target.value)} 
                        required 
                        disabled={!user}
                    />
                    <button type="submit" className="btn-primary" disabled={!user}>
                        {editingId ? "Actualizar" : "Agregar"}
                    </button>
                    {editingId && (
                        <button 
                            type="button" 
                            onClick={resetForm} 
                            className="btn-sm btn-secondary"
                        >
                            Cancelar
                        </button>
                    )}
                </form>
            </div>
            
            {/* --- SECCIÓN 2: Listado de Contactos --- */}
            <div className="contacts-section">
                <h4 style={{ marginTop: '30px' }}>Listado de Destinatarios ({filteredContacts.length})</h4>
                
                {filteredContacts.length > 0 ? (
                    <table className="contacts-table">
                        <thead>
                            <tr><th>Nombre</th><th>Cuenta</th><th style={{ width: '200px' }}>Acciones</th></tr>
                        </thead>
                        <tbody>
                            {filteredContacts.map(c => (
                                <tr key={c.id}>
                                    <td>{c.name}</td>
                                    <td>{c.account_number}</td>
                                    <td>
                                        {/* Botones de acción con los nuevos estilos */}
                                        <button 
                                            onClick={() => startEdit(c)} 
                                            className="btn-sm btn-secondary"
                                        >
                                            Editar
                                        </button>
                                        <button 
                                            onClick={() => handleRemove(c.id)} 
                                            className="btn-sm btn-danger"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                ) : (
                    <p style={{ color: 'var(--text-muted)' }}>No tienes contactos guardados. ¡Añade uno usando el formulario de arriba!</p>
                )}
            </div>
        </div>
    );
}