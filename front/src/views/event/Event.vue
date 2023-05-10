<template>
    <div>
        <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>
        <div class="flex flex-wrap gap-4 mx-10">
            <create-event v-if="isAdmin && route.path.includes('admin')" class="pb-4" />
            <event
                v-for="event in events"
                :key="event.id"
                :event="event"
                :admin="isAdmin"
            />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEventStore } from '@/stores/event';
import { useRoute } from 'vue-router';
import Event from '@/components/Event.vue';
import { useUserStore } from '@/stores/user';
import CreateEvent from '@/components/dialogs/CreateEvent.vue';

const route = useRoute();
const userStore = useUserStore();
const { isVIP, isAdmin } = storeToRefs(userStore);
const eventStore = useEventStore();
const { getEvents } = eventStore;
const { events } = storeToRefs(eventStore);

onMounted(async () => {
    try {
        await getEvents();
    } catch (error) { }
});

const breadcrumbs = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Events'
    }
];
</script>
