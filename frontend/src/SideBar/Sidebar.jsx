import React, { useState } from 'react';
import { useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import Avatar from './AvatarRappels';  
import './Sidebar.css';
import logo from '../assets/logo.png';

const Sidebar = () => {
  const [activeId, setActiveId] = useState(1);
  const user = useSelector((state) => state.user); // Accédez à l'utilisateur depuis le state Redux
  const navigate = useNavigate();

  const rows = [
    { id: 1, label: "Dashboard", icon: "fa-solid fa-gauge", link: "/dashboard" },
    { id: 2, label: "Utilisateurs", icon: "fa-solid fa-users", link: "/users" },
    { id: 3, label: "Rappels", icon: "fa-solid fa-clock", link: "/rappels" },
    { id: 5, label: "Evaluations", icon: "fa-solid fa-star", link: "/evaluations" },
    { id: 7, label: "Exigences", icon: "fa-solid fa-folder-open", link: "/manage-files" },
    { id: 8, label: "Archives", icon: "fa-solid fa-box-archive", link: "/archives" },
  ];

  const handleElementClick = (id, link) => {
    setActiveId(id);
    navigate(link);
  };

  return (
    <div className="sidebar">
      <div className="logo">
        <img src={logo} alt="CHELOTA" width="128" height="60" />
      </div>
      {rows.map((row) => (
        <div
          key={row.id}
          className={`sidebar-item ${activeId === row.id ? 'active' : ''}`}
          onClick={() => handleElementClick(row.id, row.link)}
        >
          <i className={`icon ${row.icon}`} />
          <span>{row.label}</span>
        </div>
      ))}
      <div className="user-info">
        {/* Affichage de l'avatar et du nom de l'utilisateur */}
        <Avatar user={user} />
        <div className="user-details">
          <span>{user?.name || 'Utilisateur'}</span> {/* Affiche le nom de l'utilisateur ou 'Utilisateur' par défaut */}
        </div>
      </div>
    </div>
  );
};

export default Sidebar;
