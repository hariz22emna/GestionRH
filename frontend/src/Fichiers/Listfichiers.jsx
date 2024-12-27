import React from 'react';
import Sidebar from '../SideBar/Sidebar';

const Listfichiers = () => {
  return (
    <div className="list-users-page">
      {/* Affichage du Sidebar */}
      <Sidebar />

      {/* Contenu de la page Users */}
      <div className="content">
        <h1>Liste des utilisateurs</h1>
        {/* Ajoutez ici les éléments pour afficher la liste des utilisateurs */}
      </div>
    </div>
  );
};

export default Listfichiers;
