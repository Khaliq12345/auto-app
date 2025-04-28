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
    const cutOffPrice = ref(500);
    const mPercent = ref(95);
    const selectedColors = ref<any[]>([]);
    const selectedModels = ref<any[]>([]);
    const selectedDeals = ref<any[]>([]);
    const isModalOpen = ref(false);
    const selectedCar = ref(null);
    const relatedCars = ref<any[]>([]);
    const resLaCentrale = ref();
    const resAutoScout = ref();
    const currentPage = ref(1);
    const itemsPerPage = 10;
    const cars = ref<any[]>([])
    const route = useRoute()
    const domain = computed(() => route.query.domain)
    // 
    // watchEffect(() => {
    //     if (domain.value) {
    //         console.log("Domain reçu :", domain.value)
    //         getAllCars(domain.value);
    //     }
    // })
    onMounted(() => {
        console.log("Domain reçu :", domain.value)
        getAllCars(domain.value);
    });
    // Methods
    // 
    const reloadPage = () => {
        getAllCars(domain.value)
        // window.location.reload()
        // navigateTo(route.fullPath, { replace: true })
    }
    const handleLogout = async () => {
        // Supprimer les informations de session
        sessionStorage.removeItem('AccessToken');
        sessionStorage.removeItem('RefreshToken');
        sessionStorage.removeItem('ExpiresAt');
        router.push('/login');
    };
    const getAllCars = async (domainVar: any) => {
        loadingCars.value = true;
        const accessToken = sessionStorage.getItem('AccessToken');
        const refToken = sessionStorage.getItem('RefreshToken');
        try {
            const response = await axios.get(urlAPI + "/get_all_cars",
                {
                    params: {
                        access_token: accessToken,
                        refresh_token: refToken,
                        // page : 4,
                        cut_off_price: cutOffPrice.value,
                        percentage_limit: mPercent.value,
                        domain: domainVar,
                        limit: 250
                    },
                    headers: {
                        "accept": "application/json",
                        "content-type": "application/x-www-form-urlencoded",
                    },
                },
            );

            // 
            console.log('Get Cars:', response.data);
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
    const availableDeals = computed(() => cars.value ? [...new Set(cars.value.map(car => car.card_color))].filter(Boolean).map(card_color => ({ label: card_color == 'red' ? 'Worst Deals' : card_color == 'green' ? 'Best Deals' : 'Not Bads', value: card_color })) : []);
    var filteredCars = computed(() => {
        return cars.value.filter(car => {
            const searchMatch = searchTerm.value ?
                (car?.make as string)?.toLowerCase()?.includes(searchTerm.value.toLowerCase()) ||
                (car?.version as string)?.toLowerCase()?.includes(searchTerm.value.toLowerCase()) ||
                (car?.model as string)?.toLowerCase()?.includes(searchTerm.value.toLowerCase()) :
                true;

            const colorMatch = selectedColors.value.length ? selectedColors.value.includes(car.color) : true;
            const modelMatch = selectedModels.value.length ? selectedModels.value.includes(car.make) : true;
            const dealMatch = selectedDeals.value.length ? selectedDeals.value.includes(car.card_color) : true;

            currentPage.value = 1

            return searchMatch && colorMatch && modelMatch && dealMatch;
        });
    });
    const pageCount = computed(() => Math.ceil(filteredCars.value.length / itemsPerPage));
    const paginatedCars = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return filteredCars.value.slice(start, end);
    });
    const openModal = async (car: any) => {
        selectedCar.value = car;
        const result = car.comparisons  //await getCarComparisons(car.id)
        console.log('result ', result);
        // gat comparaisons
        relatedCars.value = result;
        resLaCentrale.value = computed(() => { return relatedCars.value.filter(vtr => vtr.domain === 'https://www.lacentrale.fr/').length ?? 0 });
        resAutoScout.value = computed(() => { return relatedCars.value.filter(vtr => vtr.domain === 'https://www.autoscout24.fr/').length ?? 0 });
        isModalOpen.value = true;
    };

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
        mPercent,
        openModal,
  isModalOpen,
  selectedCar,
  resLaCentrale,
  resAutoScout,
  relatedCars,
        domain,
        cutOffPrice,
        reloadPage,
        loadingCars,
        title,
        searchTerm,
        selectedColors,
        selectedModels,
        selectedDeals,
        currentPage,
        itemsPerPage,
        cars,
        handleLogout,
        getAllCars,
        availableColors,
        availableModels,
        availableDeals,
        filteredCars,
        pageCount,
        paginatedCars,
        to,
    };
}