<template>
    <v-card class="py-2 px-4">
        <v-row no-gutters justify="space-between">
            <v-row no-gutters justify="space-between">
                <v-col cols="auto">
                    <div class="my-1 font-weight-bold">
                        {{ props.fight.fighterA.firstname }} {{ props.fight.fighterA.lastname }} vs {{ props.fight.fighterB.firstname }}
                        {{ props.fight.fighterB.lastname }}
                    </div>
                </v-col>
                <v-col cols="auto">
                    <p class="text-right text-grey-darken-1 mb-6">
                        {{ DateTime.fromISO(props.fight.endTimestamp, { locale: 'en' }).toFormat('ff') }}
                    </p>
                </v-col>
            </v-row>
            <v-col cols="12">
                <div class="text-center">
                    <v-btn
                        @click="
                            setCurrentBet(
                                constructFullName(props.fight.fighterA.firstname, props.fight.fighterA.lastname),
                                props.fight.ratingFighterA,
                            )
                        "
                        class="mx-2"
                        color="secondary"
                        :min-height="50"
                        :min-width="140"
                        rounded="false"
                    >
                        <div>
                            <p>{{ props.fight.fighterA.firstname }} {{ props.fight.fighterA.lastname }}</p>
                            <p>{{ formatMoney(props.fight.ratingFighterA) }}</p>
                        </div>
                    </v-btn>
                    <v-btn
                        @click="setCurrentBet('Null', props.fight.ratingNullMatch)"
                        class="mx-2"
                        color="secondary"
                        :min-height="50"
                        :min-width="140"
                        rounded="false"
                    >
                        <div>
                            <p>Null</p>
                            <p>{{ formatMoney(props.fight.ratingNullMatch) }}</p>
                        </div>
                    </v-btn>
                    <v-btn
                        @click="
                            setCurrentBet(
                                constructFullName(props.fight.fighterB.firstname, props.fight.fighterB.lastname),
                                props.fight.ratingFighterB,
                            )
                        "
                        class="mx-2"
                        color="secondary"
                        :min-height="50"
                        :min-width="140"
                        rounded="false"
                    >
                        <div>
                            <p>{{ props.fight.fighterB.firstname }} {{ props.fight.fighterB.lastname }}</p>
                            <p>{{ formatMoney(props.fight.ratingFighterB) }}</p>
                        </div>
                    </v-btn>
                </div>
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon';
import { formatMoney, constructFullName } from '@/service/helpers';
import { useBetStore } from '@/stores/bet';
import { FightI } from '@/interfaces/payload';

const props = defineProps<{
    fight: FightI;
}>();
const betStore = useBetStore();

function setCurrentBet(expectedWinner: string, rating: number, amount: number = 0) {
    betStore.$patch((state) => {
        state.currentBet = {
            fighterA: props.fight.fighterA,
            fighterB: props.fight.fighterB,
            expectedWinner: expectedWinner,
            amount: amount,
            rating: rating,
        };
    });
}
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
