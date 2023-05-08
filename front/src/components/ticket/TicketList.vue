<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useTicketStore } from '@/stores/tickets';
import { createToast } from 'mosha-vue-toastify';
import { defineProps, defineEmits } from 'vue';
import TicketEventCard from './TicketEventCard.vue';
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
            <TicketEventCard :ticketEvent="te" />
        </div>
    </div>
</template>