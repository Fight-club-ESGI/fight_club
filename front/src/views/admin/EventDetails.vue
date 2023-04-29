<script lang="ts">
import CreateTickets from '@/components/dialogs/CreateTicketsEvent.vue';
import TicketList from './TicketList.vue';
import { defineComponent, onMounted, computed, ref } from 'vue';
import { useTicketStore } from '@/stores/tickets';
import { useEventStore } from '@/stores/event';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router'
export default defineComponent({
    components: { CreateTickets, TicketList },
    setup() {
        const route = useRoute();
        const ticketStore = useTicketStore();
        const eventStore = useEventStore();
        const { ticketCategories, ticketsNumber, availableTickets } = storeToRefs(ticketStore);
        const { getTicketCategories, getTicketsEvent } = ticketStore;
        const { getEvent } = eventStore;
        const { event } = storeToRefs(eventStore);

        const eventId = computed(() => route.params.id.toString());

        onMounted(async () => {
            try {
                await getEvent(eventId.value)
                await getTicketCategories();
                await getTicketsEvent(eventId.value);
            } catch (error) {
                console.error(error)
            }
        });

        return { event, ticketCategories, ticketsNumber, availableTickets }
    }
})
</script>

<template>
    <v-container>
        <div v-if="event">
            <create-tickets></create-tickets>

            <ticket-list class="w-1/2"></ticket-list>
        </div>
    </v-container>
</template>