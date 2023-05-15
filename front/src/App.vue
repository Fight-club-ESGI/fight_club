<script lang="ts" setup>
import { useUserStore } from './stores/user';
import { storeToRefs } from 'pinia';
import { defineComponent, ref, onMounted } from 'vue';
import { refreshToken } from './service';
import { useRoute } from 'vue-router';
import HomeHeader from '@/components/LandingPageHeader.vue';


const display = ref<boolean>(false);
const route = useRoute();

const userStore = useUserStore();
const { signinWithToken } = userStore;
const { isConnected } = storeToRefs(userStore);

onMounted(async () => {

    if (refreshToken.value) {
        try {
            await signinWithToken(refreshToken.value);
        } catch (error) { }
    }
});

</script>

<template>
    <v-app app class="h-screen">
        <HomeHeader v-if="!route.meta?.hideHeader"></HomeHeader>

        <!-- Do not put padding on the v-main because it will break the website -->
        <v-main class="h-full overflow-auto">
            <router-view />
        </v-main>
    </v-app>
</template>
