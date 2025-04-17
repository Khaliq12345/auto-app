import { ref } from 'vue';
import { useRouter, useRuntimeConfig } from '#imports';
import { useNotifications } from '~/utils/globals';
import axios from 'axios';

export function useLoginForm() {
    const email = ref('')
    const password = ref('')
    const showPassword = ref(false)
    const isLoading = ref(false)
    const error = ref('')
    const router = useRouter()
    const config = useRuntimeConfig();
    const urlAPI = config.public.urlAPI;
const { showToast } = useNotifications();


        const handleLogin = async () => {
          console.log('is linging in')
    
      isLoading.value = true
      error.value = ''
      try {
        const response = await axios.post(urlAPI+"/login", 
          "",
          {
            params: {
              email: email.value,
              password: password.value
                },
    
            headers: {
              "accept": "application/json",
        "content-type": "application/x-www-form-urlencoded",
            },
          },
        );
    
        // La connexion a réussi, la réponse contient les informations de session
        // console.log('Connexion réussie:', response.data);
        showToast('Success',"Successfully Logged In !", 'i-heroicons-check-badge', 'success');
        // Stocker l'access token dans la session du navigateur
        sessionStorage.setItem('AccessToken', response.data.details.session.access_token);
        sessionStorage.setItem('RefreshToken', response.data.details.session.refresh_token);
        sessionStorage.setItem('ExpiresAt', response.data.details.session.expires_at);
        // sessionStorage.setItem('user', response.data.user);
        // 
        router.push('/listing') // 
      } catch (err : any) {
        console.error('Erreur lors de la connexion:', err);
        error.value = err.response?.data?.message || 'Invalid Credentials or server error !';
        showToast('Error !',"Failled to LogIn, check informations and try again", 'i-heroicons-exclamation-triangle', 'error');
      } finally {
        isLoading.value = false;
      }
    }

  return {
    email,
    password,
    showPassword,
    isLoading,
    error,
    handleLogin
  };
}