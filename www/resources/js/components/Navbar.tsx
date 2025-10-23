import React from 'react';
import {Link, useNavigate} from 'react-router-dom';
import {useAuth} from '../hooks/useAuth';

/**
 * Navbar – barra superior adaptável
 * Exibe links básicos e usuário autenticado (quando houver).
 */
export default function Navbar() {
    const {user, logout} = useAuth();
    const navigate = useNavigate();

    const handleLogout = async () => {
        await logout();
        navigate('/');
    };

    return (
        <nav className="bg-blue-700 text-white shadow-md">
            <div className="container mx-auto px-4 py-3 flex justify-between items-center">
                <Link to="/" className="text-lg font-bold tracking-wide">
                    🎮 Show do Milhão
                </Link>

                <div className="flex items-center gap-4">
                    <Link
                        to="/game/start"
                        className="hover:text-yellow-300 transition-colors"
                    >
                        Jogar
                    </Link>

                    {user ? (
                        <>
                            {user.is_admin && (
                                <Link
                                    to="/admin/questions"
                                    className="hover:text-yellow-300 transition-colors"
                                >
                                    Painel
                                </Link>
                            )}
                            <span className="text-sm bg-blue-800 px-2 py-1 rounded">
                👤 {user.name}
              </span>
                            <button
                                onClick={handleLogout}
                                className="text-sm bg-red-600 hover:bg-red-700 px-3 py-1 rounded"
                            >
                                Sair
                            </button>
                        </>
                    ) : (
                        <Link
                            to="/login"
                            className="text-sm bg-green-600 hover:bg-green-700 px-3 py-1 rounded"
                        >
                            Entrar
                        </Link>
                    )}
                </div>
            </div>
        </nav>
    );
}
