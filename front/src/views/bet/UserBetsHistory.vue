<template>
    <div class="flex flex-col h-full">
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div class="h-11/12 mx-20">
            <v-tabs v-model="tab" color="secondary" align-tabs="center">
                <v-tab value="all" class="normal-case">All</v-tab>
                <v-tab value="pending" class="normal-case">Pending</v-tab>
                <v-tab value="won" class="normal-case">Won</v-tab>
            </v-tabs>
            <div class="overflow-auto">
                <v-window v-model="tab">
                    <v-window-item value="all" class="pt-10">
                        <div v-if="loader" class="flex items-center">
                            <v-progress-circular indeterminate class="mx-auto m-20" size="120" ></v-progress-circular>
                        </div>
                        <div v-else>
                            <div v-if="bets.length">
                                <BetCardHistory
                                    class="my-4"
                                    v-for="bet in bets"
                                    :bet="bet"
                                />
                            </div>
                            <div v-else class="flex items-center">
                                <div class="mx-auto m-20 text-2xl font-bold">
                                    No bet available
                                </div>
                            </div>
                        </div>
                    </v-window-item>
                    <v-window-item value="pending" class="pt-10">
                        <div v-if="loader" class="flex items-center">
                            <v-progress-circular indeterminate class="mx-auto m-20" size="120" ></v-progress-circular>
                        </div>
                        <div v-else>
                            <div v-if="bets.length">
                                <BetCardHistory
                                    class="my-4"
                                    v-for="bet in bets"
                                    :bet="bet"
                                />
                            </div>
                            <div v-else class="flex items-center">
                                <div class="mx-auto m-20 text-2xl font-bold">
                                    No pending bets
                                </div>
                            </div>
                        </div>

                    </v-window-item>
                    <v-window-item value="won" class="pt-10">
                        <div v-if="loader" class="flex items-center">
                            <v-progress-circular indeterminate class="mx-auto m-20" size="120" ></v-progress-circular>
                        </div>
                        <div v-else>
                            <div v-if="bets.length">
                                <BetCardHistory
                                    class="my-4"
                                    v-for="bet in bets"
                                    :bet="bet"
                                />
                            </div>
                            <div v-else class="flex items-center text-center">
                                <div class="mx-auto m-20 text-2xl font-bold">
                                    No winner bets
                                </div>
                            </div>
                        </div>
                    </v-window-item>
                </v-window>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {ref, onMounted, watch} from 'vue';
import BetCardHistory from '@/components/bets/BetHistoryCard.vue';
import {useBetStore} from "@/stores/bet";
import {storeToRefs} from "pinia";

const betStore = useBetStore();
const {getUserBets} = betStore;
const {bets} = storeToRefs(betStore);

const tab = ref('all');
const loader = ref(false)

onMounted(async () => {
    await getUserBets("all", "desc");
})

watch(tab, async (value, oldValue, onCleanup) => {
    loader.value = true
    await getUserBets(value, "desc").then(() => {
        loader.value = false
    })
})

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Bets',
    }
];
</script>
