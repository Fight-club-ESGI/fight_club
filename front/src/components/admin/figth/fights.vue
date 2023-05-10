<script setup lang="ts">
import fight from './fight.vue';
import { onMounted } from 'vue';
import { useFightStore } from '@/stores/fight';
import { storeToRefs } from 'pinia';
const fightStore = useFightStore();
const { createFight, getFights } = fightStore;
const { fights } = storeToRefs(fightStore);

onMounted(async () => {
    try {
        await getFights();
    } catch (err) {
        console.error(err)
    }
});

</script>

<template>
    <div class="flex flex-col gap-y-4">
        <div v-for="fight of fights">
            <fight :fight="fight"></fight>
        </div>
    </div>
</template>