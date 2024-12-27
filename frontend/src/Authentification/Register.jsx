import React, { useState } from 'react';
import { TextField, Button, Typography, Box } from '@mui/material';
import { motion } from 'framer-motion';
import { Link } from 'react-router-dom';
import Illustration from '../assets/register.jpg';
import axios from 'axios';

const Register = () => {
  const [formData, setFormData] = useState({
    fullName: '',
    email: '',
    password: '',
    confirmPassword: '',
  });

  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    // Validation côté frontend
    if (formData.password !== formData.confirmPassword) {
      setError('Passwords do not match');
      return;
    }

    try {
      // Appel à l'API pour l'enregistrement
      const response = await axios.post('http://localhost:8000/api/register', {
        name: formData.fullName,
        email: formData.email,
        password: formData.password,
        password_confirmation: formData.confirmPassword,
      });

      if (response.status === 201) {
        setSuccess('Account created successfully!');
        setFormData({ fullName: '', email: '', password: '', confirmPassword: '' });
      }
    } catch (err) {
      const apiError =
        err.response?.data?.message || // Message simple de l'API
        (err.response?.data && Object.values(err.response.data).join(', ')) || 'Something went wrong!'; // Message par défaut

      setError(apiError);
    }
  };

  return (
    <Box
      sx={{
        height: '100vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#f1f8f4',
      }}
    >
      {/* Illustration Section */}
      <Box sx={{ flex: 1, display: 'flex', justifyContent: 'center' }}>
        <motion.img
          src={Illustration}
          alt="Illustration"
          width="100%"
          style={{
            maxWidth: '600px',
            borderRadius: '16px',
            objectFit: 'cover',
            height: 'auto',
          }}
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 1 }}
        />
      </Box>

      {/* Form Section */}
      <Box sx={{ flex: 1, display: 'flex', justifyContent: 'center' }}>
        <Box
          component="form"
          onSubmit={handleSubmit}
          sx={{
            p: 4,
            borderRadius: 2,
            boxShadow: 3,
            backgroundColor: '#ffffff',
            width: '80%',
            maxWidth: '400px',
          }}
        >
          <Typography variant="h5" align="center" mb={3} sx={{ fontWeight: 'bold', color: '#4caf50' }}>
            Create Account
          </Typography>

          {/* Display Errors or Success Messages */}
          {error && <Typography color="error" align="center" mb={2}>{error}</Typography>}
          {success && <Typography color="primary" align="center" mb={2}>{success}</Typography>}

          {/* Input Fields */}
          <TextField
            fullWidth
            label="Full Name"
            name="fullName"
            value={formData.fullName}
            onChange={handleChange}
            margin="normal"
            variant="outlined"
            sx={{
              '& .MuiOutlinedInput-root': {
                '&.Mui-focused fieldset': { borderColor: '#4caf50' },
              },
              '& .MuiInputLabel-root': { color: '#000000' },
            }}
          />
          <TextField
            fullWidth
            label="Email Address"
            name="email"
            value={formData.email}
            onChange={handleChange}
            margin="normal"
            variant="outlined"
            sx={{
              '& .MuiOutlinedInput-root': {
                '&.Mui-focused fieldset': { borderColor: '#4caf50' },
              },
              '& .MuiInputLabel-root': { color: '#000000' },
            }}
          />
          <TextField
            fullWidth
            label="Password"
            type="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            margin="normal"
            variant="outlined"
            sx={{
              '& .MuiOutlinedInput-root': {
                '&.Mui-focused fieldset': { borderColor: '#4caf50' },
              },
              '& .MuiInputLabel-root': { color: '#000000' },
            }}
          />
          <TextField
            fullWidth
            label="Confirm Password"
            type="password"
            name="confirmPassword"
            value={formData.confirmPassword}
            onChange={handleChange}
            margin="normal"
            variant="outlined"
            sx={{
              '& .MuiOutlinedInput-root': {
                '&.Mui-focused fieldset': { borderColor: '#4caf50' },
              },
              '& .MuiInputLabel-root': { color: '#000000' },
            }}
          />

          {/* Register Button */}
          <Button
            type="submit"
            variant="contained"
            color="primary"
            fullWidth
            sx={{
              mt: 2,
              backgroundColor: '#4caf50',
              '&:hover': { backgroundColor: '#388e3c' },
            }}
          >
            Register
          </Button>

          {/* Terms and Privacy Links */}
          <Box sx={{ textAlign: 'center', mt: 2 }}>
            <Typography variant="body2">
              By creating an account, you agree to the{' '}
              <Link to="/terms" style={{ color: '#4caf50' }}>
                Terms of Service
              </Link>{' '}
              and{' '}
              <Link to="/privacy" style={{ color: '#4caf50' }}>
                Privacy Policy
              </Link>.
            </Typography>
          </Box>

          {/* Login Link */}
          <Box sx={{ textAlign: 'center', mt: 3 }}>
            <Typography variant="body2">
              Already have an account?{' '}
              <Link to="/login" style={{ color: '#4caf50' }}>
                Log in
              </Link>
            </Typography>
          </Box>
        </Box>
      </Box>
    </Box>
  );
};

export default Register;
