<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useTicketStore } from '@/stores/tickets';
const props = defineProps({
    display: {
        type: String,
        default: 'list'
    }
})
const ticketStore = useTicketStore()
const { ticketEvent } = storeToRefs(ticketStore);
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
    <div v-if="props.display === 'list'">
        <v-list v-if="ticketEvent.length > 0" density="compact" :lines="false" class="max-h-96 overflow-auto"
            @click:select="emit('selectedItem', $event)">
            <v-list-item v-for="te of ticketEvent" :value="te">

                <template v-slot:append>
                    <v-chip :color="ticketCategoryColor(te.ticketCategory.name)">{{ te.ticketCategory.name }}</v-chip>
                </template>

                <v-list-item-title class="font-bold pl-2">
                    {{ te.price }} €
                </v-list-item-title>

                <v-list-item-subtitle>
                    {{ te.maxQuantity }} tickets
                </v-list-item-subtitle>
            </v-list-item>
        </v-list>
    </div>
    <div v-else class="grid grid-cols-3 gap-3">
        <div v-for="te of ticketEvent" :key="te.id">
            <v-card>
                <v-card-title>
                    <span class="font-bold">Ticket category: </span>
                    <span>{{ te.ticketCategory.name }}</span>
                </v-card-title>
                <v-card-text>
                    <span class="font-bold">Price: </span>
                    <span>{{ te.price }} €</span>
                </v-card-text>
                <v-card-text>
                    <span class="font-bold">Max quantity: </span>
                    <span>{{ te.maxQuantity }}</span>
                </v-card-text>
                <v-card-actions>
                    <v-text-field placeholder="Quantity" type="number" min="1" max="10" step="1"
                        density="compact"></v-text-field>
                    <v-btn color="primary" text>Add to cart</v-btn>
                </v-card-actions>
            </v-card>
        </div>
    </div>
</template>