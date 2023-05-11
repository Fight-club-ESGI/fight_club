<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div class="px-2 sm:px-10">

            <v-tabs v-model="tab" color="primary" align-tabs="center">
                <v-tab value="fighters">Fighters</v-tab>
                <v-tab value="category">Category</v-tab>
            </v-tabs>

            <v-window v-model="tab">
                <v-window-item value="fighters">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-3">
                        <div class="col-span-1">
                            <create-fighter class="flex justify-start pb-2" />
                            <fighter-filter class="sticky top-[64px]" />
                        </div>
                        <div v-for="fighter in filteredFighters">
                            <fighter :fighter="fighter" :isAdmin="true" class="w-full" />
                        </div>
                    </div>
                </v-window-item>

                <v-window-item value="category"> <fighter-weight-category></fighter-weight-category> </v-window-item>
            </v-window>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { defineComponent, onMounted, ref, computed, isRef } from 'vue';
import Fighter from '@/components/Fighter/Fighter.vue';
import CreateFighter from '@/components/dialogs/CreateFighter.vue';
import { useFighterStore } from '@/stores/fighter';
import { storeToRefs } from 'pinia';
import FighterFilter from '@/components/Fighter/FighterFilter.vue';
import FighterWeightCategory from '@/components/Fighter/FighterWeightCategory.vue';


const fighterStore = useFighterStore();
const { getFighters } = fighterStore;
const { fighters, filteredFighters } = storeToRefs(fighterStore);

const tab = ref();

onMounted(async () => {
    try {
        await getFighters();
    } catch (error) { }
});

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Fighters',
    }
];
</script>
