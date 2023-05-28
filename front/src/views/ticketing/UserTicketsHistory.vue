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
                    <div class="flex gap-4 justify-center flex-wrap">
                        <TicketHistoryCard class="" v-for="ticket in upcomingEvents" :ticket="ticket" />
                    </div>
                </v-window-item>
                <v-window-item value="passed" class="pt-10">
                    <v-row align="center" justify="center">
                        <v-col cols="6" lg="5">
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
import TicketHistoryCard from '@/components/TicketHistoryCard.vue';
import { useTicketStore } from '@/stores/tickets';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import { IMyTicket } from '@/interfaces/ticket';

const ticketStore = useTicketStore();
const { myTickets } = storeToRefs(ticketStore);
const { getTickets } = ticketStore;

onMounted(async () => {
    try {
        await getTickets();
        console.log(myTickets.value)
    } catch (error) {
        console.log(error);
    }
});

const tab = ref();

const ticketsByEvent = computed(() => {
    const ticketsByEvent = new Map();
    myTickets.value.forEach((ticket: IMyTicket) => {
        if (ticketsByEvent.has(ticket.event.id)) {
            ticketsByEvent.get(ticket.event.id).push(ticket);
        } else {
            ticketsByEvent.set(ticket.event.id, [ticket]);
        }
    });
    return ticketsByEvent;
});

const upcomingEvents = computed(() => {
    return myTickets.value
        .filter((ticket: IMyTicket) => new Date(ticket.event.timeStart).getTime() >= new Date().getTime())
        .sort((a, b) => new Date(b.eventDate).getTime() - new Date(a.eventDate).getTime());
});
const passedEvents = computed(() => {
    return myTickets.value
        .filter((ticket: IMyTicket) => new Date(ticket.event.timeStart).getTime() < new Date().getTime())
        .sort((a, b) => new Date(b.eventDate).getTime() - new Date(a.eventDate).getTime());
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
