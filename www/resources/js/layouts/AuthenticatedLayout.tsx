import React from 'react';
import {Outlet} from 'react-router-dom';
import Navbar from '../components/Navbar';

export default function AuthenticatedLayout() {
    return (
        <div className="min-h-screen flex flex-col bg-gray-50">
            <Navbar/>
            <main className="flex-grow p-6">
                <Outlet/>
            </main>
        </div>
    );
}
