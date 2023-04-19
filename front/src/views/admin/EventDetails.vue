<script lang="ts">
import CreateTickets from '@/components/dialogs/CreateTickets.vue';
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
        const { tickets, ticketCategories, ticketsNumber, availableTickets } = storeToRefs(ticketStore);
        const { getTicketCategories, getTickets, createTicket } = ticketStore;
        const { getEvent } = eventStore;
        const { event } = storeToRefs(eventStore);

        const eventId = computed(() => route.params.id.toString());
        
        onMounted(async () => {
            try {
                await getEvent(eventId.value)
                await getTicketCategories();
                await getTickets(eventId.value);
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

            {{ ticketsNumber }} tickets created out of {{ event.capacity }} and {{ availableTickets }} are available !
            <ticket-list class="w-1/2"></ticket-list>
        </div>
    </v-container>
</template>