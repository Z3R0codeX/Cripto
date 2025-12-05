import React, { useState } from 'react';
import { useBank } from '../context/BankContext';
import { useNavigate, Link } from 'react-router-dom';

export default function Register() {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const { register } = useBank();
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (password !== passwordConfirmation) {
            alert('Las contraseñas no coinciden');
            return;
        }
        const ok = await register(name, email, password, passwordConfirmation);
        if (ok) navigate('/dashboard');
    };

    return (
        <div className="container center-screen">
            <div className="card">
                <h2>Crear cuenta</h2>
                <form onSubmit={handleSubmit}>
                    <input placeholder="Nombre" value={name} onChange={e => setName(e.target.value)} required />
                    <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} required />
                    <input type="password" placeholder="Contraseña" value={password} onChange={e => setPassword(e.target.value)} required />
                    <input type="password" placeholder="Confirmar contraseña" value={passwordConfirmation} onChange={e => setPasswordConfirmation(e.target.value)} required />
                    <button type="submit" className="btn-primary">Registrar</button>
                </form>
                <p style={{ marginTop: 12 }}>¿Ya tienes cuenta? <Link to="/">Inicia sesión</Link></p>
            </div>
        </div>
    );
}
