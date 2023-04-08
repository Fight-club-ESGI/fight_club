<template>
    <v-container class="flex flex-col gap-4">
        <create-event v-if="isAdmin" />
        <event v-if="isVIP" :events="VIPevents" :admin="isAdmin" />
        <event :events="filteredEvents" :admin="isAdmin" />
    </v-container>
</template>

<script lang="ts">
import { defineComponent, onMounted, computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useEventStore } from '@/stores/event';
import { useRouter } from 'vue-router';
import Event from '@/components/Event.vue';
import { useUserStore } from '@/stores/user';
import CreateEvent from '@/components/dialogs/CreateEvent.vue';

export default defineComponent({
    components: { Event, CreateEvent },
    setup() {
        const router = useRouter();
        const userStore = useUserStore();
        const { isVIP, isAdmin } = storeToRefs(userStore);
        const eventStore = useEventStore();
        const { getEvents } = eventStore;
        const { events } = storeToRefs(eventStore);

        onMounted(async () => {
            try {
                await getEvents();
            } catch (error) {}
        });

        const VIPevents = computed(() => {
            return events.value.filter((event) => event.vip === true);
        });

        const filteredEvents = computed(() => events.value.filter((event) => event.vip === false));

        return { events, router, VIPevents, filteredEvents, isVIP, isAdmin };
    },
});
</script>
