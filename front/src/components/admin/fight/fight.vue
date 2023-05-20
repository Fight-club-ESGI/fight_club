<script setup lang="ts">
import { IFight } from '@/interfaces/fight';
import { PropType, computed } from 'vue';
import { Icon } from "@iconify/vue/dist/iconify.js";
import { useFightStore } from '@/stores/fight';
import { createToast } from 'mosha-vue-toastify';
import { useEventStore } from '@/stores/event';
import { useRoute } from 'vue-router';
import { ComputedRef } from 'vue';
const fightStore = useFightStore();
const eventStore = useEventStore();
const { getEvent } = eventStore;
const { removeFight, selectWinner, getFights } = fightStore

const props = defineProps({
    fight: {
        type: Object as PropType<IFight>,
        required: true
    },
    readOnly: {
        type: Boolean as PropType<boolean>,
        required: true
    }
});

const route = useRoute();
const eventId: ComputedRef<string> = computed(() => {
    return route.params.id.toString()
})
const passedFightDate = computed(() => {
    return new Date().getTime() > new Date(props.fight.fightDate).getTime();
});

const fullNameFighterA = computed(() => {
    return `${props.fight.fighterA.firstname} ${props.fight.fighterA.lastname}`
})

const fullNameFighterB = computed(() => {
    return `${props.fight.fighterB.firstname} ${props.fight.fighterB.lastname}`
})

const setWinner = async (fighterId: string) => {
    try {
        if (passedFightDate.value && !hasWinner.value) {
            await selectWinner({ fightId: props.fight.id, winnerId: fighterId });
            await getEvent(eventId.value);
        }
    } catch (error) {
        createToast('error while selecting winner', { position: 'bottom-right', type: 'danger' })
    }
}

const hasWinner = computed(() => {
    return props.fight.hasOwnProperty('winner');
});

const winner = computed(() => {
    return props.fight.winner.id;
});

const loser = computed(() => {
    return props.fight.loser.id;
});

const remove = async (id: string) => {
    try {
        await removeFight(id);
    } catch (error) {

    }
}

</script>

<template>
    <div class="gap-y-4">
        <v-card class="flex h-52 text-white">
            <!-- todo: Click pour plus d'infos, ouverture d'une modale -->
            <div class="h-52 w-80 bg-cover bg-center"
                :style="fight.fighterA.imageName ? `background-image: url('${fight.fighterA.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`">
                <div
                    class="flex flex-column h-full w-full bg-gradient-to-l from-neutral-100 to-transparent items-center p-10" />
            </div>
            <div class="flex flex-column flex-grow-1 text-center bg-neutral-100 p-2  text-neutral-700">
                <div v-if="passedFightDate && !hasWinner">
                    <div>Passed date</div>
                    <div>Select a winner</div>
                </div>
                <div v-else-if="passedFightDate && hasWinner">
                    <div>Passed date</div>
                </div>
                <div v-else class="font-bold">
                    {{
                        new Date(fight.fightDate).toLocaleDateString('en-GB', {
                            year: 'numeric',
                            month: 'long',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                        })
                    }}

                </div>
                <div class="flex h-full items-center">
                    <div @click="setWinner(fight.fighterA.id)"
                        class="group relative flex flex-col flex-grow-1 text-2xl w-1/2">
                        <div
                            :class="'flex flex-col gap-y-5' + (passedFightDate && !hasWinner ? ' z-10 cursor-pointer hover:opacity-20' : ' ')">
                            <p>
                                {{ fight.fighterA.firstname }} {{ fight.fighterA.lastname }}
                            </p>
                            <p v-if="hasWinner && winner === fight.fighterA.id" class="text-green-400 font-bold text-lg">
                                WINNER
                            </p>
                            <p v-else class="text-red-400 font-bold text-lg">
                                LOSER
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
                        <div v-if="passedFightDate && !hasWinner"
                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100">
                            WINNER
                        </div>
                    </div>
                    <v-divider :thickness="2" color="secondary" class="border-neutral-700" vertical />
                    <div @click="setWinner(fight.fighterB.id)"
                        class="group relative flex flex-col flex-grow-1 text-2xl w-1/2 ">
                        <div
                            :class="'flex flex-col gap-y-5' + (passedFightDate && !hasWinner ? ' z-10 cursor-pointer hover:opacity-20' : ' ')">
                            <p>
                                {{ fight.fighterB.firstname }} {{ fight.fighterB.lastname }}
                            </p>
                            <p v-if="hasWinner && winner === fight.fighterB.id" class="text-green-400 font-bold text-lg">
                                WINNER
                            </p>
                            <p v-else class="text-red-400 font-bold text-lg">
                                LOSER
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
                        <div v-if="passedFightDate && !hasWinner"
                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100">
                            WINNER
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <create-bet-on-fight :fight="fight" />
                </div>
            </div>
            <div class="bg-red-100 h-52 w-80 bg-cover bg-center"
                :style="fight.fighterB.imageName ? `background-image: url('${fight.fighterB.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`">
                <div
                    class="flex flex-column h-full w-full bg-gradient-to-r from-neutral-100 to-transparent items-center p-10 text-white" />
            </div>
        </v-card>
    </div>
    <!-- <v-card class="pa-4">
        <div class="flex justify-center flex-col">
            <div class="flex justify-around gap-x-3 pb-3">
                <div class="rounded-xl pa-2 elevation-2">

                    <v-img height="50" width="50"
                        src="https://upload.wikimedia.org/wikipedia/commons/9/95/Vue.js_Logo_2.svg"></v-img>
                </div>
                <div class="border rounded-xl pa-2 elevation-2">
                    <v-img height="50" width="50"
                        src="https://upload.wikimedia.org/wikipedia/commons/a/ae/Nuxt_logo.svg"></v-img>
                </div>
            </div>
            <div class="text-center py-2">
                <p class="text-lg font-semibold">{{ fullNameFighterA }}</p>
                <p>VS</p>
                <p class="text-lg font-semibold">{{ fullNameFighterB }}</p>
            </div>
            <div>

                <v-card-actions>
                    <update-fight :fight-id="props.fight.id" :disabled="readOnly"></update-fight>
                    <v-btn @click="remove(fight.id)" :disabled="readOnly" color="primary" variant="tonal"
                        class="elevation-2 flex-1">
                        <Icon icon="material-symbols:delete-rounded" height="1rem" />
                    </v-btn>
                </v-card-actions>
            </div>
        </div>
    </v-card> -->
</template>