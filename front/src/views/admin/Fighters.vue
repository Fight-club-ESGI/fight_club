<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div class="px-2 sm:px-10 flex gap-x-2">
            <fighter-filter class="w-80" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 w-full">
                <create-fighter class="flex justify-start pb-2" :admin="isAdmin" />
                <fighter v-for="fighter in filteredFighters" class="w-full" :fighter="fighter" :admin="isAdmin" />
            </div>
        </div>

    </div>
</template>
<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import Fighter from '@/components/Fighter/Fighter.vue';
import CreateFighter from '@/components/dialogs/CreateFighter.vue';
import { useFighterStore } from '@/stores/fighter';
import { storeToRefs } from 'pinia';
import FighterFilter from '@/components/Fighter/FighterFilter.vue';
import { useUserStore } from '@/stores/user';

const fighterStore = useFighterStore();
const userStore = useUserStore();
const { getFighters } = fighterStore;
const { fighters, filteredFighters } = storeToRefs(fighterStore);
const { isAdmin } = storeToRefs(userStore);

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
