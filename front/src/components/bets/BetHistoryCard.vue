<template>
    <div class="bg-white rounded my-4">
        <div class="flex p-3 items-center">
            <div v-if="bet.status === 'win'" class="p-2 w-20 text-center bg-green-500 rounded text-white mr-3 font-bold">
                Win
            </div>
            <div v-else-if="bet.status === 'lose'" class="p-2 w-20 text-center bg-red-500 rounded text-white mr-3">
                Lose
            </div>
            <div v-else-if="bet.status === 'pending'" class="p-2 w-20 text-center bg-yellow-500 rounded text-white mr-3">
                Pending
            </div>
            <div class="text-neutral-400">
                Event {{ bet.fight.event.name }}
            </div>
            <v-spacer />
            <div>
                {{
                    new Date(bet.fight.event.timeStart).toLocaleString('en-GB', {
                        year: 'numeric',
                        month: 'long',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                }}
            </div>
        </div>
        <v-divider />
        <div class="flex p-3 space-x-5">
            <div class="w-full rounded relative"
                :class="bet.fight.fighterA.id === bet.betOn.id ? 'bg-secondary' : 'bg-neutral-200'"
            >
                <span class="absolute right-0 p-2"
                      v-if="bet.fight.fighterA.id === bet.betOn.id"
                >
                    <Icon height="30" icon="material-symbols:check-small-rounded" />
                </span>
                <div class="p-5 text-center">
                    {{ bet.fight.fighterA.firstname }} {{ bet.fight.fighterA.lastname }}
                </div>
                <v-divider />
                <div class="p-3 text-center">
                    {{ bet.fight.odds.fighterAOdds.toFixed(2) }}
                </div>
            </div>
            <div class="w-full rounded relative"
                 :class="bet.fight.fighterB.id === bet.betOn.id ? 'bg-secondary' : 'bg-neutral-200'"
            >
                <span
                    v-if="bet.fight.fighterB.id === bet.betOn.id"
                    class="absolute right-0 p-2">
                    <Icon height="30" icon="material-symbols:check-small-rounded" />
                </span>
                <div class="p-5 text-center font-bold">
                    {{ bet.fight.fighterB.firstname }} {{ bet.fight.fighterB.lastname }}
                </div>
                <v-divider />
                <div class="p-3 text-center">
                    {{ bet.fight.odds.fighterBOdds.toFixed(2) }}
                </div>
            </div>
        </div>
        <div class="flex p-3 space-x-5">
            <div class="w-full text-center bg-secondary rounded">
                <p class="p-3">Bet</p>
                <v-divider />
                <p class="p-3">{{ bet.amount }} €</p>
            </div>
            <div class="w-full text-center bg-secondary rounded">
                <p class="p-3">Possible Gain</p>
                <v-divider />
                <p class="p-3 font-bold text-xl">
                    {{
                        bet.betOn.id === bet.fight.fighterA.id ? (bet.fight.odds.fighterAOdds * bet.amount - bet.amount).toFixed(2) :
                        bet.betOn.id ===  bet.fight.fighterB.id ? (bet.fight.odds.fighterBOdds * bet.amount - bet.amount).toFixed(2) :
                            0
                    }} €
                </p>
            </div>
        </div>
        <div class="p-3 text-neutral-400 flex items-center">
            <span>
                Bet Date -
                {{
                    new Date(bet.createdAt).toLocaleString('en-GB', {
                        year: 'numeric',
                        month: 'long',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                }}
            </span>
            <v-spacer />
            <span>Reference - {{ bet.id }}</span>
            <v-btn icon size="30"
                class="rounded text-neutral-400 hover:bg-neutral-200 active:bg-neutral-400 active:text-white" color="white">
                <Icon icon="material-symbols:content-copy-outline-rounded" />
            </v-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from "@iconify/vue/dist/iconify.js";
import {PropType} from "vue";
import {IBet} from "@/interfaces/bet";

const props = defineProps({
    bet: {
        type: Object as PropType<IBet>,
        required: true
    },
});
</script>
