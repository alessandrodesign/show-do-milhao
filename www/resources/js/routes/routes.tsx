import React, {JSX} from 'react';
import {BrowserRouter, Routes, Route, Navigate} from 'react-router-dom';
import PublicLayout from '../layouts/PublicLayout';
import AuthenticatedLayout from '../layouts/AuthenticatedLayout';
import {useAuth} from '../hooks/useAuth';
import Home from '../pages/Home';
import QuestionList from '../pages/admin/QuestionList';
import GamePlay from '../pages/game/GamePlay';
import GameStart from '../pages/game/GameStart';
import Ranking from '../pages/game/Ranking';
import AdminQuestionForm from '../pages/admin/AdminQuestionForm';
import Stats from '../pages/game/Stats';
import Login from '../pages/auth/Login';
import Register from '../pages/auth/Register';

function PrivateRoute({children}: { children: JSX.Element }) {
    const {user, loading} = useAuth();

    if (loading) return <div>Carregando...</div>;
    return user ? children : <Navigate to="/" replace/>;
}

export default function AppRoutes() {
    return (
        <BrowserRouter>
            <Routes>
                <Route element={<PublicLayout/>}>
                    <Route path="/" element={<Home/>}/>
                    <Route path="/login" element={<Login />} />
                    <Route path="/register" element={<Register />} />
                    <Route path="/game/start" element={<GameStart/>}/>
                    <Route path="/game/play" element={<GamePlay/>}/>
                    <Route path="/game/ranking" element={<Ranking/>}/>
                    <Route path="/game/stats/:id" element={<Stats />} />
                </Route>

                <Route
                    path="/admin"
                    element={
                        <PrivateRoute>
                            <AuthenticatedLayout/>
                        </PrivateRoute>
                    }
                >
                    <Route path="questions" element={<QuestionList/>}/>
                    <Route path="questions/new" element={<AdminQuestionForm/>}/>
                    <Route path="questions/:id" element={<AdminQuestionForm/>}/>
                </Route>
            </Routes>
        </BrowserRouter>
    );
}
