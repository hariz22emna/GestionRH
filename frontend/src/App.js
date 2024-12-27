import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import { Provider } from 'react-redux'; // Importation du Provider
import Login from './Authentification/Login';
import Register from './Authentification/Register';
import Sidebar from './SideBar/Sidebar';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Listusers from './Users/Listusers';
import Listrappels from './Rappels/Listrappels';
import Listevaluations from './Evaluations/Listevaluations';
import Listfichiers from './Fichiers/Listfichiers';
import Listarchives from './Archive/Listarchives';
import Adduser from './Users/Adduser';







// import Listusers from './Users/Listusers';

import './App.css';  // Assurez-vous que App.css est bien dans le dossier src
import store from './store'; // Importation de ton store Redux
import EditUser from './Users/Edituser';

function App() {
  return (
    <Provider store={store}> {/* Envelopper l'application avec le Provider */}
      <Router>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/sidebar" element={<Sidebar />} />
          <Route path="/users" element={<Listusers />} />
          <Route path="/rappels" element={<Listrappels />} />
          <Route path="/evaluations" element={<Listevaluations />} />
          <Route path="/manage-files" element={<Listfichiers />} />
          <Route path="/archives" element={<Listarchives />} />
          <Route path="/addusers" element={<Adduser />} />
          <Route path="/editusers/:id" element={<EditUser />} />








          {/* <Route path="/users" element={<Listusers />} /> */}

        </Routes>
      </Router>
    </Provider>
  );
}

export default App;
