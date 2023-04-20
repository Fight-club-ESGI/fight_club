<template>
    <v-card class="py-2 px-4">
        <v-row no-gutters justify="space-between">
            <p class="font-weight-bold">My bet ( 1 selection )</p>
            {{ currentBet. }}
            <!-- <v-col cols="auto">
                <div class="my-1 font-weight-bold">
                    {{ formatMoneyOrRating(event.fight.ratingFighterA) }}
                </div>
            </v-col> -->
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import { formatMoneyOrRating } from '@/service/helpers';
import { useBetStore } from '../../stores/bet';
import { storeToRefs } from 'pinia';
defineProps({
    event: {
        type: Object,
        default(rawProps) {
            return { message: 'hello' };
        },
    },
});
const betStore = useBetStore();
const { currentBet } = storeToRefs(betStore);
const { betDirect } = betStore;
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
