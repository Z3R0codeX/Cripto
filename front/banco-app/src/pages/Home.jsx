// src/pages/Home.jsx
import React from 'react';
import { useBank } from '../context/BankContext';
import { Link } from 'react-router-dom';

export default function Home() {
    const { user } = useBank();

    // Simulación de transacciones (Más adelante las traeremos de la API real)
    const recentTransactions = [
        { id: 1, type: 'Enviado', name: 'Netflix', amount: -15.00, date: 'Hoy' },
        { id: 2, type: 'Recibido', name: 'Nómina', amount: 2500.00, date: 'Ayer' },
        { id: 3, type: 'Enviado', name: 'Carlos Ruiz', amount: -50.00, date: '02 Dic' },
    ];

    return (
        <div>
            <h2 style={{ marginBottom: '20px' }}>Resumen General</h2>
            
            <div className="dashboard-grid">
                
                {/* TARJETA 1: SALDO PRINCIPAL (La más importante) */}
                <div className="card balance-card">
                    <h3>Saldo Disponible</h3>
                    <div className="amount">${user?.balance?.toLocaleString()}</div>
                    <p className="account-number">Cuenta: **** {user?.account_number?.slice(-4) || '0000'}</p>
                </div>

                {/* TARJETA 2: ACCIONES RÁPIDAS */}
                <div className="card actions-card">
                    <h3>¿Qué quieres hacer?</h3>
                    <div className="actions-grid">
                        <Link to="transfer" className="action-btn">
                            <span className="icon">💸</span>
                            Transferir
                        </Link>
                        <Link to="contacts" className="action-btn">
                            <span className="icon">👥</span>
                            Contactos
                        </Link>
                        <Link to="profile" className="action-btn">
                            <span className="icon">⚙️</span>
                            Ajustes
                        </Link>
                    </div>
                </div>

                {/* TARJETA 3: ÚLTIMOS MOVIMIENTOS (Ocupa ancho completo visualmente si es necesario) */}
                <div className="card history-card" style={{ gridColumn: '1 / -1' }}>
                    <h3>Últimos Movimientos</h3>
                    <table className="contacts-table">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Fecha</th>
                                <th style={{ textAlign: 'right' }}>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            {recentTransactions.map(t => (
                                <tr key={t.id}>
                                    <td>
                                        <strong style={{ color: t.amount > 0 ? '#2ecc71' : '#e74c3c' }}>
                                            {t.type}
                                        </strong> - {t.name}
                                    </td>
                                    <td>{t.date}</td>
                                    <td style={{ 
                                        textAlign: 'right', 
                                        fontWeight: 'bold',
                                        color: t.amount > 0 ? '#2ecc71' : '#000' 
                                    }}>
                                        {t.amount > 0 ? '+' : ''}{t.amount}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    );
}