<script lang="ts">
import { defineComponent, onMounted, computed } from 'vue';
import { useTicketStore } from '@/stores/tickets';
import { useEventStore } from '@/stores/event';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router'
export default defineComponent({
    setup() {
        const route = useRoute();
        const ticketStore = useTicketStore();
        const eventStore = useEventStore();
        const { tickets, ticketCategories } = storeToRefs(ticketStore);
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

        return { event, ticketCategories }
    }
})
</script>

<template>
    <v-container>
        <div v-if="event">
            Admin event {{ event.name }}
        </div>
    </v-container>
</template>