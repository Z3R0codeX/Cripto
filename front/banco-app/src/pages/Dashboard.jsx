import React from 'react';
import { useBank } from '../context/BankContext';
import { Link, Outlet } from 'react-router-dom';

export default function Dashboard() {
    const { user, logout } = useBank();

    if (!user) return <p>Cargando...</p>;

    // Función simple para formatear el saldo
    const formattedBalance = user.balance 
        ? parseFloat(user.balance).toLocaleString('en-US', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }) 
        : '0.00';

    return (
        <div className="dashboard-layout">
            {/* --- BARRA LATERAL (Sidebar) --- */}
            <nav className="sidebar">
                <h3>💰 Mi Banco</h3>
                
                {/* Información del Usuario y Saldo */}
                <div className="user-info">
                    <p style={{ fontWeight: '600' }}>{user.name}</p>
                    <p style={{ fontSize: '0.8rem', opacity: 0.7 }}>Saldo Disponible:</p>
                    <p className="balance">${formattedBalance}</p>
                </div>
                
                {/* Separador visual elegante */}
                <div style={{ height: '1px', background: 'rgba(255, 255, 255, 0.2)', margin: '15px 0' }}></div>

                {/* Enlaces de Navegación con Iconos */}
                <Link to="/dashboard">🏠 Inicio</Link>
                <Link to="transfer">💸 Transferir</Link>
                <Link to="contacts">👥 Contactos</Link>
                <Link to="profile">⚙️ Mi Perfil</Link>
                
                {/* Botón de Cerrar Sesión */}
                <button onClick={logout} className="btn-logout">Cerrar Sesión</button>
            </nav>
            
            {/* --- CONTENIDO PRINCIPAL (Main Content) --- */}
            <main className="content">
                {/* Aquí se renderizarán los componentes anidados (Home, Transfer, etc.) */}
                <Outlet />
            </main>
        </div>
    );
}