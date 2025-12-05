import React, { useState } from 'react';
import { useBank } from '../context/BankContext';

export default function UserProfile() {
    // Nota: Es mejor inicializar los estados con valores seguros o usar useEffect
    // Si 'user' es undefined al inicio, esto fallará. Asumiendo que 'user' se carga correctamente.
    const { user, editUser, deleteAccount } = useBank();
    const [name, setName] = useState(user?.name || '');
    const [email, setEmail] = useState(user?.email || '');
    const [isSaving, setIsSaving] = useState(false);

    const handleEditSubmit = async (e) => {
        e.preventDefault();
        setIsSaving(true);
        const success = await editUser(name, email);
        if (success) {
            alert("Perfil actualizado con éxito.");
        }
        setIsSaving(false);
    };

    const handleDelete = async () => {
        if (window.confirm("ADVERTENCIA: ¿Estás absolutamente seguro de que quieres ELIMINAR tu cuenta? Esta acción es irreversible.")) {
            const success = await deleteAccount();
            if (success) {
                alert("Cuenta eliminada con éxito. Serás redirigido al login.");
            }
        }
    };

    return (
        <div>
            <header style={{ marginBottom: '30px', borderBottom: '1px solid #eee', paddingBottom: '15px' }}>
                <h2>👤 Perfil de Usuario</h2>
                <p style={{ color: 'var(--text-muted)', margin: 0 }}>
                    Gestiona tu información personal y la configuración de tu cuenta.
                </p>
            </header>
            
            {/* --- SECCIÓN 1: EDICIÓN DE PERFIL --- */}
            <div className="card">
                <h4>Detalles de tu Perfil</h4>
                
                {/* Contenedor para limitar el ancho del formulario */}
                <div className="form-container">
                    <form onSubmit={handleEditSubmit}>
                        <label style={{ display: 'block', fontWeight: '500', marginBottom: '5px' }}>Nombre completo:</label>
                        <input 
                            value={name} 
                            onChange={e => setName(e.target.value)} 
                            required 
                        />
                        
                        <label style={{ display: 'block', fontWeight: '500', marginBottom: '5px' }}>Email:</label>
                        <input 
                            value={email} 
                            onChange={e => setEmail(e.target.value)} 
                            type="email" 
                            required 
                        />
                        
                        <div style={{ marginTop: '20px' }}>
                            <button 
                                type="submit" 
                                className="btn-primary" 
                                disabled={isSaving}
                            >
                                {isSaving ? "Guardando..." : "Guardar Cambios"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {/* --- SECCIÓN 2: ZONA DE PELIGRO --- */}
            <div className="card" style={{ marginTop: '30px' }}>
                <div className="danger-zone">
                    <h3>❌ Zona de Peligro</h3>
                    <p style={{ color: 'var(--text-muted)', marginBottom: '20px' }}>
                        Esta acción es irreversible y eliminará permanentemente todos tus datos y transacciones.
                    </p>
                    
                    <button 
                        onClick={handleDelete} 
                        className="btn-danger"
                        style={{ width: 'auto', padding: '10px 20px', fontWeight: '600' }}
                    >
                        Eliminar mi Cuenta Permanentemente
                    </button>
                </div>
            </div>
        </div>
    );
}