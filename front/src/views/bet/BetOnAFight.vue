<template>
    <v-container>
        <v-row no-gutters justify="space-around">
            <v-col cols="12" lg="6">
                <BetOnAFightCard class="my-4" v-for="fight in fights" :fight="fight" />
            </v-col>
            <v-col cols="12" lg="4">
                <div class="text-center" v-if="$vuetify.display.name === 'xs' || $vuetify.display.name === 'sm' || $vuetify.display.name === 'md'">
                    <v-dialog v-model="showCurrentBetPreview" width="9/10" height="8/10">
                        <template v-slot:activator="{ props }">
                            <v-btn v-bind="props" size="80" rounded="xxl" icon="mdi-book-open" class="ma-4 fixed bottom-0 right-0"></v-btn>
                        </template>

                        <CurrentBetPreview />
                    </v-dialog>
                </div>
                <div v-else>
                    <CurrentBetPreview class="mt-4 fixed top-18 right-18 w-110 h-8/10 overflow-y-auto" />
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import fights from '@/mocks/fights.json';
import BetOnAFightCard from '@/components/bets/BetOnFightCard.vue';
import CurrentBetPreview from '@/components/bets/CurrentBetPreview.vue';
import { ref } from 'vue';
const showCurrentBetPreview = ref<boolean>(false);
</script>
