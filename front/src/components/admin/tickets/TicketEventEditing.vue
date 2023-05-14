<script lang="ts" setup>
import { ITicketEvent, UpdateTicketEvent } from '@/interfaces/event';
import { useEventStore } from '@/stores/event';
import { useTicketStore } from '@/stores/tickets';
import { storeToRefs } from 'pinia';
import { watch, Ref, ref, toRefs } from 'vue';
import { useRoute } from 'vue-router';
const ticketStore = useTicketStore();
const { ticketsEvent } = storeToRefs(ticketStore);
const eventStore = useEventStore();
const { event } = storeToRefs(eventStore);
const { updateTicketEvent } = ticketStore;
const route = useRoute();

const props = defineProps({
    selectedItem: Object
});

const tickets = ref(0);
const ticketsPrice = ref(0);

const { selectedItem } = toRefs(props);

watch(() => selectedItem, () => {
    tickets.value = selectedItem?.value?.id.maxQuantity;
    ticketsPrice.value = selectedItem?.value?.id.price;
}, { deep: true, immediate: true });


const save = async () => {
    try {
        if (selectedItem?.value) {
            const payload: UpdateTicketEvent = {
                id: selectedItem.value.id.id,
                maxQuantity: Number(tickets.value),
                event: '/events/' + route.params.id?.toString(),
                ticketCategory: '/ticket_categories/' + selectedItem.value.id.ticketCategory.id,
                price: ticketsPrice.value
            }

            await updateTicketEvent(payload);
        }
    } catch (error) {
        console.error(error)
    }
}
</script>

<template>
    <div v-if="props.selectedItem">
        <div class="flex gap-x-4">
            <v-text-field type="number" variant="outlined" :min="props.selectedItem.id.maxQuantity"
                label="Number of tickets" v-model="tickets" density="compact" />
            <v-text-field type="number" variant="outlined" label="Price" density="compact" v-model.number="ticketsPrice"
                append-inner-icon="mdi-euro" />
        </div>
        <div class="flex justify-end">
            <v-btn variant="text" color="primary" @click="save">Save</v-btn>
        </div>
    </div>
    <div v-else>
        <v-alert v-if="event && new Date(event.timeStart) > new Date()"
            :text="ticketsEvent.length > 0 ? 'Select a type of ticket to edit it' : 'Create a ticket category'"
            variant="outlined"></v-alert>
    </div>
</template>