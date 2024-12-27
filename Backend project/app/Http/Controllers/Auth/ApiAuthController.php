<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as OClient;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    
    public function register(Request $request)
    {
        // Étape 1 : Validation des données reçues
        $validator = Validator::make($request->all(), [
            'immat' => 'required|string|max:255', // Vérifie que l'immatriculation est une chaîne avec une longueur maximale
            'name' => 'required|string|max:255', // Le nom de l'utilisateur est obligatoire
            'email' => 'required|email|unique:users,email', // L'email doit être valide et unique
            'password' => 'required|min:8', // Le mot de passe doit avoir au moins 8 caractères
            'c_password' => 'required|same:password', // Vérifie que les mots de passe correspondent
        ]);
    
        // Si la validation échoue, retourner une réponse avec les erreurs
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données.',
                'errors' => $validator->errors(),
            ], 400); // HTTP 400 : Bad Request
        }
    
        // Étape 2 : Préparer les données pour la création de l'utilisateur
        $input = $request->all();
        $input['password'] = bcrypt($input['password']); // Hashage sécurisé du mot de passe
    
        try {
            // Étape 3 : Créer l'utilisateur dans la base de données
            $user = User::create($input);
    
            // Étape 4 : Construire une réponse de succès
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur enregistré avec succès.',
                'data' => [
                    'user' => $user,
                ],
            ], 201); // HTTP 201 : Created
        } catch (\Exception $e) {
            // Étape 5 : Gestion des erreurs internes
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement.',
                'error' => $e->getMessage(), // À désactiver ou masquer en production
            ], 500); // HTTP 500 : Internal Server Error
        }
    }
    
    public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json('Email ou mot de passe invalide !', 400);
    }
    if (!password_verify($request->password, $user->password)) {
        return response()->json('Mot de passe invalide', 400);
    }

    $success['user'] = $user;

    // Vérifier si un client OAuth existe
    $oClient = OClient::where('password_client', 1)->first();
    if (!$oClient) {
        return response()->json(['error' => 'OAuth client not found'], 500);
    }

    // Récupérer les rôles et permissions de l'utilisateur
    $roles = $user->roles;
    $scopes = [];
    foreach ($roles as $role) {
        foreach ($role->permissions as $permission) {
            $scopes[] = $permission->name;
        }
    }
    $scopes = array_unique($scopes);

    // Créer une requête pour obtenir un token OAuth
    $tokenRequest = Request::create('/oauth/token', 'post', [
        'username' => $request['email'],
        'password' => $request['password'],
        'grant_type' => 'password',
        'scope' => $scopes,
        'client_id' => $oClient->id,
        'client_secret' => $oClient->secret,
    ]);
    $res = app()->handle($tokenRequest);
    $responseBody = json_decode($res->getContent(), true);

    $success['expires_in'] = $responseBody['expires_in'];
    $success['token'] = $responseBody['access_token'];
    $success['refresh_token'] = $responseBody['refresh_token'];
    $success['permissions'] = $scopes;
    $success['success'] = "User login successfully.";

    return response()->json($success);
}
    public function refresh(Request $request)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $tokenRequest = Request::create('/oauth/token', 'post', [
            'grant_type' => 'refresh_token',
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'refresh_token' => $request->refresh_token,
            'scope' => '',
        ]);
        return app()->handle($tokenRequest);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response);
    }
}
