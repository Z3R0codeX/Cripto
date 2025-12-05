import React, { useState } from 'react';
import { useBank } from '../context/BankContext';
import { useNavigate, Link } from 'react-router-dom';

export default function Login() {
    // 1. Estados para manejar los inputs
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    
    // 2. Hooks para navegación y lógica de autenticación
    const { login } = useBank();
    const navigate = useNavigate();

    // 3. Manejador del envío del formulario
    const handleSubmit = async (e) => {
        e.preventDefault();
        // Simulación de autenticación
        const success = await login(email, password);
        
        if (success) {
            navigate('/dashboard');
        } else {
            alert("Credenciales incorrectas. Intenta con un email y contraseña válidos.");
        }
    };

    return (
        // Contenedor principal con fondo degradado y centrado
        <div className="center-screen">
            
            {/* Tarjeta de Login con estilos de card + max-width */}
            <div className="login-card"> 
                
                <h2>Iniciar Sesión</h2>
                
                <form onSubmit={handleSubmit}>
                    <input 
                        type="email" 
                        placeholder="Email" 
                        value={email} 
                        onChange={e => setEmail(e.target.value)} 
                        required 
                    />
                    <input 
                        type="password" 
                        placeholder="Contraseña" 
                        value={password} 
                        onChange={e => setPassword(e.target.value)} 
                        required 
                    />
                    <button type="submit" className="btn-primary">Entrar</button>
                </form>
                
                {/* Enlace de Registro */}
                <p style={{ textAlign: 'center', marginTop: '20px', fontSize: '0.9rem', color: 'var(--text-muted)' }}>
                    ¿No tienes cuenta? 
                    <Link 
                        to="/register" 
                        style={{ color: 'var(--col-hot-pink)', fontWeight: '600', textDecoration: 'none', marginLeft: '5px' }}>
                        Regístrate
                    </Link>
                </p>
            </div>
        </div>
    );
}