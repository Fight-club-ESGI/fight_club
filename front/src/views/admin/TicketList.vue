<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useTicketStore } from '@/stores/tickets';
const ticketStore = useTicketStore()
const { tickets } = storeToRefs(ticketStore);

const ticketCategoryColor = (name: string) => {

    const colors = {
        "GOLD": "amber-darken-3",
        "SILVER": "blue-grey-lighten-1",
        "VIP": "light-blue-darken-2",
        "DEFAULT": ""
    }

    return colors[name];
}
</script>

<template>

    <v-list density="compact" :lines="false" class="max-h-96 overflow-auto">
        <v-list-item v-for="ticket of tickets" :value="ticket">

            <template v-slot:prepend>
                <v-chip :color="ticket.availability ? 'green-accent-3' : 'red-darken-2' ">{{ ticket.availability ? "AVAILABLE" : "SOLD" }}</v-chip>
            </template>

            <template v-slot:append>
                <v-chip :color="ticketCategoryColor(ticket.ticketCategory.name)">{{ ticket.ticketCategory.name }}</v-chip>
            </template>

            <v-list-item-title class="font-bold pl-2">
                {{  ticket.price }} €
            </v-list-item-title>
        </v-list-item>
    </v-list>
</template>