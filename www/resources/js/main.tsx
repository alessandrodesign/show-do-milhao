import { createRoot } from "react-dom/client";
import App from "./App";
import "@radix-ui/themes/styles.css";
import "./index.css";

createRoot(document.getElementById("app")!).render(<App />);
