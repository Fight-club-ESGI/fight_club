<script lang="ts">
import { useUserStore } from './stores/user';
import { storeToRefs } from 'pinia';
import { defineComponent, ref, onMounted } from 'vue';
import Header from './components/AuthHeader.vue';
import NavigationDrawer from './components/NavigationDrawer.vue';
import { refreshToken } from './service';
import { useRoute } from 'vue-router';
import HomeHeader from '@/components/LandingPageHeader.vue';

export default defineComponent({
    components: { HomeHeader, Header, NavigationDrawer },
    setup() {
        const display = ref<boolean>(false);
        const route = useRoute();

        const userStore = useUserStore();
        const { signinWithToken } = userStore;
        const { isConnected } = storeToRefs(userStore);

        onMounted(async () => {
            if (refreshToken.value) {
                try {
                    // TODO: Uncomment when the back function is ready
                    await signinWithToken(refreshToken.value);
                } catch (error) {}
            }
        });

        return { display, isConnected, route };
    },
});
</script>

<template>
    <v-app app>
        <!-- <Header
            v-if="
                isConnected &&
                route.name !== 'home' &&
                !route.meta?.hideHeader
            "
            @toggleNavigationDrawer="display = !display"
        ></Header> -->

        <HomeHeader v-if="!route.meta?.hideHeader"></HomeHeader>

        <NavigationDrawer
            v-if="isConnected && route.name !== 'activate-status' && route.name !== 'login' && route.name !== 'signup' && route.name !== 'home'"
            :display="display"
        />
        <!-- Do not put padding on the v-main because it will break the website -->
        <v-main>
            <router-view />
        </v-main>
    </v-app>
</template>
