import React from 'react';
import { Link } from 'react-router-dom';

export default function Home() {
    return (
        <div className="text-center mt-20">
            <h1 className="text-4xl font-bold mb-6">Show do Milhão</h1>
            <Link
                to="/game/start"
                className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold"
            >
                Jogar Agora
            </Link>
        </div>
    );
}
