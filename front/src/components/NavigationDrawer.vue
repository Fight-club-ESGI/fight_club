<template>
    <v-navigation-drawer class="h-full shadow" :rail="display" permanent>
        <v-list class="flex-column relative h-full" density="compact" nav>
            <v-list-item :prepend-avatar="`https://xsgames.co/randomusers/assets/avatars/pixel/43.jpg`"
                append-icon="mdi-gear" :title="user.username" :subtitle="user.email" :to="{ name: 'user-profile' }" nav
                class="py-2" />
            <v-divider></v-divider>
            <v-list-item :to="{ name: 'user-wallet' }" exact prepend-icon="mdi-piggy-bank" title="My wallet"
                value="wallet"></v-list-item>
            <v-list-item :to="{ name: 'user-tickets-history' }" exact prepend-icon="mdi-ticket" title="Tickets"
                value="tickets"></v-list-item>
            <v-list-item :to="{ name: 'user-bets-history' }" exact prepend-icon="mdi-checkbox-marked" title="My bets"
                value="bets"></v-list-item>
            <v-list-item :to="{ name: 'user-cart' }" exact title="My cart" value="cart">
                <template #prepend>
                    <div class="pr-8">
                        <v-badge color="red" :content="cartTotalItems">
                            <v-icon>mdi-cart</v-icon>
                        </v-badge>
                    </div>
                </template>
            </v-list-item>
            <div v-if="isAdmin">
                <div class="px-4 py-2">Admin</div>
                <v-divider />
                <v-list-item :to="{ name: 'event-admin' }" exact prepend-icon="mdi-calendar" title="Events"
                    value="events"></v-list-item>
                <v-list-item :to="{ name: 'fighter-admin' }" exact prepend-icon="mdi-karate" title="Fighters"
                    value="fighters"></v-list-item>
                <v-list-item :to="{ name: 'sponsorship-admin' }" exact prepend-icon="mdi-handshake" title="Sponsorships"
                    value="users"></v-list-item>
            </div>
            <v-divider />
            <v-list-item @click="logoutUser()" exact prepend-icon="mdi-logout" title="Logout"></v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script lang="ts">
import { storeToRefs } from 'pinia';
import { defineComponent, ref } from 'vue';
import { useUserStore } from '@/stores/user';
import { useRoute } from "vue-router";
import { useCartStore } from '@/stores/cart';

export default defineComponent({
    props: {
        display: {
            type: Boolean,
            default: false,
        },
    },
    setup() {
        // const display = props
        const route = useRoute();
        const userStore = useUserStore();
        const { user, isAdmin } = storeToRefs(userStore);
        const { logout } = userStore;
        const logoutUser = () => {
            logout();
        };
        const cartStore = useCartStore();
        const { cartTotalItems } = storeToRefs(cartStore);

        return { user, isAdmin, logoutUser, route, cartTotalItems };
    },
});
</script>
