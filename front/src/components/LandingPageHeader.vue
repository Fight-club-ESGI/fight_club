<template>
    <v-app-bar>
        <div class="flex items-center align-middle bg-black/80 fixed w-full text-white px-10 z-5000">
        <v-app-bar-nav-icon @click="emit('toggleNavigationDrawer')" v-if="isAdmin"></v-app-bar-nav-icon>
        <v-img @click="router.push({ name: 'home' })" class="cursor-pointer" height="100" width="100" src="src/assets/home.png"></v-img>
        <div class="flex w-full">
            <v-list v-if="!isConnected" class="flex bg-transparent text-white">
                <v-list-item @click="router.push({ name: 'fighters' })">Fighters</v-list-item>
                <v-list-item @click="router.push({ name: 'events' })">Events</v-list-item>
            </v-list>
            <v-list v-else class="flex bg-transparent text-white">
                <v-list-item @click="router.push({ name: 'fighters' })">Fighters</v-list-item>
                <v-list-item @click="router.push({ name: 'events' })">Events</v-list-item>
            </v-list>
            <v-spacer />
            <v-list v-if="!isConnected" class="flex bg-transparent text-white">
                <v-list-item @click="router.push({ name: 'login' })">Sign In</v-list-item>
                <v-list-item @click="router.push({ name: 'signup' })">Register</v-list-item>
            </v-list>
            <v-list v-else class="flex bg-transparent text-white">
                <v-list-item @click="router.push({ name: 'user-profile' })">Access to my Platform</v-list-item>
            </v-list>
        </div>
    </div>
    </v-app-bar>

</template>
<script lang="ts">
import { storeToRefs } from 'pinia';
import { defineComponent, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/user';

export default defineComponent({
    setup(props, { emit }) {
        const router = useRouter();

        const userStore = useUserStore();
        const { isConnected, isAdmin, user } = storeToRefs(userStore);
        const { toggleAdmin, logout } = userStore;

        const userMenu = [
            {
                value: 'Account',
                icon: 'mdi-account',
                to: 'user-profile',
            },
            {
                value: 'Wallet',
                icon: 'mdi-piggy-bank',
                to: 'user-wallet',
            },
            {
                value: 'Tickets',
                icon: 'mdi-ticket',
                to: 'user-tickets-history',
            },
        ];

        const adminMenu = [
            {
                value: 'Account',
                icon: 'mdi-account',
                to: 'user-profile',
            },
        ];

        const listMenu = computed(() => (isAdmin.value ? adminMenu : userMenu));

        const logoutUser = () => {
            logout();
        };

        return {
            userMenu,
            router,
            isAdmin,
            isConnected,
            user,
            emit,
            toggleAdmin,
            listMenu,
            logoutUser,
        };
    },
});
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
