// src/Login.jsx
import React, { useState } from 'react';
import { TextField, Button, Typography, Grid } from '@mui/material';
import { motion } from 'framer-motion';
import { Link, useNavigate } from 'react-router-dom'; // Importation de 'useNavigate'
import axios from 'axios';  // Importation d'axios
import { useDispatch } from 'react-redux'; // Importation de 'useDispatch'
import { setUser } from '../store'; // Importez l'action pour mettre à jour l'utilisateur

import Illustration from '../assets/login.jpg'; // Assure-toi du bon chemin vers l'image

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const dispatch = useDispatch();  // Utilisation de 'useDispatch' pour dispatcher l'action
  const navigate = useNavigate();  // Utilisation de 'useNavigate'

  // Fonction pour gérer la soumission du formulaire
  const handleLogin = async (e) => {
    e.preventDefault();
    
    const loginData = {
      email,
      password,
    };

    try {
      const response = await axios.post('http://localhost:8000/api/login', loginData, {
        headers: {
          'Content-Type': 'application/json',
        },
      });

      // Si la connexion est réussie, vous pouvez rediriger l'utilisateur
      if (response.status === 200) {
        const { user, token, permissions } = response.data;

        // Sauvegarder le token d'authentification dans le localStorage
        localStorage.setItem('authToken', token);
        localStorage.setItem('user', JSON.stringify(user)); // Sauvegarde aussi l'utilisateur
        localStorage.setItem('permissions', JSON.stringify(permissions)); // Sauvegarde des permissions

        // Met à jour l'état de Redux avec l'utilisateur, le token et les permissions
        dispatch(setUser(user, token, permissions));

        // Redirection vers la page des utilisateurs ou une autre page protégée
        navigate('/users');
      }
    } catch (error) {
      // Gérer les erreurs
      if (error.response && error.response.status === 401) {
        setError('Identifiants invalides');
      } else {
        setError('Une erreur s\'est produite. Veuillez réessayer.');
      }
    }
  };

  return (
    <Grid container sx={{ height: '100vh' }} direction="row" alignItems="center" justifyContent="center" style={{ backgroundColor: '#f1f8f4' }}>
      {/* Illustration */}
      <Grid item xs={12} sm={6} container justifyContent="center">
        <motion.img
          src={Illustration} // L'image importée ici
          alt="Illustration"
          width="100%"
          style={{
            maxWidth: '600px', // Augmenter la largeur maximale
            borderRadius: '16px',
            objectFit: 'cover',
            height: 'auto', // Maintenir les proportions de l'image
          }}
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 1 }}
        />
      </Grid>

      {/* Formulaire d'authentification */}
      <Grid item xs={12} sm={6} container justifyContent="center">
        <Grid container sx={{ p: 4, borderRadius: 2, boxShadow: 3, backgroundColor: '#ffffff', width: '80%', maxWidth: '400px' }}>
          <Typography
            variant="h5"
            align="center"
            mb={3}
            sx={{ fontWeight: 'bold', color: '#4caf50' }} // Titre en vert
          >
            Login to your account
          </Typography>

          {/* Affichage de l'erreur */}
          {error && (
            <Typography color="error" variant="body2" align="center" mb={2}>
              {error}
            </Typography>
          )}

          {/* Champ Email */}
          <TextField
            fullWidth
            label="Email Address"
            margin="normal"
            variant="outlined"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            sx={{
              '& .MuiOutlinedInput-root': {
                '&.Mui-focused fieldset': {
                  borderColor: '#4caf50', // Change la couleur de la bordure à vert lors de la sélection
                },
              },
              '& .MuiInputLabel-root': {
                color: '#000000', // Change la couleur du label à vert
              },
            }}
          />

          {/* Champ Password */}
          <TextField
            fullWidth
            label="Password"
            type="password"
            margin="normal"
            variant="outlined"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            sx={{
              '& .MuiOutlinedInput-root': {
                '&.Mui-focused fieldset': {
                  borderColor: '#4caf50', // Change la couleur de la bordure à vert lors de la sélection
                },
              },
              '& .MuiInputLabel-root': {
                color: '#000000', // Change la couleur du label à vert
              },
            }}
          />

          <Button
            variant="contained"
            color="primary"
            fullWidth
            onClick={handleLogin}  // Appel de la fonction de login lors du clic
            sx={{
              mt: 2,
              backgroundColor: '#4caf50', // Bouton vert
              '&:hover': {
                backgroundColor: '#388e3c', // Vert foncé au survol
              },
            }}
          >
            Login
          </Button>

          <Grid container sx={{ textAlign: 'center', mt: 2 }}>
            <Typography variant="body2">
              By logging in, you agree to the{' '}
              <Link to="/terms" style={{ color: '#4caf50' }}>Terms of Service</Link> and{' '}
              <Link to="/privacy" style={{ color: '#4caf50' }}>Privacy Policy</Link>.
            </Typography>
          </Grid>

          {/* Centered "Register" Link */}
          <Grid container sx={{ justifyContent: 'center', mt: 3 }}>
            <Typography variant="body2">
              Don't have an account?{' '}
              <Link to="/register" style={{ color: '#4caf50' }}>Register</Link>
            </Typography>
          </Grid>
        </Grid>
      </Grid>
    </Grid>
  );
};

export default Login;
