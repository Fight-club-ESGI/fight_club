<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div class="px-2 sm:px-10 flex gap-x-2">
            <fighter-filter class="w-80" />

            <div v-if="fighters"
                class="col-span-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
                no-gutters>

                <div v-for="fighter in filteredFighters" :key="fighter.id">
                    <fighter :fighter="fighter"></fighter>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import FighterFilter from '@/components/Fighter/FighterFilter.vue';
import Fighter from '@/components/Fighter/Fighter.vue';
import { onMounted } from 'vue';
import { useFighterStore } from '@/stores/fighter';
import { storeToRefs } from 'pinia';

const fighterStore = useFighterStore();
const { getFighters } = fighterStore;
const { fighters, filteredFighters } = storeToRefs(fighterStore);

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
<style lang=""></style>
