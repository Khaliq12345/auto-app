import axios from 'axios';
import { useNotifications } from '~/utils/globals';

export function useListingFunctions() {
    // Globals
    const router = useRouter();
    const loadingCars = ref(false);
    const globalLoading = ref(false);
    const isStatusModalOpen = ref(false);
    const scrapStarted = ref(false);
    const scrapStatus = ref('100 %');
    const lastScrap = ref('- - -');
    const totalToProcess = ref(0);
    const alreadyProcessed = ref(0);
    const { showToast } = useNotifications();
    const config = useRuntimeConfig();
    const urlAPI = config.public.urlAPI;
    // Cars
    const title = 'Cars Listing';
    const isModalOpen = ref(false);
    const selectedCar = ref(null);
    const relatedCars = ref<any[]>([]);
    const resLaCentrale = ref();
    const resAutoScout = ref();
    const searchTerm = ref('');
    const cutOffPrice = ref(500);
    const selectedColors = ref<any[]>([]);
    const selectedModels = ref<any[]>([]);
    const selectedDeals = ref<any[]>([]);
    const currentPage = ref(1);
    const itemsPerPage = 10;
    const cars = ref<any[]>([])
    // Excel
    const file = ref(null);
    const isImporting = ref(false);
    const uploadProgress = ref(0);
    const importError = ref();
    const importSuccess = ref(false);
    // Methods
    // 
    const reloadPage = () => {
        getAllCars()
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
    const getAllCars = async () => {
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
                        domain: '',
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
    const getCarComparisons = async (id: string) => {
        globalLoading.value = true;
        const result = ref([]);
        const accessToken = sessionStorage.getItem('AccessToken');
        const refToken = sessionStorage.getItem('RefreshToken');
        try {
            const response = await axios.get(urlAPI + "/get_car_comparisons",
                {
                    params: {
                        access_token: accessToken,
                        refresh_token: refToken,
                        car_id: id
                    },
                    headers: {
                        "accept": "application/json",
                        "content-type": "application/x-www-form-urlencoded",
                    },
                },
            );

            // 
            // console.log('Get Car Comparisons:', response.data);
            result.value = response.data.details.data;
            // Stocker l'access token dans la session du navigateur
            sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
            sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
            sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

        } catch (err) {
            console.error('Erreur de requete:', err);
        } finally {
            // 
            globalLoading.value = false;
            return result.value;
        }

    }
    const startScrapping = async () => {
        scrapStarted.value = false;
        const accessToken = sessionStorage.getItem('AccessToken');
        const refToken = sessionStorage.getItem('RefreshToken');
        try {
            const response = await axios.get(urlAPI + "/start_scraping",
                {
                    params: {
                        access_token: accessToken,
                        refresh_token: refToken
                    },
                    headers: {
                        "accept": "application/json",
                        "content-type": "application/x-www-form-urlencoded",
                    },
                },
            );

            // 
            console.log('Start Scraping :', response.data);
            scrapStarted.value = true;
            showToast('Success', "Successfully Started Scrapping. You can check Status anytime by pressing on the button", 'i-heroicons-check-badge', 'success');
            // Stocker l'access token dans la session du navigateur
            sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
            sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
            sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

        } catch (err) {
            console.error('Erreur de requete:', err);
            showToast('Error !', "Failled to Start Scraping, maybe server error !", 'i-heroicons-exclamation-triangle', 'error');
        } finally {
            // 
        }

    }
    const seeStatus = async () => {
        const accessToken = sessionStorage.getItem('AccessToken');
        const refToken = sessionStorage.getItem('RefreshToken');
        try {
            const response = await axios.get(urlAPI + "/scrape_status",
                {
                    params: {
                        access_token: accessToken,
                        refresh_token: refToken
                    },
                    headers: {
                        "accept": "application/json",
                        "content-type": "application/x-www-form-urlencoded",
                    },
                },
            );

            // 
            console.log('Scraping Status :', response.data);
            totalToProcess.value = response.data.details.data[0].total_running;
            alreadyProcessed.value = response.data.details.data[0].total_completed;
            scrapStatus.value = (alreadyProcessed.value * 100 / totalToProcess.value).toFixed(2) + ' %';
            lastScrap.value = response.data.details.data[0].stopped_at??'- - -';
            // Stocker l'access token dans la session du navigateur
            sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
            sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
            sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

        } catch (err) {
            console.error('Erreur de requete:', err);
        } finally {
            // 
        }

        isStatusModalOpen.value = true;

    }
    // seeStatus
    // 
    const availableColors = computed(() => cars.value ? [...new Set(cars.value.map(car => car.color))].filter(Boolean).map(color => ({ label: color, value: color })) : []);
    const availableModels = computed(() => cars.value ? [...new Set(cars.value.map(car => car.make))].filter(Boolean).map(model => ({ label: model, value: model })) : []);
    const availableDeals = computed(() => cars.value ? [...new Set(cars.value.map(car => car.card_color))].filter(Boolean).map(card_color => ({ label: card_color == 'red' ? 'Worst Deals' : card_color == 'green' ?  'Best Deals' : 'Not Bads', value: card_color })) : []);
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
    const handleFileChange = (event: any) => {
        file.value = event.target.files[0];
    };
    const importFile = async () => {
        if (!file.value) return;
        isImporting.value = true;
        uploadProgress.value = 0;
        importError.value = null;
        importSuccess.value = false; // 

        const formData = new FormData();
        formData.append('file', file.value);

        try {
            const response = await axios.post(urlAPI + '/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onUploadProgress: (progressEvent: any) => {
                    uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                },
            });

            importSuccess.value = true;
            console.log('Upload successful:', response.data);
        } catch (error) {
            importError.value = 'Error while uploading your file !';
            console.error('Upload error:', error);
        } finally {
            isImporting.value = false;
            // Réinitialiser la progression après la fin (succès ou échec)
            // setTimeout(() => {
            //   this.uploadProgress = -1;
            // }, 2000);
        }
    }
    let intervalId: NodeJS.Timeout | null = null;

    const setupMyInterval = (callback: () => void, intervalMs: number) => {
        callback(); // Run the function immediately
        intervalId = setInterval(callback, intervalMs);
    };

    onMounted(() => {
        getAllCars();
        setupMyInterval(seeStatus, 10000);
    });

    onUnmounted(() => {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    });

    return {
        cutOffPrice,
        reloadPage,
        loadingCars,
        globalLoading,
        isStatusModalOpen,
        scrapStarted,
        lastScrap,
        scrapStatus,
        totalToProcess,
        alreadyProcessed,
        title,
        isModalOpen,
        selectedCar,
        relatedCars,
        resLaCentrale,
        resAutoScout,
        searchTerm,
        selectedColors,
        selectedModels,
        selectedDeals,
        currentPage,
        itemsPerPage,
        cars,
        file,
        isImporting,
        uploadProgress,
        importError,
        importSuccess,
        handleLogout,
        getAllCars,
        getCarComparisons,
        startScrapping,
        seeStatus,
        availableColors,
        availableModels,
        availableDeals,
        filteredCars,
        pageCount,
        paginatedCars,
        openModal,
        to,
        handleFileChange,
        importFile
    };
}