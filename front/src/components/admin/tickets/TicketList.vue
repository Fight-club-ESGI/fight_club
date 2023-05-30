<script setup lang="ts">
import { storeToRefs } from 'pinia';
import { useTicketStore } from '@/stores/tickets';
import { PropType, computed, ref } from 'vue';
import { ITicketEvent } from '@/interfaces/event';
import { formatNumber } from '@/service/helpers';
import { Icon } from '@iconify/vue';
const ticketStore = useTicketStore()
const { ticketsEvent, selectedTicketEvent } = storeToRefs(ticketStore);

const emit = defineEmits(['selectedItem']);

const props = defineProps({
    readOnly: {
        type: Boolean as PropType<boolean>
    },
    isActive: {
        type: Boolean as PropType<boolean>
    },
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

const getPriceUnit = (price: number) => formatNumber(price).split(',')[0];

const getPriceDecimal = (price: number) => formatNumber(price).split(',')[1];

</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <v-card class="py-2 px-4"
            v-for="ticketEvent of ticketsEvent.filter(ticketEvent => ticketEvent.isActive == isActive && (!isActive ? ticketEvent.tickets.length > 0 : true))"
            :elevation="selectedTicketEvent?.id === ticketEvent.id ? 4 : 0"
            @click="readOnly ? '' : setSelectedItem(ticketEvent)">
            <div class="flex justify-between items-start pb-5">
                <h2 class="text-4xl font-bold">{{ ticketEvent.ticketCategory.name }}
                    <v-chip :color="ticketCategoryColor(ticketEvent.ticketCategory.name)">{{
                        ticketEvent.ticketCategory.name
                    }}</v-chip>
                </h2>
            </div>
            <div class="flex items-center justify-between mb-5">
                <div class="flex gap-x-3">
                    <span class="flex gap-2 items-center bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                        <Icon height="20" icon="mdi:love-seat" />
                        {{ ticketEvent.maxQuantity }}
                    </span>
                    <span class="flex gap-2 items-center bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                        <Icon height="20" icon="mdi:cash-register" />
                        {{ ticketEvent.tickets.length }}
                    </span>
                </div>
            </div>
            <div v-if="isActive">
                <span class="font-bold">Available: </span>
                <span>{{ ticketEvent.maxQuantity - ticketEvent.tickets.length }}</span>
            </div>
            <v-card-text>
                <div class="my-1 text-right font-weight-bold text-2xl self-end">
                    {{ getPriceUnit(ticketEvent.price) }}<span class="text-grey-darken-1 text-lg">,{{
                        getPriceDecimal(ticketEvent.price)
                    }}</span>
                    €
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>