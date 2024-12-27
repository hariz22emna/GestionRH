import React, { useEffect, useState } from 'react';
import Sidebar from '../SideBar/Sidebar';
import {
  Box,
  Typography,
  Paper,
  TextField,
  Button,
  CircularProgress,
  Snackbar,
  SnackbarContent,
  Card,
  CardContent,
  CardActions,
  IconButton,
} from '@mui/material';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPenToSquare, faTrash } from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Listrappels = () => {
  const [rappels, setRappels] = useState([]); // Initialisation comme tableau vide
  const [searchTerm, setSearchTerm] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [snackbarMessage, setSnackbarMessage] = useState('');
  const [openSnackbar, setOpenSnackbar] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchRappels = async () => {
      const token = localStorage.getItem('authToken');
      if (token) {
        try {
          const response = await axios.get('http://localhost:8000/api/getAllRappels', {
            headers: {
              Authorization: `Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9yJhdWQiOiI5ZDk1NGE0Yy1lM2NiLTQ3MGEtOTcxMi1jODY0YWE3Zjk4ZDkiLCJqdGkiOiIxNmIyZGY1NDhkOWIyNzNkYTZkNGQ5YmRjMWY5NTYyN2Y4MzQwZTI0MTJjNzgzZWJkZjVlYjA3ODVjYjJmMzkwNTUzODQyNGIxYmNiZTIxMiIsImlhdCI6MTczMjgyMzk4Ny42NTI2NjYsIm5iZiI6MTczMjgyMzk4Ny42NTI2NjgsImV4cCI6MTczMjkxMDM4Ni45MDAzNDQsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.Qmn93FjowS-EJIqCkbvoZQuusZXMpdET_1vYrHC1cGZHy_7Mg5c4RxLFiJ5Iu2jlXoMeb3eQLfJqmTYZXwQ8H0xrreEARHZWzTlsLvDFn3frfJzrer-7ebcdWPlQ0bRcRdIRTE4qb1XhIJNsB9cnRuWXmHyPMRTg5xOp0Me_v-iebyDXMbYED_D88uYshk_3LtFCASbgaKwcxpU0nR4byAgwNC_6auHidAdqxhuqlmzn3clpIryGROWpkXy0SuVaeCTrVbOZJXWW6w1vr7pMbXfLQy8jShgvL89DdpQPo5_S7KaRuOI83Izja7PGcWakEbtZtX_gy_Ghc2SkneE-aE-obDrlXH5JS_eQbkF82-Cf13uV98Edwuu6b5GNajwRIE30e-ID0xsCrnFWum1vxSaIDD0klalq9YmQihk9ScSPgP2bTv_BpmpEotCxoJerXQzMKAUh80RZhS8UHY9K6nc-p7aaw74_2ng04zB8rYbGaAL-oO_CBbVBNQV6yr_x6Y-1N1vyEhz1JhdQXSK675Z76zxDxiZNoJT10NNoCfqVj510seaT1guma6AnpaYfvS7hLyo-lgiacFmp2DmjkBy6MRaYAz8jECp-47Ajd3rFYaXaWBClGhRhCl71jbGAQj-Bs1ULB5rUt-WPPFwSlhcic-lI3OAOR_VJfhOWv_w`,
            },
          });

          console.log('Réponse de l\'API:', response.data.rappel);  // Log de la réponse de l'API

          // Assurez-vous que la structure des données est correcte
          if (Array.isArray(response.data.rappel)) {
            setRappels(response.data.rappel);  // Stockez les rappels dans le state
          } else {
            setError('Aucun rappel trouvé.');
          }

          setLoading(false);
        } catch (error) {
          setError('Erreur lors de la récupération des rappels: ' + error.message);
          setLoading(false);
        }
      } else {
        setError('Token manquant. Veuillez vous connecter.');
        setLoading(false);
      }
    };

    fetchRappels();
  }, []);

  // Log pour vérifier l'état des rappels
  console.log('Rappels dans l\'état:', rappels);

  // Vérification que rappels est bien un tableau avant d'appliquer filter
  const filteredRappels = Array.isArray(rappels)
    ? rappels.filter((rappel) =>
        rappel.titre && rappel.titre.toLowerCase().includes(searchTerm.toLowerCase())
      )
    : [];

  const handleEditRappel = (id) => {
    navigate(`/editrappel/${id}`);  // Redirection vers la page d'édition
  };

  const handleDeleteRappel = async (id) => {
    const token = localStorage.getItem('authToken');
    if (token) {
      try {
        await axios.delete(`http://localhost:8000/api/rappels/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setRappels(rappels.filter((rappel) => rappel.id !== id));
        setSnackbarMessage('Rappel supprimé avec succès!');
        setOpenSnackbar(true);
      } catch (error) {
        setSnackbarMessage('Erreur lors de la suppression du rappel.');
        setOpenSnackbar(true);
      }
    }
  };

  const handleAddRappel = () => {
    navigate('/addrappel');  // Redirection vers la page d'ajout
  };

  return (
    <Box display="flex" height="100vh" bgcolor="#f5f5f5">
      <Sidebar />
      <Box flex={1} padding={3}>
        {error && (
          <Paper sx={{ padding: 2, backgroundColor: '#f8d7da', color: '#721c24' }}>
            <Typography>{error}</Typography>
          </Paper>
        )}
        <Paper elevation={3} sx={{ padding: 3, marginBottom: 3 }}>
          <Box display="flex" justifyContent="space-between" alignItems="center">
            <Typography variant="h6">Gestion des rappels</Typography>
            <Box display="flex" gap={2}>
              <TextField
                label="Rechercher"
                variant="outlined"
                size="small"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                sx={{ minWidth: '250px' }}
              />
              <Button
                variant="contained"
                sx={{ backgroundColor: '#4CAF50', color: '#fff' }}
                onClick={handleAddRappel}
              >
                Ajouter
              </Button>
            </Box>
          </Box>
        </Paper>

        {loading ? (
          <Box display="flex" justifyContent="center" alignItems="center" height="300px">
            <CircularProgress color="success" />
          </Box>
        ) : (
          <Box display="grid" gridTemplateColumns="repeat(auto-fill, minmax(250px, 1fr))" gap={3}>
            {filteredRappels.length > 0 ? (
              filteredRappels.map((rappel, index) => (
                <Card key={index} sx={{ maxWidth: 345, marginBottom: 3 }}>
                  <CardContent>
                    <Typography variant="h6" component="div">
                      {rappel.titre}  {/* Affichez 'titre' */}
                    </Typography>
                    <Typography variant="body2" color="text.secondary">
                      {rappel.description}  {/* Affichez la description */}
                    </Typography>
                  </CardContent>
                  <CardActions>
                    <IconButton color="default" sx={{ fontSize: 18 }} onClick={() => handleEditRappel(rappel.id)}>
                      <FontAwesomeIcon icon={faPenToSquare} style={{ color: 'black' }} />
                    </IconButton>
                    <IconButton color="default" sx={{ fontSize: 18 }} onClick={() => handleDeleteRappel(rappel.id)}>
                      <FontAwesomeIcon icon={faTrash} style={{ color: 'black' }} />
                    </IconButton>
                  </CardActions>
                </Card>
              ))
            ) : (
              <Typography variant="body1" align="center" colSpan={3}>
                Aucun rappel trouvé.
              </Typography>
            )}
          </Box>
        )}
      </Box>

      {/* Snackbar pour afficher le message en haut au centre */}
      <Snackbar
        open={openSnackbar}
        autoHideDuration={6000}
        onClose={() => setOpenSnackbar(false)}
        anchorOrigin={{ vertical: 'top', horizontal: 'center' }}
      >
        <SnackbarContent
          sx={{
            backgroundColor: snackbarMessage.includes('Erreur') ? '#d32f2f' : '#388e3c',
            color: 'white',
            padding: '10px 20px',
            borderRadius: '8px',
            textAlign: 'center',
          }}
          message={snackbarMessage}
        />
      </Snackbar>
    </Box>
  );
};

export default Listrappels;
