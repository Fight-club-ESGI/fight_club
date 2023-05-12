<script lang="ts" setup>
import CreateTickets from '@/components/dialogs/CreateTicketsEvent.vue';
import fights from '@/components/admin/figth/fights.vue';
import TicketList from '@/components/admin/tickets/TicketList.vue';
import TicketEventEditing from '@/components/admin/tickets/TicketEventEditing.vue';
import { onMounted, computed, ref } from 'vue';
import { useTicketStore } from '@/stores/tickets';
import { useEventStore } from '@/stores/event';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router'


const route = useRoute();
const ticketStore = useTicketStore();
const eventStore = useEventStore();
const { getTicketCategories, getTicketsEvent } = ticketStore;
const { getEvent } = eventStore;
const { event } = storeToRefs(eventStore);
const item = ref();
const tab = ref<String>('');

const eventId = computed(() => route.params.id.toString());

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Events',
        to: { name: 'event-admin' }
    },
    {
        title: 'Event details'
    }
];

onMounted(async () => {
    try {
        await getEvent(eventId.value)
        await getTicketCategories();
        await getTicketsEvent(eventId.value);
    } catch (error) {
        console.error(error)
    }
});


</script>

<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div class="px-5">
            <v-tabs v-model="tab" align-tabs="center">
                <v-tab value="one" color="primary">Ticket event</v-tab>
                <v-tab value="two" color="primary">
                    <v-icon>mdi-lock-outline</v-icon>
                    Fights
                </v-tab>

            </v-tabs>
            <v-window v-model="tab">
                <v-window-item value="one">
                    <div v-if="event" class="pa-5">
                        <create-tickets v-if="new Date(event.timeStart) > new Date()"></create-tickets>
                        <div v-else class="text-lightgray font-bold">
                            <i>The start date of the event has passed, tickets are in readonly mode</i>
                        </div>
                        <div class="flex gap-x-2 py-2">
                            <ticket-list class="w-1/2" @selected-item="($event) => item = $event"
                                :readOnly="!(new Date(event.timeStart) > new Date())"></ticket-list>
                            <ticket-event-editing :selectedItem="item" class="w-1/2"></ticket-event-editing>
                        </div>
                    </div>
                </v-window-item>

                <v-window-item value="two">
                    <div class="pa-5">
                        <fights></fights>
                    </div>
                </v-window-item>
            </v-window>
        </div>
    </div>
</template>