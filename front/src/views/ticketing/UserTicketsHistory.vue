<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>

        <v-container>
            <v-tabs v-model="tab" color="primary" align-tabs="center">
                <v-tab value="upcoming"> Upcoming </v-tab>
                <v-tab value="passed"> Passed </v-tab>
            </v-tabs>
            <v-window v-model="tab">
                <v-window-item value="upcoming" class="pt-10">
                    <v-row align="center" justify="center">
                        <v-col cols="12" lg="5">
                            <TicketHistoryCard class="my-4" v-for="ticket in upcomingEvents" :ticket="ticket" />
                        </v-col>
                    </v-row>
                </v-window-item>
                <v-window-item value="passed" class="pt-10">
                    <v-row align="center" justify="center">
                        <v-col cols="12" lg="5">
                            <TicketHistoryCard class="my-4" v-for="ticket in passedEvents" :ticket="ticket" />
                        </v-col>
                    </v-row>
                </v-window-item>
            </v-window>
        </v-container>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import tickets from '@/mocks/tickets.json';
import TicketHistoryCard from '@/components/TicketHistoryCard.vue';

const tab = ref();

const upcomingEvents = computed(() => {
    const filteredTickets = tickets.filter((e) => e.status === 'upcoming');
    return filteredTickets.sort((a, b) => new Date(b.eventDate).getTime() - new Date(a.eventDate).getTime());
});
const passedEvents = computed(() => {
    const filteredTickets = tickets.filter((e) => e.status === 'passed');
    return filteredTickets.sort((a, b) => new Date(b.eventDate).getTime() - new Date(a.eventDate).getTime());
});

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Tickets',
    }
];
</script>
