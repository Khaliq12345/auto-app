

  export default defineNuxtRouteMiddleware((to, from) => {
    const router = useRouter();

    if (import.meta.client) {
    sessionStorage.setItem('route', to.path)
      const accessToken = sessionStorage.getItem('AccessToken');
      if (!accessToken) {
        // return navigateTo('/login');
        return router.push('/login');
      }
    }
  });