<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useTicketStore } from '@/stores/tickets';
import { PropType, ref } from 'vue';
import { ITicketEvent } from '@/interfaces/event';
const ticketStore = useTicketStore()
const { ticketsEvent, selectedTicketEvent } = storeToRefs(ticketStore);

const emit = defineEmits(['selectedItem']);

const props = defineProps({
    readOnly: {
        type: Boolean as PropType<boolean>
    }
});

const ticketCategoryColor = (name: string) => {

    const colors: { [key: string]: string } = {
        "GOLD": "amber-darken-3",
        "SILVER": "blue-grey-lighten-1",
        "VIP": "light-blue-darken-2",
        "V_VIP": "light-blue-darken-4",
        "PEUPLE": "grey-darken-1"
    }

    return colors[name];
}

const setSelectedItem = (ticketEvent: ITicketEvent) => {
    emit('selectedItem', ticketEvent);
    selectedTicketEvent.value = ticketEvent;
}

</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <v-card v-for="ticketEvent of ticketsEvent" :elevation="selectedTicketEvent?.id === ticketEvent.id ? 4 : 0"
            @click="readOnly ? '' : setSelectedItem(ticketEvent)">
            <v-card-title>
                <span class="font-bold">Ticket category: </span>
                <span><v-chip :color="ticketCategoryColor(ticketEvent.ticketCategory.name)">{{ ticketEvent.ticketCategory.name
                }}</v-chip></span>
            </v-card-title>
            <v-card-text>
                <span class="font-bold">Max quantity: </span>
                <span>{{ ticketEvent.maxQuantity }}</span>
            </v-card-text>
            <v-card-text>
                <span class="font-bold">Price: </span>
                <span>{{ ticketEvent.price }} €</span>
            </v-card-text>

        </v-card>
    </div>
</template>