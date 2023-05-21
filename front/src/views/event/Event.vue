<template>
    <div>
        <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>
        <v-switch v-if="isVIP" v-model="vipEvents" color="primary" label="VIP events" class="flex pl-4"></v-switch>
        <v-tabs v-model="tab" color="secondary" align-tabs="center">
            <v-tab :value="1">Current</v-tab>
            <v-tab :value="2">Passed</v-tab>
        </v-tabs>
        <v-window v-model="tab">
            <v-window-item v-for="n in 2" :key="n" :value="n">
                <div v-if="loader" class="flex items-center">
                    <v-progress-circular indeterminate class="mx-auto m-20" size="120"></v-progress-circular>
                </div>
                <div v-else>
                    <div v-if="events.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 p-4">
                        <create-event v-if="isAdmin && route.path.includes('admin')" class="pb-4" :admin="isAdmin" />
                        <event v-for="event in filteredVipEvents" :key="event.id" :event="event" :admin="isAdmin" />
                    </div>
                    <div v-else class="flex items-center text-center">
                        <p class="w-full text-2xl m-4">
                            No events available
                        </p>
                    </div>
                </div>
            </v-window-item>
        </v-window>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref, computed, watch } from 'vue';
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
const { getEvents, getEventsAdmin } = eventStore;
const { events } = storeToRefs(eventStore);
const vipEvents = ref<boolean>(false)

const tab = ref(1)
const datePeriod = ref("before")
const loader = ref(true)

onMounted(async () => {
    try {
        if (isAdmin.value) {
            await getEventsAdmin('after').then(() => {
                loader.value = false
            })
        } else {
            await getEvents('after').then(() => {
                loader.value = false
            })
        }
    } catch (error) { }
});

watch(tab, async (value, oldValue, onCleanup) => {
    loader.value = true
    switch (value) {
        case 1:
            datePeriod.value = "after"
            break;
        case 2:
            datePeriod.value = "before"
            break;
    }

    if (isAdmin.value) {
        await getEventsAdmin(datePeriod.value ?? "after").then(() => {
            loader.value = false
        })
    } else {
        await getEvents(datePeriod.value ?? "after").then(() => {
            loader.value = false
        })
    }

})

const filteredVipEvents = computed(() => {
    return events.value.filter(e => vipEvents.value ? e.vip : e).sort(function (a, b) {
        // @ts-ignore
        return new Date(a.timeStart) - new Date(b.timeStart)
    })
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
