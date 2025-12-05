import React, { createContext, useState, useContext, useEffect } from 'react';
import api from '../api/axiosConfig';

const BankContext = createContext();

export const useBank = () => useContext(BankContext);

export const BankProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [contacts, setContacts] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const checkAuth = async () => {
            const token = localStorage.getItem('token');
            if (token) {
                try {
                    const res = await api.get('/user');
                    setUser(res.data);
                } catch (error) {
                    localStorage.removeItem('token');
                }
            }
            setLoading(false);
        };
        checkAuth();
    }, []);

    // --- Actions ---
    const login = async (email, password) => {
        try {
            const res = await api.post('/login', { email, password });
            localStorage.setItem('token', res.data.token);
            setUser(res.data.user);
            return true;
        } catch (error) { return false; }
    };

    const logout = async () => {
        try { await api.post('/logout'); } catch (e) {}
        localStorage.removeItem('token');
        setUser(null);
    };

    const deleteAccount = async () => {
        if (!confirm("¿Borrar cuenta permanentemente?")) return;
        try { await api.delete('/user'); logout(); } catch (e) { alert("Error"); }
    };

    const loadContacts = async () => {
        try {
            const res = await api.get('/contacts');
            const list = res.data.data || res.data;
            // Normalizar al shape que usa la UI
            const mapped = (list || []).map(c => ({
                id: c.ID_CONTACTO || c.id,
                name: c.NAME || (c.contactUser && (c.contactUser.name || c.contactUser.email)) || '',
                account_number: c.contactUser ? (c.contactUser.email || '') : (c.account_number || '')
            }));
            setContacts(mapped);
        } catch (e) { }
    };

    const addContact = async (name, account) => {
        try {
            // El backend espera 'contacto_user_id' (id del usuario) y 'NAME'.
            // Aquí asumimos que el campo 'account' es el id numérico del usuario contacto.
            const contacto_user_id = Number(account);
            if (!contacto_user_id || isNaN(contacto_user_id)) throw new Error('El campo cuenta debe ser el id de usuario (numérico).');
            await api.post('/contacts', { contacto_user_id, NAME: name });
            await loadContacts();
            return true;
        } catch (e) { return false; }
    };

    const editContact = async (id, name, account) => {
        try {
            const payload = {};
            if (name) payload.NAME = name;
            // if account provided and numeric, treat as contacto_user_id
            const contacto_user_id = Number(account);
            if (contacto_user_id && !isNaN(contacto_user_id)) payload.contacto_user_id = contacto_user_id;
            await api.put(`/contacts/${id}`, payload);
            await loadContacts();
            return true;
        } catch (e) { return false; }
    };

    const removeContact = async (id) => {
        if (!confirm("¿Eliminar?")) return;
        try { await api.delete(`/contacts/${id}`); loadContacts(); } catch (e) {}
    };

    const transfer = async (amount, contactId) => {
        try {
            const res = await api.post('/transfer', { amount, contact_id: contactId });
            setUser({ ...user, balance: res.data.new_balance });
            alert("Transferencia exitosa");
            return true;
        } catch (e) { alert("Error en transferencia"); return false; }
    };

    const editUser = async (name, email) => {
        try { const res = await api.put('/user', { name, email }); setUser(res.data); alert("Guardado"); } 
        catch (e) { alert("Error"); }
    };

    return (
        <BankContext.Provider value={{
            user, contacts, loading, login, logout, deleteAccount,
            loadContacts, addContact, editContact, removeContact, transfer, editUser
        }}>
            {children}
        </BankContext.Provider>
    );
};