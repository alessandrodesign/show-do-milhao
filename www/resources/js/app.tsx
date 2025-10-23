import React from 'react';
import {createRoot} from 'react-dom/client';
import AppRoutes from './routes/routes';
import {AuthProvider} from './context/AuthContext';
import {GameProvider} from "./context/GameContext";
import {RankingProvider} from "./context/RankingContext";

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(
        <React.StrictMode>
            <AuthProvider>
                <GameProvider>
                    <RankingProvider>
                        <AppRoutes/>
                    </RankingProvider>
                </GameProvider>
            </AuthProvider>
        </React.StrictMode>
    );
}
