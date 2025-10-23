import React from 'react';

export default function ModalSuspense({
                                          open,
                                          onReveal
                                      }: {
    open: boolean;
    onReveal: () => void;
}) {
    if (!open) return null;
    return (
        <div className="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div className="bg-white rounded-xl p-8 max-w-md w-full text-center space-y-4">
                <h3 className="text-xl font-bold">Momento de tensão...</h3>
                <p>Pronto para ver a resposta?</p>
                <button onClick={onReveal} className="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                    Ver resposta
                </button>
            </div>
        </div>
    );
}
