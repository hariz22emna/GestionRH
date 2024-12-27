import React from 'react';
import avatar from '../assets/avatar.jpg';


const AvatarRappels = ({ user }) => {
  const defaultAvatar = avatar; // Assurez-vous d'avoir une image par défaut

  return (
    <img
      src={user?.avatar || defaultAvatar}
      alt="Avatar utilisateur"
      className="avatar"
      style={{
        width: '50px',
        height: '50px',
        borderRadius: '50%',
        border: '2px solid #ccc',
      }}
    />
  );
};

export default AvatarRappels;
