<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    protected string $supabaseUrl;
    protected string $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    public function post(string $endpoint, array $data = [])
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->supabaseKey,
                'Content-Type' => 'application/json',
            ])->post($this->supabaseUrl . $endpoint, $data);

            return $response->json();
        } catch (\Exception $e) {
            // Log l'erreur ou gérez-la comme vous le souhaitez
            return null;
        }
    }
}