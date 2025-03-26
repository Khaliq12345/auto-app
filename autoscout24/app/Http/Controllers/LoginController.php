<?php

namespace App\Http\Controllers; // Adaptez le namespace si nécessaire

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseService;

class LoginController extends Controller
{
    protected $supabaseService;

    public function __construct(SupabaseService $supabaseService)
    {
        // $this->middleware('guest')->except('logout');
        $this->supabaseService = $supabaseService;
    }

   

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;


        $response = $this->supabaseService->post('/auth/v1/token?grant_type=password', [
            'email' => $email,
            'password' => $password,
        ]);

        // dd($response);

        if ($response && isset($response['access_token']) && isset($response['user'])) {
            // Connexion réussie avec Supabase
            $request->session()->put('supabase_user', $response['user']);
            $request->session()->put('supabase_access_token', $response['access_token']);
            // Optionnel : Gérer la session Laravel si nécessaire
            return redirect()->intended(route('dashboard'));
        } else {
            // Échec de la connexion avec Supabase
            return back()->withErrors(['email' => 'Identifiants incorrects.']);
        }
    }

    public function logout(Request $request)
    {
        // Déconnexion de Supabase (nécessite l'access token)
        $accessToken = $request->session()->get('supabase_access_token');
        if ($accessToken) {
            Http::withHeaders([
                'apikey' => $this->supabaseService->supabaseKey,
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->supabaseService->supabaseUrl . '/auth/v1/logout');
            // Vous pouvez ignorer la réponse si vous ne faites rien de spécifique après la déconnexion Supabase
        }

        // Déconnexion de la session Laravel
        $request->session()->forget('supabase_user');
        $request->session()->forget('supabase_access_token');
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}