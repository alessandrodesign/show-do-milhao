import React from 'react';
import {NavLink} from 'react-router-dom';
import {useAuth} from '../hooks/useAuth';

export default function AdminSidebar() {
    const {user, logout} = useAuth();

    const menu = [
        {to: '/admin/dashboard', label: '🏠 Dashboard'},
        {to: '/admin/categories', label: '📚 Categorias'},
        {to: '/admin/difficulties', label: '🎯 Dificuldades'},
        {to: '/admin/questions', label: '❓ Perguntas'},
        {to: '/admin/users', label: '👥 Usuários'},
        {to: '/admin/logs', label: '📝 Auditoria'},
    ];

    return (
        <aside className="w-64 bg-indigo-800 text-white flex flex-col">
            <div className="text-center py-4 text-lg font-bold border-b border-indigo-700">Painel Admin</div>
            <nav className="flex-1 overflow-y-auto">
                {menu.map(m => (
                    <NavLink
                        key={m.to}
                        to={m.to}
                        className={({isActive}) =>
                            `block px-4 py-2 hover:bg-indigo-700 ${isActive ? 'bg-indigo-700' : ''}`
                        }
                    >
                        {m.label}
                    </NavLink>
                ))}
            </nav>
            <div className="border-t border-indigo-700 p-4 text-sm flex justify-between items-center">
                <span>{user?.name}</span>
                <button onClick={logout} className="bg-red-600 px-2 py-1 rounded">Sair</button>
            </div>
        </aside>
    );
}
