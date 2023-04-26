<template>
    <v-container>
        <v-row no-gutters justify="space-around">
            <v-col cols="6">
                <BetOnAFightCard class="my-4" v-for="fight in fights" :fight="fight" />
            </v-col>
            <v-col cols="4">
                <CurrentBetPreview class="mt-4 fixed top-18 right-18 w-120 h-8/10 overflow-y-auto" />
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import fights from '@/mocks/fights.json';
import BetOnAFightCard from '@/components/bets/BetOnFightCard.vue';
import CurrentBetPreview from '@/components/bets/CurrentBetPreview.vue';
</script>
