import React, { useEffect, useState } from 'react';
import axios from 'axios';

interface Category { id: number; name: string; }
interface Difficulty { id: number; name: string; }
interface Alternative { id: number; letter: string; text: string; is_correct: boolean; }
interface Question {
    id: number;
    statement: string;
    category: Category;
    difficulty: Difficulty;
    alternatives: Alternative[];
}

export default function QuestionList() {
    const [questions, setQuestions] = useState<Question[]>([]);

    useEffect(() => {
        axios.get('/api/v1/questions').then(res => {
            setQuestions(res.data.data);
        });
    }, []);

    return (
        <div className="container mx-auto p-4">
            <h2 className="text-2xl font-bold mb-4">Perguntas</h2>
            <table className="min-w-full border text-left">
                <thead className="bg-gray-100">
                <tr>
                    <th className="p-2">ID</th>
                    <th className="p-2">Enunciado</th>
                    <th className="p-2">Categoria</th>
                    <th className="p-2">Dificuldade</th>
                </tr>
                </thead>
                <tbody>
                {questions.map(q => (
                    <tr key={q.id} className="border-t">
                        <td className="p-2">{q.id}</td>
                        <td className="p-2">{q.statement}</td>
                        <td className="p-2">{q.category?.name}</td>
                        <td className="p-2">{q.difficulty?.name}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
