<template>
    <v-app-bar elevation="0" border flat>
        <div class="flex items-center align-middle bg-background fixed w-full text-white px-10 z-5000">
            <v-img @click="router.push({ name: 'home' })" class="cursor-pointer" height="36" width="36"
                :src="appLogo"></v-img>
            <div class="flex w-full pl-5">
                <ul class="flex gap-x-4">
                    <li class="text-lightgray hover:text-primary/90 transition ease-in-out delay-150 hover:text-primary/80">
                        <router-link :to="{ name: 'fighters' }" class="h-full block px-2 font-medium whitespace-nowrap "
                            style="line-height: calc(64px - 1px)">Fighters</router-link>
                    </li>
                    <li class="text-lightgray hover:text-primary/90 transition ease-in-out delay-150 hover:text-primary/80">
                        <router-link :to="{ name: 'events' }" class="h-full block px-2 font-medium whitespace-nowrap"
                            style="line-height: calc(64px - 1px)">Events</router-link>
                    </li>
                </ul>
                <v-spacer />
                <v-list v-if="!isConnected" class="flex bg-transparent text-lightgray">
                    <v-list-item @click="router.push({ name: 'login' })">Sign In</v-list-item>
                    <v-list-item @click="router.push({ name: 'register' })">Register</v-list-item>
                </v-list>
                <v-list v-else class="flex items-center bg-transparent">
                    <!-- <v-list-item @click="router.push({ name: 'user-profile' })">Access to my Platform</v-list-item> -->
                    <v-menu open-on-hover v-if="isAdmin">
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" v-bind="props">
                                Admin
                            </v-btn>
                        </template>

                        <div class="bg-white rounded-lg elevation-2 mt-2">
                            <ul class="py-2 px-2 text-lightgray text-small font-normal cursor-pointer">
                                <li v-for="item in adminMenu" @click="router.push({ name: item.to })"
                                    class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                    <Icon :icon="item.icon" /> {{ item.value }}
                                </li>
                                <li @click="logout()" class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                    <Icon icon="material-symbols:logout" />Logout
                                </li>
                            </ul>
                        </div>
                    </v-menu>

                    <v-menu open-on-hover>
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" v-bind="props" class="">
                                {{ user.email }}
                            </v-btn>
                        </template>

                        <div class="bg-white rounded-lg elevation-2 mt-2">
                            <ul class="py-2 px-2 text-lightgray text-small font-normal cursor-pointer">
                                <li v-for=" item in userMenu " @click="router.push({ name: item.to })"
                                    class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                    <Icon :icon="item.icon" /> {{ item.value }}
                                </li>
                                <li @click="logout()" class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                    <Icon icon="material-symbols:logout" />Logout
                                </li>
                            </ul>
                        </div>
                    </v-menu>
                </v-list>
                <v-badge v-if="isConnected" color="secondary" :content="cartTotalItems" offset-y="10"
                    class="flex items-center">
                    <v-btn color="lightgray" variant="text" icon="mdi-cart" @click="router.push({ name: 'user-cart' })" />
                </v-badge>
            </div>
        </div>
    </v-app-bar>
</template>
<script lang="ts" setup>
import appLogo from '../assets/gloves.png';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/user';
import { Icon } from '@iconify/vue';
import { useCartStore } from '@/stores/cart';

const router = useRouter();

const cartStore = useCartStore();
const { cartTotalItems } = storeToRefs(cartStore)
const userStore = useUserStore();
const { isConnected, isAdmin, user } = storeToRefs(userStore);
const { logout } = userStore;

const userMenu = [
    {
        value: 'Account',
        icon: 'mdi:user',
        to: 'user-profile',
    },
    {
        value: 'Wallet',
        icon: 'mingcute:pig-money-fill',
        to: 'user-wallet',
    },
    {
        value: 'Bets',
        icon: 'material-symbols:check-box',
        to: 'user-bets-history'
    },
    {
        value: 'Tickets',
        icon: 'icon-park-outline:tickets-two',
        to: 'user-tickets-history',
    },
];

const adminMenu = [
    {
        value: 'Account',
        icon: 'mdi:user',
        to: 'user-profile',
    },
    {
        value: 'Events',
        icon: 'mdi-calendar',
        to: 'event-admin',
    },
    {
        value: 'Fighters',
        icon: 'mdi-karate',
        to: 'fighter-admin'
    },
    {
        value: 'Sponsorships',
        icon: 'mdi-handshake',
        to: 'sponsorship-admin',
    },
];

const listMenu = computed(() => (isAdmin.value ? adminMenu : userMenu));

</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
