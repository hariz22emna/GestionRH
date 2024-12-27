import { createStore } from 'redux';

// Récupérer les données de localStorage
const user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null;
const token = localStorage.getItem('authToken');
const permissions = localStorage.getItem('permissions') ? JSON.parse(localStorage.getItem('permissions')) : [];

// État initial avec les données du localStorage
const initialState = {
  user,
  token,
  permissions,
};

// Reducer
const rootReducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_USER':
      // Sauvegarder dans localStorage
      localStorage.setItem('user', JSON.stringify(action.payload.user));
      localStorage.setItem('authToken', action.payload.token);
      localStorage.setItem('permissions', JSON.stringify(action.payload.permissions));

      return {
        ...state,
        user: action.payload.user,
        token: action.payload.token,
        permissions: action.payload.permissions,
      };

    case 'CLEAR_USER':
      // Nettoyer le localStorage lors de la déconnexion
      localStorage.removeItem('user');
      localStorage.removeItem('authToken');
      localStorage.removeItem('permissions');

      return {
        ...state,
        user: null,
        token: null,
        permissions: [],
      };

    default:
      return state;
  }
};

// Création du store avec le reducer
const store = createStore(rootReducer);

export const setUser = (user, token, permissions) => {
  return {
    type: 'SET_USER',
    payload: { user, token, permissions },
  };
};

export const clearUser = () => {
  return {
    type: 'CLEAR_USER',
  };
};

export default store;
