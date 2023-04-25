<template>
    <v-card class="py-2 px-4 fixed top-18 right-18 h-8/10 w-150">
        <v-row no-gutters justify="center">
            <v-col cols="12">
                <p class="py-3">My bet</p>
            </v-col>
            <v-col cols="12">
                <div v-if="currentBet" class="custom-div">
                    <v-row no-gutters justify="space-between">
                        <v-col cols="10">
                            <p class="font-weight-bold">
                                {{ constructFullName(currentBet?.fighterA?.firstname, currentBet?.fighterA?.lastname) }} -
                                {{ constructFullName(currentBet?.fighterB?.firstname, currentBet?.fighterB?.lastname) }}
                            </p>
                        </v-col>
                        <v-col cols="auto">
                            <v-icon @click="betStore.$resetCurrentBet">mdi-close</v-icon>
                        </v-col>
                    </v-row>

                    <p>Result of the fight :</p>
                    <p class="font-weight-bold">{{ currentBet?.expectedWinner }}</p>
                    <p>Rating :</p>
                    <p>{{ currentBet?.rating }}</p>
                </div>
                <v-row v-else style="height: 600px" align="center">
                    <v-col cols="12">
                        <div class="text-center">
                            <p class="font-weight-bold">Add your first bet !</p>
                        </div>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import { useBetStore } from '@/stores/bet';
import { storeToRefs } from 'pinia';
import { constructFullName } from '@/service/helpers';
const betStore = useBetStore();
const { currentBet } = storeToRefs(betStore);
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
    padding: 12px;
}
</style>
