/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp, ref, onMounted, onUnmounted, computed } from 'vue';
import DropdownMenu from './components/DropdownMenu.vue';
import SeoFields from './components/SeoFields.vue';
import Tabs from './components/Tabs.vue';
import Tab from './components/Tab.vue';
import SliderComponent from './components/SliderComponent.vue';
import Pharmacies from './components/Pharmacies.vue';
import Hospitals from './components/Hospitals.vue';
import CollegesAndSchools from './components/CollegesAndSchools.vue';
import { createPinia } from 'pinia';
import { useSliderStore } from './stores/sliderStore';
import ImageUploader from './components/ImageUploader.vue';
import Restaurants from './components/Restaurants.vue';
import ContentRepeater from './components/ContentRepeater.vue';
import SafetyInfo from './components/SafetyInfo.vue';
import Faqs from './components/Faqs.vue';
import Testimonials from './components/Testimonials.vue';
import Itinerary from './components/Itinerary.vue';
import HomeSlider from './components/HomeSlider.vue';
import MenuBuilder from './components/MenuBuilder.vue'
import ImageRepeater from './components/ImageRepeater.vue'

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const pinia = createPinia();

const app = createApp({
    setup() {
        const mobileMenuOpen = ref(false);
        const userMenuOpen = ref(false);
        const isDesktop = ref(window.innerWidth >= 768);

        const metaTitle = ref('');
        const metaDescription = ref('');
        const keywords = ref('');

        const toggleMobileMenu = () => {
            mobileMenuOpen.value = !mobileMenuOpen.value;
        };

        const toggleUserMenu = () => {
            userMenuOpen.value = !userMenuOpen.value;
        };

        const handleResize = () => {
            const wasDesktop = isDesktop.value;
            isDesktop.value = window.innerWidth >= 768;
            if (!wasDesktop && isDesktop.value) {
                // Transitioning from mobile to desktop
                mobileMenuOpen.value = true;
            } else if (wasDesktop && !isDesktop.value) {
                // Transitioning from desktop to mobile
                mobileMenuOpen.value = false;
            }
        };

        const handleClickOutside = (event) => {
            if (!event.target.closest('#app')) {
                userMenuOpen.value = false;
                mobileMenuOpen.value = false;
            }
        };

        onMounted(() => {
            window.addEventListener('resize', handleResize);
            document.addEventListener('click', handleClickOutside);
            handleResize(); // Call on initial load
        });

        onUnmounted(() => {
            window.removeEventListener('resize', handleResize);
            document.removeEventListener('click', handleClickOutside);
        });

        const sliderStore = useSliderStore();
        const sliderData = computed(() => JSON.stringify(sliderStore.getSliderData));
        const isUploading = computed(() => sliderStore.getUploadingStatus);

        return {
            mobileMenuOpen,
            userMenuOpen,
            isDesktop,
            toggleMobileMenu,
            toggleUserMenu,
            metaTitle,
            metaDescription,
            keywords,
            sliderData,
            isUploading
        };
    },
    components: {
        DropdownMenu,
        SeoFields,
        Tabs,
        Tab,
        SliderComponent,
        Pharmacies,
        Hospitals,
        ImageUploader,
        CollegesAndSchools,
        Restaurants,
        ContentRepeater,
        SafetyInfo,
        Faqs,
        Testimonials,
        Itinerary,
        HomeSlider,
        MenuBuilder,
        ImageRepeater
    }
});

app.use(pinia);
app.mount('#app');
