import React, { useState } from 'react';
import { useBank } from '../context/BankContext';
import { useNavigate } from 'react-router-dom';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const { login } = useBank();
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        const success = await login(email, password);
        if (success) navigate('/dashboard');
        else alert("Credenciales incorrectas");
    };

    return (
        <div className="container center-screen">
            <div className="card">
                <h2>Iniciar Sesión</h2>
                <form onSubmit={handleSubmit}>
                    <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} required />
                    <input type="password" placeholder="Contraseña" value={password} onChange={e => setPassword(e.target.value)} required />
                    <button type="submit" className="btn-primary">Entrar</button>
                </form>
            </div>
        </div>
    );
}