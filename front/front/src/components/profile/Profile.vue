<template>
    <v-container>
        <v-tabs v-model="tab" color="primary" align-tabs="center">
            <v-tab value="Informations"> Profile </v-tab>
            <v-tab value="Bets"> Bets </v-tab>
            <v-tab value="Tickets"> Tickets </v-tab>
            <v-tab value="Security"> Security </v-tab>
        </v-tabs>

        <v-window v-model="tab">
            <v-window-item value="Informations" class="pt-10">
                <user-informations></user-informations>
            </v-window-item>

            <v-window-item value="Bets" class="pt-10">
                <user-bet></user-bet>
            </v-window-item>

            <v-window-item value="Tickets" class="pt-10">
                <user-tickets></user-tickets>
            </v-window-item>

            <v-window-item value="Security" class="pt-10">
                <security></security>
            </v-window-item>
        </v-window>
    </v-container>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import UserBet from '@/components/profile/Bet.vue';
import Security from '@/components/profile/ChangePassword.vue';
import UserTickets from '@/components/profile/Tickets.vue';
import UserInformations from '@/components/profile/UserInformations.vue';

export default defineComponent({
    components: { UserBet, Security, UserTickets, UserInformations },
    setup() {
        const tab = ref();

        return { tab };
    },
});
</script>
