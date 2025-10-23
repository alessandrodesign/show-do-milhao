import React from 'react';

/**
 * Loader – componente genérico de carregamento
 * Mostra um spinner centralizado e uma mensagem opcional.
 */
export default function Loader({message}: { message?: string }) {
    return (
        <div className="flex flex-col items-center justify-center h-full py-10 text-gray-600">
            <svg
                className="animate-spin h-8 w-8 text-blue-600 mb-3"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    className="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    strokeWidth="4"
                ></circle>
                <path
                    className="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4l3.5-3.5L12 0v4a8 8 0 100 16v-4l-3.5 3.5L12 24v-4a8 8 0 01-8-8z"
                ></path>
            </svg>
            <p className="text-sm">{message ?? 'Carregando...'}</p>
        </div>
    );
}
