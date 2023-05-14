<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>

        <div class="px-10">
            <div class="grid grid-cols-4 gap-4">
                <fighter-filter class="col-span-1 sticky top-[64px]" />
                <div v-if="fighters"
                    class="col-span-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
                    no-gutters>
                    <div v-for="fighter in filteredFighters" :key="fighter.id">
                        <fighter :fighter="fighter"></fighter>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import FighterFilter from '@/components/Fighter/FighterFilter.vue';
import Fighter from '@/components/Fighter/Fighter.vue';
import { defineComponent, onMounted } from 'vue';
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
