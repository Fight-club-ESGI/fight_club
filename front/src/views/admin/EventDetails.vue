<script lang="ts" setup>
import CreateTickets from '@/components/dialogs/CreateTicketsEvent.vue';
import fights from '@/components/admin/fight/fights.vue';
import TicketList from '@/components/admin/tickets/TicketList.vue';
import TicketEventEditing from '@/components/admin/tickets/TicketEventEditing.vue';
import { onMounted, computed, ref, reactive } from 'vue';
import { useTicketStore } from '@/stores/tickets';
import { useEventStore } from '@/stores/event';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router'
import { useUserStore } from "@/stores/user";

const route = useRoute();
const ticketStore = useTicketStore();
const eventStore = useEventStore();
const userStore = useUserStore();
const { getTicketCategories, getTicketsEvent } = ticketStore;
const { getEvent, getEventAdmin } = eventStore;
const { event } = storeToRefs(eventStore);
const { isAdmin } = storeToRefs(userStore);
const item = ref();
const tab = ref<String>('');

const eventId = computed(() => route.params.id.toString());

const items = computed(() => [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Events',
        to: { name: 'event-admin' }
    },
    {
        title: event.value?.name
    }
]);

onMounted(async () => {
    try {
        if (isAdmin) {
            await getEventAdmin(eventId.value)
        } else {
            await getEvent(eventId.value)
        }
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
                <v-tab value="one" color="primary">Fights</v-tab>
                <v-tab value="two" color="primary">
                    Tickets event
                </v-tab>

            </v-tabs>
            <v-window v-model="tab">
                <v-window-item value="one">
                    <div class="pa-5">
                        <fights></fights>
                    </div>
                </v-window-item>

                <v-window-item value="two">
                    <div v-if="event" class="pa-5">
                        <create-tickets v-if="new Date(event.timeStart) > new Date()" :event="event"></create-tickets>
                        <div v-else class="text-lightgray font-bold">
                            <i>The start date of the event has passed, tickets are in readonly mode</i>
                        </div>
                        <div class="flex gap-x-2 py-2">
                            <ticket-list class="w-4/5" @selected-item="(ticketEvent) => item = ticketEvent"
                                :readOnly="!(new Date(event.timeStart) > new Date())"></ticket-list>

                            <ticket-event-editing :selectedItem="item" class="w-1/5"
                                @reset-selection="item = null"></ticket-event-editing>
                        </div>
                    </div>
                </v-window-item>
            </v-window>
        </div>
    </div>
</template>