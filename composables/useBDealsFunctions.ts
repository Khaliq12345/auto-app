import axios from 'axios';

export function useBDealsFunctions() {
    // Globals
    const router = useRouter();
    const loadingCars = ref(false);
    const config = useRuntimeConfig();
    const urlAPI = config.public.urlAPI;
    // Cars
    const title = 'Deals Listing';
    const searchTerm = ref('');
    const selectedColors = ref<any[]>([]);
    const selectedModels = ref<any[]>([]);
    const currentPage = ref(1);
    const itemsPerPage = 10;
    const cars = ref<any[]>([])
    // Methods
    // 
    const handleLogout = async () => {
        // Supprimer les informations de session
        sessionStorage.removeItem('AccessToken');
        sessionStorage.removeItem('RefreshToken');
        sessionStorage.removeItem('ExpiresAt');
        router.push('/login');
    };
    const getAllCars = async (domainVar: string) => {
        console.log("In lauc ", domainVar);
        loadingCars.value = true;
        const accessToken = sessionStorage.getItem('AccessToken');
        const refToken = sessionStorage.getItem('RefreshToken');
        try {
            const response = await axios.get(urlAPI + "/get_best_deals",
                {
                    params: {
                        access_token: accessToken,
                        refresh_token: refToken,
                        domain : domainVar,
                    },
                    headers: {
                        "accept": "application/json",
                        "content-type": "application/x-www-form-urlencoded",
                    },
                },
            );

            // 
            console.log('Got Best Deals:', response.data);
            cars.value = response.data.details;
            // Stocker l'access token dans la session du navigateur
            sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
            sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
            sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

        } catch (err) {
            console.error('Erreur de requete:', err);
        } finally {
            // 
            loadingCars.value = false;
        }

    }
    // 
    const availableColors = computed(() => cars.value ? [...new Set(cars.value.map(car => car.color))].filter(Boolean).map(color => ({ label: color, value: color })) : []);
    const availableModels = computed(() => cars.value ? [...new Set(cars.value.map(car => car.make))].filter(Boolean).map(model => ({ label: model, value: model })) : []);
    var filteredCars = computed(() => {
        return cars.value.filter(car => {
            const searchMatch = searchTerm.value ?
                (car?.make as string)?.toLowerCase()?.includes(searchTerm.value.toLowerCase()) ||
                (car?.version as string)?.toLowerCase()?.includes(searchTerm.value.toLowerCase()) ||
                (car?.model as string)?.toLowerCase()?.includes(searchTerm.value.toLowerCase()) :
                true;

            const colorMatch = selectedColors.value.length ? selectedColors.value.includes(car.color) : true;
            const modelMatch = selectedModels.value.length ? selectedModels.value.includes(car.make) : true;

            return searchMatch && colorMatch && modelMatch;
        });
    });
    const pageCount = computed(() => Math.ceil(filteredCars.value.length / itemsPerPage));
    const paginatedCars = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return filteredCars.value.slice(start, end);
    });
  
    const to = (page: any) => {
        return {
            query: {
                page
            },
            hash: '#with-links'
        }
    }
    // 
   

    return {
        loadingCars,
        title,
        searchTerm,
        selectedColors,
        selectedModels,
        currentPage,
        itemsPerPage,
        cars,
        handleLogout,
        getAllCars,
        availableColors,
        availableModels,
        filteredCars,
        pageCount,
        paginatedCars,
        to,
    };
}