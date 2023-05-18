<template>
    <div
        v-if="event"
        class="flex flex-column gap-x-2 pb-3 h-full"
    >
        <div
            :style="event.imageName ? `background-image: url('${event.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`"
            class="h-96 bg-cover bg-center"
        >
            <div class="flex flex-column h-full w-full bg-gradient-to-b from-neutral-800 to-transparent items-center p-10 text-white">
                <div class="text-white">
                    <slot name="breadcrumbs"/>
                </div>
                <h2 class="text-4xl font-bold mx-auto">{{ event.name }}</h2>
                <div class="w-1/2 overflow-auto">
                    <p class="p-4 break-words text-center">{{ event.description }}eazeazeazeazeazeazeazezeaazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazekkkkkkkkkkkkkkkkkkkkazeazeazeaeazeazzeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeaz</p>
                </div>
                <div class="flex gap-3">
                    <div class="flex items-center gap-2 bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                        <Icon
                            height="20"
                            icon="material-symbols:calendar-month-rounded"
                        />
                        {{
                            new Date(event.timeStart).toLocaleString('en-GB', {
                                year: 'numeric',
                                month: 'long',
                                day: '2-digit',
                            })
                        }}
                    </div>
                    <div class="flex items-center gap-2 bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                        <v-icon icon="mdi-account-multiple"></v-icon>
                        <p class="text-lg font-bold">{{ event.capacity }}</p>
                    </div>
                    <div class="flex items-center gap-2 bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                        <Icon
                            height="20"
                            icon="material-symbols:location-on"
                        />
                        {{ event.location }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col px-32">
            <v-divider />
            <h4 class="text-2xl font-bold pt-3">Tickets</h4>
            <!-- <div v-if="event.ticketEvents.length === 0">No tickets</div> -->
            <ticket-list display="grid" @selected-item="($event) => item = $event"></ticket-list>
            <div class="gap-y-4">
                <div class="text-2xl font-bold py-3 underline ">Planning</div>
                <v-card
                    v-for="fight in event.fights"
                    class="flex h-52 text-white"
                >
                    <!-- todo: Click pour plus d'infos, ouverture d'une modale -->
                    <div class="h-52 w-80 bg-cover bg-center"
                         :style="fight.fighterA.imageName ? `background-image: url('${fight.fighterA.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`"
                    >
                        <div class="flex flex-column h-full w-full bg-gradient-to-l from-neutral-100 to-transparent items-center p-10" />
                    </div>
                    <div class="flex flex-column flex-grow-1 text-center bg-neutral-100 p-2  text-neutral-700">
                        <div class="font-bold">
                            18:00 - 20:00
                        </div>
                        <div class="flex h-full items-center">
                            <div class="flex flex-col flex-grow-1 text-2xl w-1/2 gap-y-5">
                                <p>
                                    {{ fight.fighterA.firstname }} {{ fight.fighterA.lastname }}
                                </p>
                                <p>
                                    {{ fight.odds.fighterAOdds }}
                                </p>
                            </div>
                            <v-divider
                                :thickness="2"
                                color="secondary"
                                class="border-neutral-700"
                                vertical
                            />
                            <div class="flex flex-col flex-grow-1 text-2xl w-1/2 gap-y-5">
                                <p>
                                    {{ fight.fighterB.firstname }} {{ fight.fighterB.lastname }}
                                </p>
                                <p>
                                    {{ fight.odds.fighterBOdds }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <create-bet-on-fight />
                        </div>
                    </div>
                    <div class="bg-red-100 h-52 w-80 bg-cover bg-center"
                         :style="fight.fighterB.imageName ? `background-image: url('${fight.fighterB.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`"
                    >
                        <div class="flex flex-column h-full w-full bg-gradient-to-r from-neutral-100 to-transparent items-center p-10 text-white" />
                    </div>
                </v-card>
            </div>
        </div>
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
import {Icon} from "@iconify/vue";
import CreateBetOnFight from "@/components/dialogs/CreateBetOnFight.vue";

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
