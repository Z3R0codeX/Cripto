import React, { useState } from 'react';
import { useBank } from '../context/BankContext';

export default function UserProfile() {
    const { user, editUser, deleteAccount } = useBank();
    const [name, setName] = useState(user.name);
    const [email, setEmail] = useState(user.email);

    return (
        <div>
            <h2>Perfil de Usuario</h2>
            <div className="card">
                <form onSubmit={(e) => { e.preventDefault(); editUser(name, email); }}>
                    <label>Nombre:</label>
                    <input value={name} onChange={e => setName(e.target.value)} />
                    <label>Email:</label>
                    <input value={email} onChange={e => setEmail(e.target.value)} />
                    <button type="submit" className="btn-primary">Guardar Cambios</button>
                </form>
            </div>
            
            <div className="danger-zone">
                <h3>Zona de Peligro</h3>
                <button onClick={deleteAccount} className="btn-danger">Eliminar mi Cuenta</button>
            </div>
        </div>
    );
}