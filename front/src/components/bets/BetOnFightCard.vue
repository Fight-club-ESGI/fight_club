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
                            bet(
                                props.fight.id,
                                currentBet,
                                constructFullName(props.fight.fighterA.firstname, props.fight.fighterA.lastname),
                                props.fight.ratingFighterA,
                            )
                        "
                        class="mx-2"
                        :color="
                            isThereABetOnTheFighter(
                                props.fight.id,
                                currentBet,
                                constructFullName(props.fight.fighterA.firstname, props.fight.fighterA.lastname),
                            )
                                ? 'secondary'
                                : 'primary'
                        "
                        :min-height="50"
                        :min-width="140"
                        rounded="false"
                    >
                        <div>
                            <p>{{ props.fight.fighterA.firstname }} {{ props.fight.fighterA.lastname }}</p>
                            <p>{{ formatNumber(props.fight.ratingFighterA) }}</p>
                        </div>
                    </v-btn>
                    <v-btn
                        @click="bet(props.fight.id, currentBet, 'Null', props.fight.ratingNullMatch)"
                        class="mx-2"
                        :color="isThereABetOnTheFighter(props.fight.id, currentBet, 'Null') ? 'secondary' : 'primary'"
                        :min-height="50"
                        :min-width="140"
                        rounded="false"
                    >
                        <div>
                            <p>Null</p>
                            <p>{{ formatNumber(props.fight.ratingNullMatch) }}</p>
                        </div>
                    </v-btn>
                    <v-btn
                        @click="
                            bet(
                                props.fight.id,
                                currentBet,
                                constructFullName(props.fight.fighterB.firstname, props.fight.fighterB.lastname),
                                props.fight.ratingFighterB,
                            )
                        "
                        class="mx-2"
                        :color="
                            isThereABetOnTheFighter(
                                props.fight.id,
                                currentBet,
                                constructFullName(props.fight.fighterB.firstname, props.fight.fighterB.lastname),
                            )
                                ? 'secondary'
                                : 'primary'
                        "
                        :min-height="50"
                        :min-width="140"
                        rounded="false"
                    >
                        <div>
                            <p>{{ props.fight.fighterB.firstname }} {{ props.fight.fighterB.lastname }}</p>
                            <p>{{ formatNumber(props.fight.ratingFighterB) }}</p>
                        </div>
                    </v-btn>
                </div>
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { DateTime } from 'luxon';
import { formatNumber, constructFullName } from '@/service/helpers';
import { useBetStore } from '@/stores/bet';
import { CurrentBetI, FightI, FightBetI } from '@/interfaces/payload';
import { storeToRefs } from 'pinia';

const props = defineProps<{
    fight: FightI;
}>();
const betStore = useBetStore();
const { currentBet } = storeToRefs(betStore);

function isFightAlreadyOnCurrentBet(fightId: string, currentBet: CurrentBetI) {
    return currentBet.bets.find((e: FightBetI) => e.fightId === fightId) !== undefined;
}

function isThereABetOnTheFighter(fightId: string, currentBet: CurrentBetI, expectedWinner: string) {
    return currentBet.bets.find((e: FightBetI) => e.fightId === fightId)?.expectedWinner === expectedWinner;
}

function bet(fightId: string, currentBet: CurrentBetI, expectedWinner: string, rating: number, amount: number = 0) {
    if (isFightAlreadyOnCurrentBet(fightId, currentBet)) {
        if (isThereABetOnTheFighter(fightId, currentBet, expectedWinner)) {
            removeToCurrentBetStore(fightId, currentBet);
        } else {
            removeToCurrentBetStore(fightId, currentBet);
            addBetToCurrentBetStore(expectedWinner, rating, amount);
        }
    } else addBetToCurrentBetStore(expectedWinner, rating, amount);
}

function removeToCurrentBetStore(fightId: string, currentBet: CurrentBetI) {
    const objWithIdIndex = currentBet.bets.findIndex((e: FightBetI) => e.fightId === fightId);
    if (objWithIdIndex > -1) {
        currentBet.bets.splice(objWithIdIndex, 1);
    }
    betStore.$patch((state) => {
        state.currentBet;
    });
}

function addBetToCurrentBetStore(expectedWinner: string, rating: number, amount: number = 0) {
    betStore.$patch((state) => {
        state.currentBet?.bets.push({
            id: props.fight.id,
            fightId: props.fight.id,
            fighterA: props.fight.fighterA,
            fighterB: props.fight.fighterB,
            expectedWinner: expectedWinner,
            rating: rating,
        });
    });
}
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
