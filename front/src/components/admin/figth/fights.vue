<script setup lang="ts">
import CreateFight from '@/components/dialogs/CreateFight.vue';
import fight from './fight.vue';
import { onMounted } from 'vue';
import { useFightStore } from '@/stores/fight';
import { useEventStore } from '@/stores/event';
import { storeToRefs } from 'pinia';
const fightStore = useFightStore();
const { createFight, getFights } = fightStore;
const eventStore = useEventStore();
const { fights } = storeToRefs(eventStore);

onMounted(async () => {
    try {
        await getFights();
    } catch (err) {
        console.error(err)
    }
});

</script>

<template>
    <div>
        <create-fight></create-fight>
        <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            <div v-for="fight of fights">
                <fight :fight="fight"></fight>
            </div>
        </div>
    </div>
</template>