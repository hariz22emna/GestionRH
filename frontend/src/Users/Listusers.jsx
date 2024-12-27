import React, { useEffect, useState } from 'react';
import Sidebar from '../SideBar/Sidebar';
import {
  Box,
  Typography,
  Paper,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  IconButton,
  Avatar,
  Button,
  TextField,
  CircularProgress,
  Pagination,
  Snackbar,
  SnackbarContent
} from '@mui/material';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPenToSquare, faTrash } from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Listusers = () => {
  const [users, setUsers] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [rowsPerPage, setRowsPerPage] = useState(5);
  const [snackbarMessage, setSnackbarMessage] = useState('');
  const [openSnackbar, setOpenSnackbar] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUsers = async () => {
      const token = localStorage.getItem('authToken');
      if (token) {
        try {
          const response = await axios.get('http://localhost:8000/api/getAllUsers', {
            headers: {
              Authorization: `Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9yJhdWQiOiI5ZDk1NGE0Yy1lM2NiLTQ3MGEtOTcxMi1jODY0YWE3Zjk4ZDkiLCJqdGkiOiIxNmIyZGY1NDhkOWIyNzNkYTZkNGQ5YmRjMWY5NTYyN2Y4MzQwZTI0MTJjNzgzZWJkZjVlYjA3ODVjYjJmMzkwNTUzODQyNGIxYmNiZTIxMiIsImlhdCI6MTczMjgyMzk4Ny42NTI2NjYsIm5iZiI6MTczMjgyMzk4Ny42NTI2NjgsImV4cCI6MTczMjkxMDM4Ni45MDAzNDQsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.Qmn93FjowS-EJIqCkbvoZQuusZXMpdET_1vYrHC1cGZHy_7Mg5c4RxLFiJ5Iu2jlXoMeb3eQLfJqmTYZXwQ8H0xrreEARHZWzTlsLvDFn3frfJzrer-7ebcdWPlQ0bRcRdIRTE4qb1XhIJNsB9cnRuWXmHyPMRTg5xOp0Me_v-iebyDXMbYED_D88uYshk_3LtFCASbgaKwcxpU0nR4byAgwNC_6auHidAdqxhuqlmzn3clpIryGROWpkXy0SuVaeCTrVbOZJXWW6w1vr7pMbXfLQy8jShgvL89DdpQPo5_S7KaRuOI83Izja7PGcWakEbtZtX_gy_Ghc2SkneE-aE-obDrlXH5JS_eQbkF82-Cf13uV98Edwuu6b5GNajwRIE30e-ID0xsCrnFWum1vxSaIDD0klalq9YmQihk9ScSPgP2bTv_BpmpEotCxoJerXQzMKAUh80RZhS8UHY9K6nc-p7aaw74_2ng04zB8rYbGaAL-oO_CBbVBNQV6yr_x6Y-1N1vyEhz1JhdQXSK675Z76zxDxiZNoJT10NNoCfqVj510seaT1guma6AnpaYfvS7hLyo-lgiacFmp2DmjkBy6MRaYAz8jECp-47Ajd3rFYaXaWBClGhRhCl71jbGAQj-Bs1ULB5rUt-WPPFwSlhcic-lI3OAOR_VJfhOWv_w`,
            },
          });
          setUsers(response.data);
          setLoading(false);
        } catch (error) {
          setError('Erreur lors de la récupération des utilisateurs: ' + error.message);
          setLoading(false);
        }
      } else {
        setError('Token manquant. Veuillez vous connecter.');
        setLoading(false);
      }
    };

    fetchUsers();
  }, []);

  const filteredUsers = users.filter((user) =>
    user.name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleChangePage = (event, value) => {
    setPage(value);
  };

  const handleChangeRowsPerPage = (event) => {
    setRowsPerPage(parseInt(event.target.value, 10));
    setPage(1);
  };

  const handleAddUser = () => {
    navigate('/addusers');
  };

  const handleEditUser = (id) => {
    navigate(`/editusers/${id}`);  // Redirection vers la page d'édition
  };

  const handleDeleteUser = async (id) => {
    const token = localStorage.getItem('authToken');
    if (token) {
      try {
        await axios.delete(`http://localhost:8000/api/users/${id}`, {
          headers: {
            Authorization: `Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9yJhdWQiOiI5ZDk1NGE0Yy1lM2NiLTQ3MGEtOTcxMi1jODY0YWE3Zjk4ZDkiLCJqdGkiOiIxNmIyZGY1NDhkOWIyNzNkYTZkNGQ5YmRjMWY5NTYyN2Y4MzQwZTI0MTJjNzgzZWJkZjVlYjA3ODVjYjJmMzkwNTUzODQyNGIxYmNiZTIxMiIsImlhdCI6MTczMjgyMzk4Ny42NTI2NjYsIm5iZiI6MTczMjgyMzk4Ny42NTI2NjgsImV4cCI6MTczMjkxMDM4Ni45MDAzNDQsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.Qmn93FjowS-EJIqCkbvoZQuusZXMpdET_1vYrHC1cGZHy_7Mg5c4RxLFiJ5Iu2jlXoMeb3eQLfJqmTYZXwQ8H0xrreEARHZWzTlsLvDFn3frfJzrer-7ebcdWPlQ0bRcRdIRTE4qb1XhIJNsB9cnRuWXmHyPMRTg5xOp0Me_v-iebyDXMbYED_D88uYshk_3LtFCASbgaKwcxpU0nR4byAgwNC_6auHidAdqxhuqlmzn3clpIryGROWpkXy0SuVaeCTrVbOZJXWW6w1vr7pMbXfLQy8jShgvL89DdpQPo5_S7KaRuOI83Izja7PGcWakEbtZtX_gy_Ghc2SkneE-aE-obDrlXH5JS_eQbkF82-Cf13uV98Edwuu6b5GNajwRIE30e-ID0xsCrnFWum1vxSaIDD0klalq9YmQihk9ScSPgP2bTv_BpmpEotCxoJerXQzMKAUh80RZhS8UHY9K6nc-p7aaw74_2ng04zB8rYbGaAL-oO_CBbVBNQV6yr_x6Y-1N1vyEhz1JhdQXSK675Z76zxDxiZNoJT10NNoCfqVj510seaT1guma6AnpaYfvS7hLyo-lgiacFmp2DmjkBy6MRaYAz8jECp-47Ajd3rFYaXaWBClGhRhCl71jbGAQj-Bs1ULB5rUt-WPPFwSlhcic-lI3OAOR_VJfhOWv_w`,
          },
        });
        setUsers(users.filter((user) => user.id !== id));
        setSnackbarMessage('Utilisateur supprimé avec succès!');
        setOpenSnackbar(true);
      } catch (error) {
        setSnackbarMessage('Erreur lors de la suppression de l\'utilisateur.');
        setOpenSnackbar(true);
      }
    }
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
            <Typography variant="h6">Gestion des utilisateurs</Typography>
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
                onClick={handleAddUser}
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
          <Box>
            <TableContainer component={Paper}>
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell sx={{ fontWeight: 'bold' }}>Utilisateur</TableCell>
                    <TableCell sx={{ fontWeight: 'bold' }}>Identifiant</TableCell>
                    <TableCell sx={{ fontWeight: 'bold' }}>Email</TableCell>
                    <TableCell sx={{ fontWeight: 'bold' }}>État</TableCell>
                    <TableCell sx={{ fontWeight: 'bold', textAlign: 'center' }}>Actions</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {filteredUsers.length > 0 ? (
                    filteredUsers
                      .slice((page - 1) * rowsPerPage, page * rowsPerPage)
                      .map((user, index) => (
                        <TableRow key={index}>
                          <TableCell>
                            <Box display="flex" alignItems="center">
                              <Avatar sx={{ bgcolor: '#79C300', mr: 2 }}>
                                {user.name.charAt(0)}
                              </Avatar>
                              {user.name}
                            </Box>
                          </TableCell>
                          <TableCell>{user.immat}</TableCell>
                          <TableCell>{user.email}</TableCell>
                          <TableCell>
                            <Typography
                              variant="body2"
                              sx={{
                                padding: '4px 8px',
                                backgroundColor: user.status === 1 ? '#e0f7e9' : '#fbeaea',
                                color: user.status === 1 ? '#388e3c' : '#d32f2f',
                                borderRadius: '12px',
                                display: 'inline-block',
                              }}
                            >
                              {user.status === 1 ? 'Autorisé' : 'Non autorisé'}
                            </Typography>
                          </TableCell>
                          <TableCell align="center">
                            <Box display="inline-flex" gap={0}>
                              <IconButton color="default" sx={{ fontSize: 18, padding: 0 }} onClick={() => handleEditUser(user.id)}>
                                <FontAwesomeIcon icon={faPenToSquare} style={{ color: 'black' }} />
                              </IconButton>
                              <IconButton color="default" sx={{ fontSize: 18, padding: 0 }} onClick={() => handleDeleteUser(user.id)}>
                                <FontAwesomeIcon icon={faTrash} style={{ color: 'black' }} />
                              </IconButton>
                            </Box>
                          </TableCell>
                        </TableRow>
                      ))
                  ) : (
                    <TableRow>
                      <TableCell colSpan={6} align="center">
                        Aucun utilisateur trouvé.
                      </TableCell>
                    </TableRow>
                  )}
                </TableBody>
              </Table>
            </TableContainer>

            <Box display="flex" justifyContent="flex-end" mt={2}>
              <Pagination
                count={Math.ceil(filteredUsers.length / rowsPerPage)}
                page={page}
                onChange={handleChangePage}
                color="primary"
                shape="rounded"
                size="small"
              />
            </Box>
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

export default Listusers;
