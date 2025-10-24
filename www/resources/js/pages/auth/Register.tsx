import React, {useState} from 'react';
import api from '../../api/api';
import {useNavigate} from 'react-router-dom';

export default function Register() {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirm, setConfirm] = useState('');
    const [msg, setMsg] = useState('');
    const nav = useNavigate();

    const submit = async (e: any) => {
        e.preventDefault();
        try {
            await api.post('/register', {name, email, password, password_confirmation: confirm});
            setMsg('Cadastro realizado! Faça login.');
            setTimeout(() => nav('/login'), 1500);
        } catch {
            setMsg('Erro ao registrar.');
        }
    };

    return (
        <div className="max-w-md mx-auto mt-20 space-y-4">
            <h2 className="text-2xl font-bold text-center">Cadastrar</h2>
            <form onSubmit={submit} className="space-y-3">
                <input className="border p-2 w-full" placeholder="Nome" value={name}
                       onChange={e => setName(e.target.value)}/>
                <input type="email" className="border p-2 w-full" placeholder="Email" value={email}
                       onChange={e => setEmail(e.target.value)}/>
                <input type="password" className="border p-2 w-full" placeholder="Senha" value={password}
                       onChange={e => setPassword(e.target.value)}/>
                <input type="password" className="border p-2 w-full" placeholder="Confirmar senha" value={confirm}
                       onChange={e => setConfirm(e.target.value)}/>
                <button className="bg-green-600 text-white w-full py-2 rounded">Registrar</button>
            </form>
            {msg && <div className="text-center text-sm text-gray-600">{msg}</div>}
        </div>
    );
}
