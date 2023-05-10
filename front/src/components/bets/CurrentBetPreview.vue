<template>
    <v-card class="ma-0 pa-0" color="white">
        <v-row no-gutters justify="center">
            <v-row no-gutters justify="space-between" class="z-10 bg-white sticky top-0">
                <v-col cols="auto">
                    <p class="pa-4">My bet ({{ currentBet.bets.length }} selected)</p>
                    <p class="px-4 pb-1" v-if="currentBet.bets.length === 1">Simple bet</p>
                    <p class="px-4 pb-1" v-else-if="currentBet.bets.length > 1">Combined bet</p>
                </v-col>
                <v-col cols="auto">
                    <v-icon class="ma-4" v-if="currentBet.bets.length > 0" @click="betStore.$resetCurrentBet">mdi-delete</v-icon>
                </v-col>
                <v-divider></v-divider>
            </v-row>
            <v-col cols="12">
                <div v-if="currentBet">
                    <div v-for="bet in currentBet.bets.sort((a, b) => a.id - b.id)" class="custom-div">
                        <v-row no-gutters justify="space-between">
                            <v-col cols="10">
                                <p class="font-weight-bold">
                                    {{ constructFullName(bet.fighterA?.firstname, bet.fighterA?.lastname) }} -
                                    {{ constructFullName(bet.fighterB?.firstname, bet.fighterB?.lastname) }}
                                </p>
                            </v-col>
                            <v-col cols="auto">
                                <v-icon @click="removeToCurrentBetStore(bet.id, currentBet)">mdi-close</v-icon>
                            </v-col>
                        </v-row>

                        <p>Result of the fight :</p>
                        <p class="font-weight-bold">{{ bet.expectedWinner }}</p>
                        <v-row no-gutters justify="space-between">
                            <v-col cols="2">
                                <p>Rating</p>
                                <p class="font-weight-bold text-xl">{{ formatNumber(bet.rating) }}</p>
                            </v-col>
                        </v-row>
                    </div>
                </div>
                <v-row v-else style="height: 300px" align="center" no-gutters>
                    <v-col cols="12">
                        <div class="text-center">
                            <p class="font-weight-bold">Add your first bet !</p>
                        </div>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <div class="z-10 bg-white overflow-y-auto sticky bottom-0">
            <v-divider></v-divider>
            <v-row no-gutters justify="space-between" class="pa-2">
                <v-col cols="12">
                    <v-text-field v-if="currentBet.bets.length > 0" label="Bet" type="number" v-model="amount" />
                </v-col>
                <v-col cols="auto">
                    <p class="font-weight-bold">Bet</p>
                    <p class="font-weight-bold">Possible gains</p>
                </v-col>
                <v-col cols="auto">
                    <div class="font-weight-bold">
                        <p v-if="amount && currentBet.bets.length > 0">{{ formatNumber(amount) }} €</p>
                        <p v-else>{{ formatNumber(0) }} €</p>
                        <p v-if="amount && currentBet.bets.length > 0">{{ formatNumber(amount * calculateTotalRating(currentBet)) }} €</p>
                        <p v-else>{{ formatNumber(0) }} €</p>
                    </div>
                </v-col>
                <v-btn :disabled="!amount || amount == 0 || currentBet.bets.length <= 0" block class="my-2">Bet</v-btn>
            </v-row>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useBetStore } from '@/stores/bet';
import { storeToRefs } from 'pinia';
import { formatNumber, constructFullName } from '@/service/helpers';
import { CurrentBetI, FightBetI } from '@/interfaces/payload';

const betStore = useBetStore();
const { currentBet } = storeToRefs(betStore);
const amount = ref<number>(0);

function removeToCurrentBetStore(fightId: string, currentBet: CurrentBetI) {
    const objWithIdIndex = currentBet.bets.findIndex((e: FightBetI) => e.fightId === fightId);
    if (objWithIdIndex > -1) {
        currentBet.bets.splice(objWithIdIndex, 1);
    }
    betStore.$patch((state) => {
        state.currentBet;
    });
}

function calculateTotalRating(currentBet: CurrentBetI) {
    let totalRating = 1;
    currentBet.bets.forEach((element) => {
        totalRating = totalRating * element.rating;
    });
    return totalRating;
}
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
.custom-div {
    border: solid;
    border-width: 1px;
    border-radius: 25px;
    border-color: lightgray;
    padding: 12px 12px 12px 12px;
    margin: 12px;
}
</style>
