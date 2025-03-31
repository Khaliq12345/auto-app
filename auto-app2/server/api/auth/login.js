// server/api/auth/login.js
import { defineEventHandler } from 'h3';
// import { useFetch } from '#app'; // Peut être utilisé côté serveur dans Nuxt 3

// Remplacez par les informations de votre projet Supabase (idéalement via des variables d'environnement)
// const supabaseUrl = process.env.SUPABASE_URL;
// const supabaseAnonKey = process.env.SUPABASE_ANON_KEY;

const supabaseUrl = 'https://nitlrmzkefgmjtyrjicc.supabase.co';
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5pdGxybXprZWZnbWp0eXJqaWNjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDIwMjA5MzQsImV4cCI6MjA1NzU5NjkzNH0.2IBAqF0ZpLbR-v_AXTxCOw5FpOvqdmQyNG8iMolP-rk';


export default defineEventHandler(async (event) => {
  const body = await readBody(event);

  const supabaseAuthUrl = `${supabaseUrl}/auth/v1/token?grant_type=password`;

  try {
    // const { data, error: fetchError } = await useFetch(supabaseAuthUrl, {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'apikey': supabaseAnonKey,
    //   },
    //   body: body
    // }).json();

    const { data, error: fetchError } = await useFetch('http://localhost/api_outilsco/public/api/matieres/read1', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'apikey': 'e7062c5b-d95d-4fa5-af31-52cb6e662816',
      },
      body: {
        "sigleetbs": "CPEGSM",
        "annee": "2024-2025"
      }
    });

    if (fetchError.value) {
      console.error('Erreur de Supabase:', fetchError.value);
      return { status: 401, body: { error: fetchError.value.message || 'Erreur d\'authentification Supabase.' } };
    }

    return { data };
  } catch (error) {
    console.error('Erreur lors de la requête vers Supabase:', error);
    return {
         status: 500,
          body: { error: " Erreur interne du serveur lors de la connexion à Supabase." } 
        };
  }


});