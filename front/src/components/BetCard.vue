<template>
    <v-card class="py-2 px-4">
        <v-row no-gutters justify="space-between">
            <v-col cols="auto">
                <div class="my-1 font-weight-bold text-xl">
                    {{ bet.figher1 }}
                </div>
                <div class="my-1 font-weight-bold text-xl">
                    {{ bet.figher2 }}
                </div>
            </v-col>
            <v-col cols="4">
                <p class="text-right text-grey-darken-1">
                    {{ DateTime.fromISO(bet.eventDate).toFormat('ff') }}
                </p>
            </v-col>
        </v-row>
        <div v-if="bet.status === 'pending'">
            <v-row no-gutters justify="space-between" align="center" class="custom-result mt-4 mb-2 py-3 px-4">
                <v-col cols="auto">
                    <div>
                        Result of the fight
                        <span class="font-weight-bold text-xl">{{ bet.selectedWinner }}</span>
                    </div>
                </v-col>
                <v-col cols="2">
                    <p class="custom-rating text-center">{{ bet.rating }}</p>
                </v-col>
            </v-row>
            <v-row no-gutters justify="space-between" align="center">
                <v-col cols="auto">
                    <p class="my-2">Bet</p>
                    <p class="my-2">Possible gains</p>
                </v-col>
                <v-col cols="2">
                    <p class="my-2 text-center">{{ bet.betAmount }} €</p>
                    <p class="my-2 text-center font-weight-bold text-xl">{{ bet.possibleGain }} €</p>
                </v-col>
            </v-row>
        </div>
        <div v-if="bet.status === 'finished'">
            <v-row no-gutters justify="space-between" align="center" class="custom-result mt-4 mb-2 py-3 px-4">
                <v-col cols="auto">
                    <div class="text-sm">Winner of the fight</div>
                    <div class="custom-bg-primary my-1 py-1 px-3 text-white font-weight-bold text-xl">{{ bet.winner }}</div>
                </v-col>
                <v-col cols="2">
                    <p class="text-center font-weight-bold text-md">{{ bet.rating }}</p>
                </v-col>
            </v-row>
            <v-row no-gutters justify="space-between" align="center">
                <v-col cols="auto">
                    <p class="my-2">Bet</p>
                    <p class="my-2">Possible gains</p>
                </v-col>
                <v-col cols="2">
                    <p class="my-2 text-center">{{ bet.betAmount }} €</p>
                    <p class="my-2 text-center font-weight-bold text-xl">{{ bet.possibleGain }} €</p>
                </v-col>
                <v-col cols="2 offset-10">
                    <div class="custom-lost text-center my-1 pa-1 text-white font-weight-bold text-xl">LOST</div>
                </v-col>
            </v-row>
        </div>
        <div v-if="bet.status === 'won'">
            <v-row no-gutters justify="space-between" align="center" class="custom-result mt-4 mb-2 py-3 px-4">
                <v-col cols="auto">
                    <div class="text-sm">Winner of the fight</div>
                    <div class="custom-bg-success my-1 py-1 px-3 text-white font-weight-bold text-xl">{{ bet.winner }}</div>
                </v-col>
                <v-col cols="2">
                    <p class="text-center font-weight-bold text-md">{{ bet.rating }}</p>
                </v-col>
            </v-row>
            <v-row no-gutters justify="space-between" align="center">
                <v-col cols="auto">
                    <p class="my-2">Bet</p>
                    <p class="my-2">Gains</p>
                </v-col>
                <v-col cols="2">
                    <p class="my-2 text-center">{{ bet.betAmount }} €</p>
                    <p class="my-2 text-center font-weight-bold text-xl">{{ bet.betAmount + bet.possibleGain }} €</p>
                </v-col>
                <v-col cols="2 offset-10">
                    <div class="custom-won text-center my-1 pa-1 text-white font-weight-bold text-xl">WON</div>
                </v-col>
            </v-row>
        </div>
        <v-divider class="my-2"></v-divider>
        <p class="mb-1 text-grey-darken-1">Ref {{ bet.ref }} - {{ DateTime.fromISO(bet.betDate).toFormat('ff') }}</p>
    </v-card>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon';
defineProps({
    bet: {
        type: Object,
        default(rawProps) {
            return { message: 'hello' };
        },
    },
});
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
.custom-rating {
    color: white;
    background: rgb(var(--v-theme-primary));
    border-radius: 5px;
}
.custom-result {
    background: rgb(var(--v-theme-lightgray));
    border-radius: 5px;
}
.custom-bg-success {
    background: rgb(var(--v-theme-success));
}
.custom-bg-primary {
    background: rgb(var(--v-theme-primary));
}
.custom-won {
    background: rgb(var(--v-theme-success));
    border-radius: 20px 5px 20px;
}
.custom-lost {
    background: rgb(var(--v-theme-primary));
    border-radius: 20px 5px 20px;
}
</style>
