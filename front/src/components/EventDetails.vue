<template>
    <div v-if="event" class="flex flex-column gap-x-2 pb-3 h-full">
        <div :style="`background-image: url('${event.locationLink}')`" class="h-96 bg-cover bg-center">
            <div
                class="flex flex-column h-full w-full bg-gradient-to-b from-neutral-800 to-transparent items-center p-10 text-white">
                <div class="text-white">
                    <slot name="breadcrumbs" />
                </div>
                <h2 class="text-4xl font-bold mx-auto">{{ event.name }}</h2>
                <div class="w-1/2 overflow-auto">
                    <p class="p-4 break-words text-center">
                        {{ event.description }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <div class="flex items-center gap-2 bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                        <Icon height="20" icon="material-symbols:calendar-month-rounded" />
                        {{
                            new Date(event.timeStart).toLocaleString('en-GB', {
                                year: 'numeric',
                                month: 'long',
                                day: '2-digit',
                            })
                        }}
                        -
                        {{
                            new Date(event.timeEnd).toLocaleString('en-GB', {
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
                        <Icon height="20" icon="material-symbols:location-on" />
                        {{ event.location }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col px-32 py-5">
            <v-divider />
            <h4 class="text-2xl font-bold py-3">Tickets</h4>
            <!-- <div v-if="event.ticketEvents.length === 0">No tickets</div> -->
            <ticket-list display="grid" :event="event" @selected-item="($event) => item = $event"></ticket-list>
            <div class="flex flex-col gap-y-4 py-5">
                <div class="text-2xl font-bold py-3">Planning</div>
                <v-card v-for="fight in event.fights" class="flex text-white">
                    <div class="w-80 bg-cover bg-center"
                        :style="`background-image: url('https://api.multiavatar.com/${fight.fighterA.firstname}${fight.fighterA.lastname}.png?apikey=XdoCH30EA6grGx')`">
                        <div
                            class="flex flex-column h-full w-full bg-gradient-to-l from-neutral-100 to-transparent items-center p-10" />
                    </div>
                    <div class="flex flex-column flex-grow-1 text-center bg-neutral-100 p-2  text-neutral-700">
                        <div class="font-bold">
                            {{
                                new Date(event.timeStart).toLocaleString('en-GB', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                            }}
                        </div>
                        <div class="flex h-full items-center">
                            <div class="flex flex-col flex-grow-1 text-2xl w-1/2 gap-y-5">
                                <p>
                                    {{ fight.fighterA.firstname }} {{ fight.fighterA.lastname }}
                                </p>
                                <p>
                                    {{ fight.odds.fighterAOdds.toFixed(2) }}
                                </p>
                                <div v-if="fight.winnerValidation">
                                    <p v-if="fight.winner.id === fight.fighterA.id" class="text-green-500">WINNER</p>
                                    <p v-else class="text-red-500">LOSER</p>
                                </div>
                                <div class="flex text-white gap-x-2 mx-auto">
                                    <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-md">
                                        <Icon icon="material-symbols:flag" />
                                        <p class="text-sm font-bold">{{ fight.fighterA.nationality }}</p>
                                    </div>
                                    <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-md">
                                        <Icon icon="material-symbols:weight" />
                                        <p class="text-sm font-bold">{{ fight.fighterA.weight }} kg</p>
                                    </div>
                                    <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-md">
                                        <Icon icon="mdi:human-male-height-variant" />
                                        <p class="text-sm font-bold">{{ fight.fighterA.height }} cm</p>
                                    </div>
                                </div>
                            </div>
                            <v-divider :thickness="2" color="secondary" class="border-neutral-700" vertical />
                            <div class="flex flex-col flex-grow-1 text-2xl w-1/2 gap-y-5">
                                <p>
                                    {{ fight.fighterB.firstname }} {{ fight.fighterB.lastname }}
                                </p>
                                <p>
                                    {{ fight.odds.fighterBOdds.toFixed(2) }}
                                </p>
                                <div v-if="fight.winnerValidation">
                                    <p v-if="fight.winnerValidation && fight.winner.id === fight.fighterB.id"
                                        class="text-green-500">WINNER</p>
                                    <p v-else class="text-red-500">LOSER</p>
                                </div>
                                <div class="flex text-white gap-x-2 mx-auto">
                                    <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-md">
                                        <Icon icon="material-symbols:flag" />
                                        <p class="text-sm font-bold">{{ fight.fighterB.nationality }}</p>
                                    </div>
                                    <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-md">
                                        <Icon icon="material-symbols:weight" />
                                        <p class="text-sm font-bold">{{ fight.fighterB.weight }} kg</p>
                                    </div>
                                    <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-md">
                                        <Icon icon="mdi:human-male-height-variant" />
                                        <p class="text-sm font-bold">{{ fight.fighterB.height }} cm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="isConnected && new Date(fight.fightDate) > new Date()" class="mt-2">
                            <create-bet-on-fight :fight="fight" />
                        </div>
                    </div>
                    <div class="bg-red-100 w-80 bg-cover bg-center"
                        :style="`background-image: url('https://api.multiavatar.com/${fight.fighterB.firstname}${fight.fighterB.lastname}.png?apikey=XdoCH30EA6grGx')`">
                        <div
                            class="flex flex-column h-full w-full bg-gradient-to-r from-neutral-100 to-transparent items-center p-10 text-white" />
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
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useEventStore } from '@/stores/event';
import { useTicketStore } from '@/stores/tickets';
import TicketList from '@/components/ticket/TicketList.vue';
import { Icon } from "@iconify/vue";
import CreateBetOnFight from "@/components/dialogs/CreateBetOnFight.vue";
import { useUserStore } from "@/stores/user";

const route = useRoute();
const eventStore = useEventStore();
const ticketStore = useTicketStore();
const userStore = useUserStore();
const { getEvent, getEventAdmin } = eventStore;
const { getTicketsEvent } = ticketStore;
const { event } = storeToRefs(eventStore);
const { isAdmin, isConnected } = storeToRefs(userStore);

const eventId = computed(() => route.params.id.toString());
const imageUrl = ref('');

const eventRandomLandscape = async () => {
    return fetch('https://api.unsplash.com/photos/random?query=landscape&count=1&client_id=h-auINRIAez3dVEu2eNqxOUBVmLfiTKfLIw_dLN38io')
        .then(res => res.json())
        .then(data => imageUrl.value = data[0].urls.full);
}

onMounted(async () => {
    try {
        // await eventRandomLandscape();
        if (isAdmin) {
            await getEventAdmin(eventId.value)
        } else {
            await getEvent(eventId.value)
        }
        await getTicketsEvent(eventId.value);
    } catch (error) {
        console.error(error)
    }
});


</script>
