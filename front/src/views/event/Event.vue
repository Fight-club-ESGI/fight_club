<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <v-container class="flex flex-col">
            <create-event v-if="isAdmin && route.path.includes('admin')" class="pb-4" />
            <event v-if="isVIP" :events="VIPevents" :admin="isAdmin" class="pb-4" />
            <event :events="filteredEvents" :admin="isAdmin" />
        </v-container>
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

const VIPevents = computed(() => {
    return events.value.filter((event) => event.vip === true);
});

const filteredEvents = computed(() => events.value.filter((event) => event.vip === false));

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Events'
    }
];
</script>
