<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useTicketStore } from '@/stores/tickets';
const ticketStore = useTicketStore()
const { ticketsEvent } = storeToRefs(ticketStore);
const emit = defineEmits(['selectedItem']);
const ticketCategoryColor = (name: string) => {

    const colors = {
        "GOLD": "amber-darken-3",
        "SILVER": "blue-grey-lighten-1",
        "VIP": "light-blue-darken-2",
        "V_VIP": "light-blue-darken-4",
        "PEUPLE": "grey-darken-1"
    }

    return colors[name];
}

</script>

<template>
    <v-list v-if="ticketsEvent.length > 0" density="compact" :lines="false" class="max-h-96 overflow-auto"
        @click:select="emit('selectedItem', $event)">
        <v-list-item v-for="ticketEvent of ticketsEvent" :value="ticketEvent">

            <template v-slot:append>
                <v-chip :color="ticketCategoryColor(ticketEvent.ticketCategory.name)">{{ ticketEvent.ticketCategory.name
                }}</v-chip>
            </template>

            <v-list-item-title class="font-bold pl-2">
                {{ ticketEvent.price }} €
            </v-list-item-title>

            <v-list-item-subtitle>
                {{ ticketEvent.maxQuantity }} tickets
            </v-list-item-subtitle>
        </v-list-item>
    </v-list>
</template>