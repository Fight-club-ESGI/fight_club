<template>
    <v-container>
        <v-tabs v-model="tab" color="primary" align-tabs="center">
            <v-tab value="pending"> Pending </v-tab>
            <v-tab value="finished"> Finished </v-tab>
            <v-tab value="won"> Won </v-tab>
        </v-tabs>
        <v-window v-model="tab">
            <v-window-item value="pending" class="pt-10">
                <v-row v-if="pendingBets.length !== 0" align="center" justify="center">
                    <v-col cols="12" lg="5">
                        <BetCard class="my-4" v-for="bet in pendingBets" :bet="bet" />
                    </v-col>
                </v-row>
                <v-row v-if="pendingBets.length === 0" style="height: 600px" align="center">
                    <v-col cols="12">
                        <div class="text-center">
                            <v-icon color="orange" size="40" class="my-2">mdi-clover</v-icon>
                            <p class="my-2 font-weight-bold">Add your first bet!</p>
                            <p class="my-2">You don't have any current bets. Try your luck and bet on a fight!</p>
                            <v-btn class="my-2">See the fights</v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-window-item>
            <v-window-item value="finished" class="pt-10">
                <v-row v-if="pendingBets.length !== 0" align="center" justify="center">
                    <v-col cols="12" lg="5">
                        <BetCard class="my-4" v-for="bet in finishedBets" :bet="bet" />
                    </v-col>
                </v-row>
                <v-row v-if="finishedBets.length === 0" style="height: 600px" align="center">
                    <v-col cols="12">
                        <div class="text-center">
                            <v-icon color="orange" size="40" class="my-2">mdi-clover</v-icon>
                            <p class="my-2 font-weight-bold">Add your first bet!</p>
                            <p class="my-2">You don't have any finished bets. Try your luck and bet on a fight!</p>
                            <v-btn class="my-2">See the fights</v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-window-item>
            <v-window-item value="won" class="pt-10">
                <v-row v-if="pendingBets.length !== 0" align="center" justify="center">
                    <v-col cols="12" lg="5">
                        <BetCard class="my-4" v-for="bet in wonBets" :bet="bet" />
                    </v-col>
                </v-row>
                <v-row v-if="wonBets.length === 0" style="height: 600px" align="center">
                    <v-col cols="12">
                        <div class="text-center">
                            <v-icon color="orange" size="40" class="my-2">mdi-clover</v-icon>
                            <p class="my-2 font-weight-bold">Add your first bet!</p>
                            <p class="my-2">You don't have any won bets. Try your luck and bet on a fight!</p>
                            <v-btn class="my-2">See the fights</v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-window-item>
        </v-window>
    </v-container>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import BetCard from '@/components/BetCard.vue';
import bets from '@/mocks/bets.json';
const tab = ref();
const pendingBets = computed(() => {
    return bets.filter((e) => e.status === 'pending');
});
const finishedBets = computed(() => {
    return bets.filter((e) => e.status === 'finished');
});
const wonBets = computed(() => {
    return bets.filter((e) => e.status === 'won');
});
</script>
