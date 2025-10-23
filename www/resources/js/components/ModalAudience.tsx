import React from 'react';

export default function ModalAudience({
                                          open,
                                          alternatives,
                                          onClose
                                      }: {
    open: boolean;
    alternatives: { letter: string; text: string }[];
    onClose: () => void;
}) {
    if (!open) return null;

    // simulação de votos aleatórios totalizando 100 %
    const votes = alternatives.map(() => Math.floor(Math.random() * 40) + 10);
    const total = votes.reduce((a, b) => a + b, 0);
    const percent = votes.map((v) => Math.round((v / total) * 100));

    return (
        <div className="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div className="bg-white p-6 rounded-xl w-full max-w-md text-center space-y-4">
                <h3 className="text-xl font-bold mb-2">📊 Opinião dos Universitários</h3>
                <ul className="space-y-2">
                    {alternatives.map((a, i) => (
                        <li key={a.letter} className="flex justify-between border-b pb-1">
                            <span>{a.letter}) {a.text}</span>
                            <strong>{percent[i]} %</strong>
                        </li>
                    ))}
                </ul>
                <button
                    onClick={onClose}
                    className="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
                >
                    Fechar
                </button>
            </div>
        </div>
    );
}
