import React, { useState, useRef } from "react";
import axios from "axios";
import { Box, Button, TextField, Grid, IconButton, Typography, Checkbox, FormControlLabel, Avatar, Snackbar, Alert } from "@mui/material";
import AddPhotoAlternateIcon from "@mui/icons-material/AddPhotoAlternate";
import { LocalizationProvider } from "@mui/x-date-pickers/LocalizationProvider";
import { DatePicker } from "@mui/x-date-pickers";
import { AdapterDateFns } from "@mui/x-date-pickers/AdapterDateFns";
import { useNavigate } from "react-router-dom"; // Pour la redirection

const AddUser = () => {
  const [formData, setFormData] = useState({
    immat: "",
    name: "",
    email: "",
    password: "",
    password_cofirm: "",
    phoneNumber: "",
    dateOfBirth: new Date(),
    image: null,
    generatePassword: false,
  });

  const [step, setStep] = useState(1);
  const [errorMessage, setErrorMessage] = useState(null);
  const [openSnackbar, setOpenSnackbar] = useState(false); // Contrôle l'affichage du Snackbar
  const fileInputRef = useRef(null);
  const navigate = useNavigate(); // Pour la redirection

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevState) => ({ ...prevState, [name]: value }));
  };

  const handleDateChange = (date) => {
    setFormData((prevState) => ({ ...prevState, dateOfBirth: date }));
  };

  const handleFileInput = (event) => {
    const file = event.target.files[0];
    if (file) {
      setFormData((prevState) => ({ ...prevState, image: file }));
    }
  };

  const triggerFileInput = () => {
    fileInputRef.current?.click();
  };

  const handleGeneratePassword = () => {
    const randomPassword = Math.random().toString(36).slice(-8);
    setFormData((prevState) => ({
      ...prevState,
      password: randomPassword,
      password_cofirm: randomPassword,
      generatePassword: true,
    }));
    setErrorMessage("Un mot de passe a été généré !");
  };

  const handleNextStep = () => {
    setErrorMessage(null);
    setStep(2);
  };

  const handlePrevStep = () => {
    setStep(1);
  };

  const handleSubmit = async () => {
    setErrorMessage(null);

    // Vérification des champs obligatoires
    if (!formData.immat || !formData.name || !formData.email || !formData.phoneNumber) {
      setErrorMessage("Veuillez remplir tous les champs obligatoires.");
      return;
    }

    if (!formData.password || !formData.password_cofirm) {
      setErrorMessage("Veuillez remplir tous les champs obligatoires.");
      return;
    }

    // Vérification de la correspondance des mots de passe
    if (formData.password !== formData.password_cofirm) {
      setErrorMessage("Les mots de passe ne correspondent pas.");
      return;
    }

    // Vérification de la longueur du mot de passe
    if (formData.password.length < 8) {
      setErrorMessage("Le mot de passe doit contenir au moins 8 caractères.");
      return;
    }

    try {
      const formDataToSend = new FormData();
      Object.entries(formData).forEach(([key, value]) => {
        if (key === "dateOfBirth") {
          formDataToSend.append(key, value.toISOString());
        } else {
          formDataToSend.append(key, value);
        }
      });

      const response = await axios.post("http://127.0.0.1:8000/api/createUser", formDataToSend, {
        headers: {
          "Content-Type": "multipart/form-data",
          Authorization: `Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9yJhdWQiOiI5ZDk1NGE0Yy1lM2NiLTQ3MGEtOTcxMi1jODY0YWE3Zjk4ZDkiLCJqdGkiOiIxNmIyZGY1NDhkOWIyNzNkYTZkNGQ5YmRjMWY5NTYyN2Y4MzQwZTI0MTJjNzgzZWJkZjVlYjA3ODVjYjJmMzkwNTUzODQyNGIxYmNiZTIxMiIsImlhdCI6MTczMjgyMzk4Ny42NTI2NjYsIm5iZiI6MTczMjgyMzk4Ny42NTI2NjgsImV4cCI6MTczMjkxMDM4Ni45MDAzNDQsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.Qmn93FjowS-EJIqCkbvoZQuusZXMpdET_1vYrHC1cGZHy_7Mg5c4RxLFiJ5Iu2jlXoMeb3eQLfJqmTYZXwQ8H0xrreEARHZWzTlsLvDFn3frfJzrer-7ebcdWPlQ0bRcRdIRTE4qb1XhIJNsB9cnRuWXmHyPMRTg5xOp0Me_v-iebyDXMbYED_D88uYshk_3LtFCASbgaKwcxpU0nR4byAgwNC_6auHidAdqxhuqlmzn3clpIryGROWpkXy0SuVaeCTrVbOZJXWW6w1vr7pMbXfLQy8jShgvL89DdpQPo5_S7KaRuOI83Izja7PGcWakEbtZtX_gy_Ghc2SkneE-aE-obDrlXH5JS_eQbkF82-Cf13uV98Edwuu6b5GNajwRIE30e-ID0xsCrnFWum1vxSaIDD0klalq9YmQihk9ScSPgP2bTv_BpmpEotCxoJerXQzMKAUh80RZhS8UHY9K6nc-p7aaw74_2ng04zB8rYbGaAL-oO_CBbVBNQV6yr_x6Y-1N1vyEhz1JhdQXSK675Z76zxDxiZNoJT10NNoCfqVj510seaT1guma6AnpaYfvS7hLyo-lgiacFmp2DmjkBy6MRaYAz8jECp-47Ajd3rFYaXaWBClGhRhCl71jbGAQj-Bs1ULB5rUt-WPPFwSlhcic-lI3OAOR_VJfhOWv_w`, // Remplacez par votre token
        },
      });

      console.log("Utilisateur ajouté:", response.data);
      setOpenSnackbar(true); // Affiche le Snackbar en cas de succès
      setTimeout(() => {
        navigate("/users"); // Redirige vers la page des utilisateurs après 3 secondes
      }, 3000);
    } catch (error) {
      console.error("Erreur lors de l'ajout de l'utilisateur:", error);
      if (error.response) {
        setErrorMessage(error.response?.data?.message || "Une erreur s'est produite. Veuillez réessayer.");
      } else {
        setErrorMessage("Une erreur s'est produite. Veuillez vérifier votre connexion.");
      }
    }
  };

  return (
    <Box display="flex" height="100vh" bgcolor="#f5f5f5" padding={3}>
      <Box
        minWidth="200px"
        bgcolor="#fff"
        padding={2}
        marginRight={2}
        borderRadius={2}
        boxShadow={2}
      >
        <Typography
          variant="subtitle1"
          gutterBottom
          style={{ fontWeight: step === 1 ? "bold" : "normal" }}
        >
          Informations personnelles
        </Typography>
        <Typography
          variant="subtitle1"
          gutterBottom
          style={{ fontWeight: step === 2 ? "bold" : "normal" }}
        >
          Authentification
        </Typography>
      </Box>

      <Box flex={1} bgcolor="#fff" padding={3} borderRadius={2} boxShadow={2}>
        <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
          <Typography variant="h6" fontWeight="bold">
            {step === 1 ? "Ajouter un utilisateur" : "Authentification"}
          </Typography>
        </Box>

        {step === 1 && (
          <Grid container spacing={2}>
            <Grid item xs={12}>
              <TextField
                label="Identifiant *"
                variant="outlined"
                fullWidth
                name="immat"
                value={formData.immat}
                onChange={handleChange}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                label="Nom *"
                variant="outlined"
                fullWidth
                name="name"
                value={formData.name}
                onChange={handleChange}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                label="Adresse mail *"
                variant="outlined"
                fullWidth
                name="email"
                value={formData.email}
                onChange={handleChange}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                label="Téléphone *"
                variant="outlined"
                fullWidth
                name="phoneNumber"
                value={formData.phoneNumber}
                onChange={handleChange}
              />
            </Grid>
            <Grid item xs={12}>
              <LocalizationProvider dateAdapter={AdapterDateFns}>
                <DatePicker
                  label="Date de naissance"
                  value={formData.dateOfBirth}
                  onChange={handleDateChange}
                  renderInput={(params) => <TextField {...params} fullWidth />}
                />
              </LocalizationProvider>
            </Grid>

            <Grid item xs={12}>
              <Typography variant="body2" color="textSecondary" gutterBottom>
                Image de profil
              </Typography>
              <Box display="flex" alignItems="center">
                {formData.image ? (
                  <Avatar
                    src={URL.createObjectURL(formData.image)}
                    alt="Image de profil"
                    sx={{ width: 56, height: 56 }}
                  />
                ) : (
                  <Avatar sx={{ width: 56, height: 56 }} />
                )}
                <IconButton onClick={triggerFileInput} color="primary">
                  <AddPhotoAlternateIcon />
                </IconButton>
                <input
                  type="file"
                  ref={fileInputRef}
                  style={{ display: "none" }}
                  onChange={handleFileInput}
                  accept="image/*"
                />
              </Box>
            </Grid>
          </Grid>
        )}

        {step === 2 && (
          <Grid container spacing={2}>
            <Grid item xs={12}>
              <TextField
                label="Mot de passe *"
                variant="outlined"
                fullWidth
                type="password"
                name="password"
                value={formData.password}
                onChange={handleChange}
              />
            </Grid>
            <Grid item xs={12}>
              <TextField
                label="Confirmer le mot de passe *"
                variant="outlined"
                fullWidth
                type="password"
                name="password_cofirm"
                value={formData.password_cofirm}
                onChange={handleChange}
              />
            </Grid>

            <Grid item xs={12}>
              <FormControlLabel
                control={
                  <Checkbox
                    checked={formData.generatePassword}
                    onChange={handleGeneratePassword}
                  />
                }
                label="Générer un mot de passe"
              />
            </Grid>
          </Grid>
        )}

        <Box display="flex" justifyContent="space-between" mt={3}>
          {step === 2 && (
            <Button
              variant="outlined"
              color="secondary"
              onClick={handlePrevStep}
              sx={{ borderColor: "#4CAF50", color: "#4CAF50" }}
            >
              Précédent
            </Button>
          )}
          <Button
            variant="contained"
            color="primary"
            onClick={step === 1 ? handleNextStep : handleSubmit}
            sx={{ backgroundColor: "#4CAF50", color: "#fff" }}
          >
            {step === 1 ? "Suivant" : "Ajouter"}
          </Button>
        </Box>

        {errorMessage && (
          <Typography variant="body2" color="error" mt={2}>
            {errorMessage}
          </Typography>
        )}
      </Box>

      {/* Snackbar pour afficher le message de succès */}
      <Snackbar
        open={openSnackbar}
        autoHideDuration={6000}
        onClose={() => setOpenSnackbar(false)}
      >
        <Alert onClose={() => setOpenSnackbar(false)} severity="success" sx={{ width: "100%" }}>
          Utilisateur ajouté avec succès !
        </Alert>
      </Snackbar>
    </Box>
  );
};

export default AddUser;
