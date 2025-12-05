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
        try { const res = await api.get('/contacts'); setContacts(res.data); } catch (e) {}
    };

    const addContact = async (name, account) => {
        try { await api.post('/contacts', { name, account_number: account }); loadContacts(); return true; } 
        catch (e) { return false; }
    };

    const editContact = async (id, name, account) => {
        try { await api.put(`/contacts/${id}`, { name, account_number: account }); loadContacts(); return true; } 
        catch (e) { return false; }
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