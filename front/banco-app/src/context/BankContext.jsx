import React, { createContext, useState, useContext, useEffect } from 'react';
import api from '../api/axiosConfig';

const BankContext = createContext();

export const useBank = () => useContext(BankContext);

export const BankProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [contacts, setContacts] = useState([]);
    const [transactions, setTransactions] = useState([]);
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
            // El backend retorna 'access_token' (LoginController.php)
            localStorage.setItem('token', res.data.access_token || res.data.token);
            setUser(res.data.user || res.data);
            // refresh contacts and transactions after login
            try { await loadContacts(); await loadTransactions(); } catch(e){}
            return true;
        } catch (error) { return false; }
    };

    const register = async (name, email, password, password_confirmation) => {
        try {
            const res = await api.post('/register', { name, email, password, password_confirmation });
            localStorage.setItem('token', res.data.access_token || res.data.token);
            setUser(res.data.user || res.data);
            // load initial user data
            try { await loadContacts(); await loadTransactions(); } catch(e){}
            return true;
        } catch (error) {
            console.error('register error', error);
            const serverMsg = error?.response?.data?.message || error?.response?.data?.errors || error.message;
            alert('Error al registrar: ' + (serverMsg || 'Revisa la consola'));
            return false;
        }
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
                // preserve original contact user id if provided by the API
                contact_user_id: c.contacto_user_id || c.contactoUserId || c.contact_user_id || (c.contactUser && c.contactUser.id) || null,
                name: c.NAME || (c.contactUser && (c.contactUser.name || c.contactUser.email)) || '',
                account_number: c.contactUser ? (c.contactUser.email || '') : (c.account_number || '')
            }));
            setContacts(mapped);
        } catch (e) { }
    };

    const loadTransactions = async () => {
        try {
            const res = await api.get('/transactions');
            const list = res.data.data || res.data;
            setTransactions(list || []);
        } catch (e) { console.error('loadTransactions error', e); }
    };

    const addContact = async (name, account) => {
        // Ensure user is authenticated (we use token in localStorage)
        const token = localStorage.getItem('token');
        if (!token) {
            alert('No estás autenticado. Por favor inicia sesión.');
            return false;
        }

        try {
            // El backend espera 'contacto_user_id' (id del usuario) y 'NAME'.
            // Intentamos interpretar 'account' como id numérico; si no, buscamos usuario por email/nombre.
            let contacto_user_id = parseInt((account || '').toString().trim(), 10);
            if (!contacto_user_id || isNaN(contacto_user_id)) {
                // Buscar usuario por email o nombre
                const res = await api.get(`/users/search?query=${encodeURIComponent(account)}`);
                const users = res.data.data || [];
                if (users.length === 0) {
                    alert('No se encontró ningún usuario con ese identificador (email/nombre).');
                    return false;
                }
                contacto_user_id = users[0].id; // tomar el primero
            }

            const payload = { contacto_user_id, NAME: name };
            console.debug('addContact payload', payload);
            const resp = await api.post('/contacts', payload);
            console.debug('addContact resp', resp.status, resp.data);
            await loadContacts();
            return true;
        } catch (e) {
            console.error('addContact error', e);
            const serverMsg = e?.response?.data?.message || e?.response?.data?.detail || e.message;
            alert('Error al agregar contacto: ' + (serverMsg || 'Revisa la consola para más detalles'));
            return false;
        }
    };

    const editContact = async (id, name, account) => {
        // Ensure user is authenticated (we use token in localStorage)
        const token = localStorage.getItem('token');
        if (!token) {
            alert('No estás autenticado. Por favor inicia sesión.');
            return false;
        }

        try {
            const payload = {};
            if (name) payload.NAME = name;
            // if account provided and numeric, treat as contacto_user_id
            if (account) {
                let contacto_user_id = parseInt((account || '').toString().trim(), 10);
                if (!contacto_user_id || isNaN(contacto_user_id)) {
                    const res = await api.get(`/users/search?query=${encodeURIComponent(account)}`);
                    const users = res.data.data || [];
                    if (users.length) contacto_user_id = users[0].id;
                }
                if (contacto_user_id && !isNaN(contacto_user_id)) payload.contacto_user_id = contacto_user_id;
            }
            console.debug('editContact payload', id, payload);
            const resp = await api.put(`/contacts/${id}`, payload);
            console.debug('editContact resp', resp.status, resp.data);
            await loadContacts();
            return true;
        } catch (e) {
            console.error('editContact error', e);
            const serverMsg = e?.response?.data?.message || e?.response?.data?.detail || e.message;
            alert('Error al actualizar contacto: ' + (serverMsg || 'Revisa la consola para más detalles'));
            return false;
        }
    };

    const removeContact = async (id) => {
        if (!confirm("¿Eliminar?")) return false;
        try {
            console.debug('DELETE /contacts/' + id);
            const resp = await api.delete(`/contacts/${id}`);
            console.debug('delete resp', resp.status, resp.data);
            await loadContacts();
            return true;
        } catch (e) {
            console.error('removeContact error', e);
            const serverMsg = e?.response?.data?.message || e?.response?.data?.detail || e.message;
            alert('Error al eliminar contacto: ' + (serverMsg || 'Revisa la consola'));
            return false;
        }
    };

    const transfer = async (amount, contactId) => {
        try {
            // Round amount to 2 decimals
            const amt = Number(parseFloat(amount).toFixed(2));
            const res = await api.post('/transfer', { amount: amt, contact_id: contactId });
            setUser({ ...user, balance: res.data.new_balance });
            // refresh transactions
            await loadTransactions();
            alert("Transferencia exitosa");
            return true;
        } catch (e) {
            console.error('transfer error', e);
            const serverMsg = e?.response?.data?.message || e?.response?.data?.detail || e.message;
            alert('Error en transferencia: ' + (serverMsg || 'Revisa la consola'));
            return false;
        }
    };

    const editUser = async (name, email) => {
        try { const res = await api.put('/user', { name, email }); setUser(res.data); alert("Guardado"); } 
        catch (e) { alert("Error"); }
    };

    return (
        <BankContext.Provider value={{
            user, contacts, loading, login, register, logout, deleteAccount,
            transactions, loadTransactions,
            loadContacts, addContact, editContact, removeContact, transfer, editUser
        }}>
            {children}
        </BankContext.Provider>
    );
};