<template>
    <v-container>
        <user-informations></user-informations>
        <change-password></change-password>
        <div class="text-center mt-6">
            <v-btn @click="logoutUser()" variant="text">Logout</v-btn>
        </div>
    </v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import UserBets from '@/components/profile/Bets.vue';
import ChangePassword from '@/components/profile/ChangePassword.vue';
import UserTickets from '@/components/profile/Tickets.vue';
import UserInformations from '@/components/profile/UserInformations.vue';
import { useUserStore } from '@/stores/user';

export default defineComponent({
    components: { UserBets, ChangePassword, UserTickets, UserInformations },
    setup() {
        const userStore = useUserStore();
        const { logout } = userStore;
        const logoutUser = () => {
            logout();
        };
        return { logoutUser };
    },
});
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
