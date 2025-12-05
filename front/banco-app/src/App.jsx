import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { BankProvider, useBank } from './context/BankContext';
import Login from './pages/Login';      
import Dashboard from './pages/Dashboard';
import Contacts from './pages/Contacts';
import Transfer from './pages/Transfers';
import Home from './pages/Home';    
import UserProfile from './pages/UsersProfile';
import './App.css';

const ProtectedRoute = ({ children }) => {
    const { user, loading } = useBank();
    if (loading) return <div>Cargando...</div>;
    return user ? children : <Navigate to="/" />;
};

function App() {
    return (
        <BankProvider>
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Login />} />
                    <Route path="/dashboard" element={<ProtectedRoute><Dashboard /></ProtectedRoute>}>
                        <Route index element={<Home />} />
                        <Route path="contacts" element={<Contacts />} />
                        <Route path="transfer" element={<Transfer />} />
                        <Route path="profile" element={<UserProfile />} />
                    </Route>
                </Routes>
            </BrowserRouter>
        </BankProvider>
    );
}

export default App;