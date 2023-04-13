<template>
    <!--<v-app-bar color="primary-darken-1">
        <v-app-bar-title
            @click="router.push({ name: 'home' })" style="cursor: pointer"
        >
            Thunderous Knockout Fighting
        </v-app-bar-title>

        <template v-if="isConnected">
            <router-link to="/bets">
                <v-btn color="white"><v-icon class="mr-2">mdi-checkbox-marked</v-icon>My bets</v-btn>
            </router-link>
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" color="white" class="mr-3"><v-icon size="30" class="mr-2">mdi-account</v-icon>{{ user.email }}</v-btn>
                </template>

                <v-list>
                    <v-list-item
                        v-for="item of listMenu"
                        :key="item.value"
                        @click="router.push({ name: item.to })"
                        :value="item"
                        active-color="primary"
                    >
                        <template v-slot:prepend>
                            <v-icon :icon="item.icon"></v-icon>
                        </template>

                        <v-list-item-title>{{ item.value }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="logoutUser()">
                        <template v-slot:prepend> <v-icon icon="mdi-logout"></v-icon> </template>Logout</v-list-item
                    >
                </v-list>
            </v-menu>
        </template>

        <template v-else>
            <div class="d-flex mr-3" style="gap: 0.75rem">
                <v-btn @click="router.push({ name: 'signup' })" color="secondary" variant="flat">signup </v-btn>
                <v-btn @click="router.push({ name: 'login' })" color="white" variant="flat">signin </v-btn>
            </div>
        </template>
    </v-app-bar>
-->

    <div class="flex items-center align-middle bg-black/20 h-24 fixed w-full text-white px-20">
        <div
            class="h-full w-1/4 bg-cover bg-center"
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
            <v-list v-if="isConnected" class="flex bg-transparent text-white">
                <v-list-item @click="router.push({ name: 'login' })">Sign In</v-list-item>
                <v-list-item @click="router.push({ name: 'signup' })">Register</v-list-item>
            </v-list>
            <v-list v-else class="flex bg-transparent text-white">
                <v-list-item @click="router.push({ name: 'login' })">Access to my Platform</v-list-item>
            </v-list>
        </div>
    </div>
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
