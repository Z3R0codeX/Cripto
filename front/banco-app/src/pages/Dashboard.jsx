import React, { useEffect } from 'react';
import { useBank } from '../context/BankContext';
import { Link, Outlet } from 'react-router-dom';

export default function Dashboard() {
    const { user, logout } = useBank();

    if (!user) return <p>Cargando...</p>;

    return (
        <div className="dashboard-layout">
            <nav className="sidebar">
                <h3>Mi Banco</h3>
                <div className="user-info">
                    <p>{user.name}</p>
                    <p className="balance">${user.balance}</p>
                </div>
                <hr/>
                <Link to="/dashboard">Inicio</Link>
                <Link to="transfer">Transferir</Link>
                <Link to="contacts">Contactos</Link>
                <Link to="profile">Mi Perfil</Link>
                <button onClick={logout} className="btn-logout">Cerrar Sesión</button>
            </nav>
            <main className="content">
                <Outlet />
            </main>
        </div>
    );
}