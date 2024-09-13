/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp, ref, onMounted, onUnmounted } from 'vue';
import DropdownMenu from './components/DropdownMenu.vue';
import SeoFields from './components/SeoFields.vue';
import Tabs from './components/Tabs.vue';
import Tab from './components/Tab.vue';
import SliderComponent from './components/SliderComponent.vue';
import Pharmacies from './components/Pharmacies.vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

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

        return {
            mobileMenuOpen,
            userMenuOpen,
            isDesktop,
            toggleMobileMenu,
            toggleUserMenu,
            metaTitle,
            metaDescription,
            keywords
        };
    }
});

app.component('dropdown-menu', DropdownMenu);
app.component('seo-fields', SeoFields);
app.component('tabs', Tabs);
app.component('tab', Tab);
app.component('slider-component', SliderComponent);
app.component('pharmacies', Pharmacies);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
