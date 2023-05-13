<template>
    <div v-if="event">
        <div class="flex gap-x-2 pb-3">
            <div>
                <v-img
                    :src="event.locationLink ? event.locationLink : 'https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80'"
                    aspect-ratio="16/9" width="300" class="rounded-xl" />
            </div>
            <div class="flex flex-col">
                <h2 class="text-3xl font-extrabold">{{ event.name }}</h2>
                <div><i>{{ event.location }}</i></div>
                <div class="">
                    {{
                        new Date(event.timeStart).toLocaleString('en-GB', {
                            year: 'numeric',
                            month: 'long',
                            day: '2-digit',
                        })
                    }}
                </div>
                <div class="flex align-center gap-2">
                    <v-icon icon="mdi-account-multiple"></v-icon>
                    <p class="text-primary text-lg font-bold">{{ event.capacity }}</p>
                </div>
            </div>
        </div>
        <v-divider />
        <h4 class="text-2xl font-bold pt-3">Event details</h4>
        <div>{{ event.description }}</div>
        <h4 class="text-2xl font-bold pt-3">Tickets</h4>
        <!-- <div v-if="event.ticketEvents.length === 0">No tickets</div> -->
        <ticket-list display="grid" @selected-item="($event) => item = $event"></ticket-list>


    </div>
    <div v-else>
        No event found
    </div>
</template>
<script lang="ts" setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useEventStore } from '../stores/event';
import { useTicketStore } from '../stores/tickets';
import TicketList from '@/components/ticket/TicketList.vue';

const route = useRoute();

const eventStore = useEventStore();
const ticketStore = useTicketStore();
const { getEvent } = eventStore;
const { getTicketsEvent } = ticketStore;
const { event } = storeToRefs(eventStore);

const eventId = computed(() => route.params.id.toString());

onMounted(async () => {
    try {
        await getEvent(eventId.value);
        await getTicketsEvent(eventId.value);
    } catch (error) {
        console.error(error)
    }
});


</script>
