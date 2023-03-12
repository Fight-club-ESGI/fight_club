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
                    {{ bet.date }}
                </p>
            </v-col>
        </v-row>
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
        <v-divider class="my-2"></v-divider>
        <p class="mb-1 text-grey-darken-1">Réf 112E75872ZE1571 - 17/02/2023 12:17</p>
    </v-card>
</template>

<script setup lang="ts">
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
</style>
