<template>
    <div>
        <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>
        <v-switch v-if="isVIP" v-model="vipEvents" color="primary" label="VIP events" class="flex pl-4"></v-switch>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 px-4">
            <create-event v-if="isAdmin && route.path.includes('admin')" class="pb-4" :admin="isAdmin"/>
            <event v-for="event in filteredVipEvents" :key="event.id" :event="event" :admin="isAdmin" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref, computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEventStore } from '@/stores/event';
import { useRoute } from 'vue-router';
import Event from '@/components/Event.vue';
import { useUserStore } from '@/stores/user';
import CreateEvent from '@/components/dialogs/CreateEvent.vue';

const route = useRoute();
const userStore = useUserStore();
const { isAdmin, isVIP } = storeToRefs(userStore);
const eventStore = useEventStore();
const { getEvents } = eventStore;
const { events } = storeToRefs(eventStore);
const vipEvents = ref<boolean>(false)

onMounted(async () => {
    try {
        await getEvents();
    } catch (error) { }
});

const filteredVipEvents = computed(() => {
    return events.value.filter(e => vipEvents.value ? e.vip : e)
})

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
