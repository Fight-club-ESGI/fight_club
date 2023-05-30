<script lang="ts" setup>
import { ITicketEvent, UpdateTicketEvent } from '@/interfaces/event';
import { useEventStore } from '@/stores/event';
import { useTicketStore } from '@/stores/tickets';
import { storeToRefs } from 'pinia';
import { emit } from 'process';
import { watch, Ref, ref, toRefs } from 'vue';
import { useRoute } from 'vue-router';
const ticketStore = useTicketStore();
const { ticketsEvent } = storeToRefs(ticketStore);
const eventStore = useEventStore();
const { event } = storeToRefs(eventStore);
const { updateTicketEvent } = ticketStore;
const route = useRoute();

const emit = defineEmits(['reset-selection'])
const props = defineProps({
    selectedItem: Object
});

const tickets = ref(0);
const ticketsPrice = ref(0);

const { selectedItem } = toRefs(props);

watch(() => selectedItem, () => {
    tickets.value = selectedItem?.value?.maxQuantity;
    ticketsPrice.value = selectedItem?.value?.price;
}, { deep: true, immediate: true });


const save = async () => {
    try {
        if (selectedItem?.value) {
            const payload: UpdateTicketEvent = {
                id: selectedItem.value.id,
                maxQuantity: Number(tickets.value),
                event: '/events/' + route.params.id.toString(),
                ticketCategory: '/ticket_categories/' + selectedItem.value.ticketCategory.id,
                price: ticketsPrice.value
            }

            await updateTicketEvent(payload);
            emit('reset-selection');
        }
    } catch (error) {
        console.error(error)
    }
}

const rules = {
    required: (value: any) => !!value || 'Required.',
    minCapacity: (value: any) => value >= selectedItem?.value?.tickets.length || 'Max quantity must be greater than {' + selectedItem?.value?.tickets.length
        + '} the number of tickets sold',
};
</script>

<template>
    <form ref="form" v-if="props.selectedItem">
        <div class="flex flex-col gap-x-4 gap-y-4">
            <v-text-field type="number" variant="outlined" :rules="[rules.minCapacity]"
                :min="props.selectedItem.tickets.length" label="Max quantity" v-model="tickets" density="compact" />
            <v-text-field type="number" variant="outlined" label="Price" step="0.01" density="compact"
                v-model="ticketsPrice" />
        </div>
        <div class="flex justify-end">
            <v-btn variant="text" color="primary" :disabled="tickets < props.selectedItem.tickets.length"
                @click="save">Save</v-btn>
        </div>
    </form>
    <div v-else>
        <div v-if="event && new Date(event.timeStart) > new Date()">
            <i v-if="ticketsEvent.length > 0">Select a type of ticket to edit it</i>
            <i v-else>Create a ticket category</i>
        </div>
    </div>
</template>