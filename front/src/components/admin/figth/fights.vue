<script setup lang="ts">
import CreateFight from '@/components/dialogs/CreateFight.vue';
import fight from './fight.vue';
import { onMounted } from 'vue';
import { useFightStore } from '@/stores/fight';
import { useEventStore } from '@/stores/event';
import { storeToRefs } from 'pinia';
const fightStore = useFightStore();
const { createFight, getFights } = fightStore;
const { fights } = storeToRefs(fightStore);
const eventStore = useEventStore();
const { event } = storeToRefs(eventStore);

onMounted(async () => {
    try {
        await getFights();
    } catch (err) {
        console.error(err)
    }
});

</script>

<template>
    <div v-if="event">
        <create-fight v-if="new Date(event.timeStart) > new Date()"></create-fight>
        <div v-else class="text-lightgray font-bold">
            <i>The start date of the event has passed, fights are in readonly mode</i>
        </div>
        <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            <div v-for="fight of fights">
                <fight :fight="fight" :readOnly="!(new Date(event.timeStart) > new Date())"></fight>
            </div>
        </div>
    </div>
</template>