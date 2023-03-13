<script lang="ts">
import { storeToRefs } from 'pinia';
import { defineComponent, ref, onMounted } from 'vue';
import Header from './components/Header.vue';
import NavigationDrawer from './components/NavigationDrawer.vue';
import { useUserStore } from './stores/user';
import { token } from './service';
import { useRoute } from 'vue-router';
export default defineComponent({
    components: { Header, NavigationDrawer },
    setup() {
        const display = ref<boolean>(false);
        const route = useRoute();

        const userStore = useUserStore();
        const { signinWithToken } = userStore;
        const { isAdmin } = storeToRefs(userStore);

        onMounted(async () => {
            if (token.value) {
                try {
                    // TODO: Uncomment when the back function is ready
                    // await signinWithToken(token.value);
                } catch (error) {}
            }
        });

        return { display, isAdmin, route };
    },
});
</script>

<template>
    <v-app app>
        <Header
            v-if="route.name != 'activate-status' && route.name != 'login' && route.name != 'signup'"
            @toggleNavigationDrawer="display = !display"
        ></Header>
        <NavigationDrawer v-if="isAdmin" :display="display"></NavigationDrawer>
        <!-- Do not put padding on the v-main because it will break the website -->
        <v-main>
            <router-view />
        </v-main>
    </v-app>
</template>
