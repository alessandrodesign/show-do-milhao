import React from 'react';
import { Outlet } from 'react-router-dom';
import Navbar from '../components/Navbar';

export default function PublicLayout() {
    return (
        <div className="min-h-screen flex flex-col bg-gray-50">
            <Navbar />
            <main className="flex-grow p-6">
                <Outlet />
            </main>
            <footer className="text-center py-4 bg-gray-100 text-sm text-gray-600">
                © {new Date().getFullYear()} Show do Milhão
            </footer>
        </div>
    );
}
