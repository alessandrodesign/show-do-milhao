import {Toaster} from "@/components/ui/toaster";
import {Toaster as Sonner} from "@/components/ui/sonner";
import {TooltipProvider} from "@/components/ui/tooltip";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {BrowserRouter, Routes, Route} from "react-router-dom";
import GameSetup from "./pages/GameSetup";
import GamePlay from "./pages/GamePlay";
import GameOver from "./pages/GameOver";
import Victory from "./pages/Victory";
import Ranking from "./pages/Ranking";
import NotFound from "./pages/NotFound";
import AdminLogin from "./pages/admin/Login";
import QuestionList from "./pages/admin/QuestionList";
import QuestionForm from "./pages/admin/QuestionForm";
import Categories from "./pages/admin/Categories";
import Difficulties from "./pages/admin/Difficulties";
import {Theme} from "@radix-ui/themes";

const queryClient = new QueryClient();

const App = () => (
    <QueryClientProvider client={queryClient}>
        <Theme>
            <TooltipProvider>
                <Toaster/>
                <Sonner/>
                <BrowserRouter>
                    <Routes>
                        <Route path="/" element={<GameSetup/>}/>
                        <Route path="/game/play" element={<GamePlay/>}/>
                        <Route path="/game/over" element={<GameOver/>}/>
                        <Route path="/game/victory" element={<Victory/>}/>
                        <Route path="/ranking" element={<Ranking/>}/>
                        <Route path="/admin/login" element={<AdminLogin/>}/>
                        <Route path="/admin/questions" element={<QuestionList/>}/>
                        <Route path="/admin/questions/new" element={<QuestionForm/>}/>
                        <Route path="/admin/questions/edit/:id" element={<QuestionForm/>}/>
                        <Route path="/admin/categories" element={<Categories/>}/>
                        <Route path="/admin/difficulties" element={<Difficulties/>}/>
                        <Route path="*" element={<NotFound/>}/>
                    </Routes>
                </BrowserRouter>
            </TooltipProvider>
        </Theme>
    </QueryClientProvider>
);

export default App;
