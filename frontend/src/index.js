import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App";
import "./index.css"; // Si tu as des styles globaux
import '@fortawesome/fontawesome-free/css/all.min.css';
import { Provider } from 'react-redux'; // Import du Provider de Redux
import store from './store';  // Import du store que vous avez créé


const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(
  <React.StrictMode>
      <Provider store={store}>  {/* Le store est passé ici */}
    <App />
  </Provider>,
  </React.StrictMode>
);
