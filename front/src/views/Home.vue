<template>
    <v-container fluid class="pa-0">
        <v-row no-gutters justify="center">
            <v-col cols="3" lg="2"></v-col>
            <v-col cols="6">
                <BetOnEventCard class="my-4 mx-2" v-for="event in events" :event="event" />
            </v-col>
            <v-col cols="3" lg="2">
                <CurrentBetPreview class="my-4 mx-2" />
            </v-col>
        </v-row>
        <admin-view v-if="isAdmin"></admin-view>
    </v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { useUserStore } from '../stores/user';
import { storeToRefs } from 'pinia';
import AdminView from '@/views/admin/AdminView.vue';
import imageUrl from '@/assets/boxing.jpg';
import events from '@/mocks/events.json';
import BetOnEventCard from '@/components/bets/BetOnEventCard.vue';
import CurrentBetPreview from '@/components/bets/CurrentBetPreview.vue';

export default defineComponent({
    components: { AdminView, BetOnEventCard, CurrentBetPreview },
    setup() {
        const userStore = useUserStore();
        const { isAdmin } = storeToRefs(userStore);

        return { events, isAdmin, imageUrl };
    },
});
</script>

<style scoped>
.custom-h1 {
    font-size: 42px;
    text-transform: uppercase;
    color: white;
    margin-bottom: 24px;
}
.custom-h2 {
    font-size: 24px;
    color: white;
    margin-bottom: 24px;
}
</style>
