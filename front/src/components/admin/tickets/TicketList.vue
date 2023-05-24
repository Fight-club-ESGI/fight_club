<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useTicketStore } from '@/stores/tickets';
import { PropType } from 'vue';
const ticketStore = useTicketStore()
const { activeTickets } = storeToRefs(ticketStore);

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

</script>

<template>
    <v-list v-if="activeTickets.length > 0" density="compact" :disabled="readOnly" :lines="false"
        class="max-h-96 overflow-auto" @click:select="readOnly ? '' : emit('selectedItem', $event)">
        <v-list-item v-for="ticketEvent of activeTickets" :value="ticketEvent">

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