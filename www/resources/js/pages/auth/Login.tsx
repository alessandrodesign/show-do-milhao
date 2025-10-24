import React, {useState} from 'react';
import {useAuth} from '../../hooks/useAuth';
import {useNavigate} from 'react-router-dom';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const {login} = useAuth();
    const nav = useNavigate();

    const submit = async (e: any) => {
        e.preventDefault();
        const ok = await login(email, password);
        if (ok) nav('/'); else setError('Email ou senha inválidos');
    };

    return (
        <div className="max-w-md mx-auto mt-20 space-y-4">
            <h2 className="text-2xl font-bold text-center">Entrar</h2>
            {error && <div className="text-red-600">{error}</div>}
            <form onSubmit={submit} className="space-y-3">
                <input type="email" className="border p-2 w-full" placeholder="Email" value={email}
                       onChange={e => setEmail(e.target.value)}/>
                <input type="password" className="border p-2 w-full" placeholder="Senha" value={password}
                       onChange={e => setPassword(e.target.value)}/>
                <button className="bg-blue-600 text-white w-full py-2 rounded">Entrar</button>
            </form>
        </div>
    );
}
