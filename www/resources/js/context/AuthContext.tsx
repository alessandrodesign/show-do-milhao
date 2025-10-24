import React, { createContext, useState, useEffect, ReactNode } from 'react';
import api from '../api/api';

export interface User {
    id: number;
    name: string;
    email: string;
    is_admin?: boolean;
}

interface AuthContextType {
    user: User | null;
    loading: boolean;
    login: (email: string, password: string) => Promise<boolean>;
    logout: () => Promise<void>;
}

export const AuthContext = createContext<AuthContextType>({
    user: null,
    loading: false,
    login: async () => false,
    logout: async () => {},
});

export function AuthProvider({ children }: { children: ReactNode }) {
    const [user, setUser] = useState<User | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadUser = async () => {
            try {
                const res = await api.get('/me'); // endpoint de perfil
                setUser(res.data);
            } catch {
                setUser(null);
            } finally {
                setLoading(false);
            }
        };
        loadUser();
    }, []);

    const login = async (email: string, password: string) => {
        try {
            await api.get('/sanctum/csrf-cookie'); // necessário para Sanctum
            const res = await api.post('/login', { email, password });
            setUser(res.data.user);
            return true;
        } catch {
            return false;
        }
    };

    const logout = async () => {
        await api.post('/logout').catch(() => {});
        setUser(null);
    };

    return (
        <AuthContext.Provider value={{ user, loading, login, logout }}>
            {children}
        </AuthContext.Provider>
    );
}
