<template>
    <v-app-bar>
        <div class="flex items-center align-middle bg-black/80 h-20 fixed w-full text-white px-20 z-5000">
        <div
            @click="router.push({ name: 'home' })"
            class="h-full w-1/4 bg-cover bg-center cursor-pointer"
            style="background-image: url('src/assets/Thunderous_knockout__2_-removebg-preview.png');"
        >
        </div>
        <div class="flex w-full">
            <v-list class="flex bg-transparent text-white">
                <v-list-item>Home</v-list-item>
                <v-list-item>Bets</v-list-item>
                <v-list-item>Booking</v-list-item>
                <v-list-item>About Us</v-list-item>
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
