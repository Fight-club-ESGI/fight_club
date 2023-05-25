<template>
    <v-breadcrumbs :items="items"></v-breadcrumbs>
    <div v-for="event in events" class="px-10">
        <div class="text-2xl flex">
            <div>
                Event : {{ event.name }}
            </div>
            <v-spacer />
            <div>
                {{
                    new Date(event.timeStart).toLocaleString('en-GB', {
                        year: 'numeric',
                        month: 'long',
                        day: '2-digit',
                    })
                }}
            </div>
        </div>
        <v-divider :thickness="2" class="border-opacity-100" />
        <div v-if="event.fights.length" class="p-10">
            <v-card v-for="fight in event.fights" class="flex text-white mb-4">
                <div class="flex bg-cover bg-center"
                    :style="fight.fighterA?.imageName ? `background-image: url('${fight.fighterA.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`">
                    <div
                        class="flex flex-column h-full w-full bg-gradient-to-l from-neutral-100 to-transparent items-center p-20" />
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
                    <div class="flex flex-grow-1 h-full items-center pt-3">
                        <div class="w-1/2 flex flex-col flex-grow-1 text-2xl gap-y-5">
                            <p>
                                {{ fight.fighterA.firstname }} {{ fight.fighterA.lastname }}
                            </p>
                            <p>
                                {{ fight.odds.fighterAOdds }}
                            </p>
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
                        <div class="w-1/2 flex flex-col flex-grow-1 text-2xl gap-y-5">
                            <p>
                                {{ fight.fighterB.firstname }} {{ fight.fighterB.lastname }}
                            </p>
                            <p>
                                {{ fight.odds.fighterBOdds }}
                            </p>
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
                    <div v-if="new Date(fight.fightDate) > new Date()" class="mt-2">
                        <create-bet-on-fight :fight="fight" />
                    </div>
                </div>
                <div class="bg-red-100 bg-cover bg-center"
                    :style="fight.fighterB?.imageName ? `background-image: url('${fight.fighterB.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`">
                    <div
                        class="flex flex-column h-full w-full bg-gradient-to-r from-neutral-100 to-transparent items-center p-20 text-white" />
                </div>
            </v-card>
        </div>
        <div v-else class="p-10 text-xl">
            No fight on this event
        </div>
    </div>
</template>

<script setup lang="ts">
import { useEventStore } from "@/stores/event";
import { storeToRefs } from "pinia";
import { onMounted } from "vue";
import { useUserStore } from "@/stores/user";
import CreateBetOnFight from "@/components/dialogs/CreateBetOnFight.vue";
import { Icon } from "@iconify/vue";

const userStore = useUserStore();
const { isAdmin } = storeToRefs(userStore);
const eventStore = useEventStore()
const { getEvents, getEventsAdmin } = eventStore
const { events } = storeToRefs(eventStore)

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Bets'
    }
];

onMounted(async () => {
    try {
        if (isAdmin.value) {
            await getEventsAdmin('after')
        } else {
            await getEvents('after')
        }
    } catch (error) { }
})
</script>

<style scoped></style>