<template>
    <div v-if="event">
        <div class="flex gap-x-2 pb-3">
            <div>
                <v-img
                    :src="event.locationLink ? event.locationLink : 'https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80'"
                    aspect-ratio="16/9" width="300" class="rounded-xl" />
            </div>
            <div class="flex flex-col">
                <h2 class="text-3xl font-extrabold">{{ event.name }}</h2>
                <div><i>{{ event.location }}</i></div>
                <div class="">
                    {{
                        new Date(event.timeEnd).toLocaleString('en-GB', {
                            year: 'numeric',
                            month: 'long',
                            day: '2-digit',
                        })
                    }}
                </div>
                <div class="flex align-center gap-2">
                    <v-icon icon="mdi-account-multiple"></v-icon>
                    <p class="text-primary text-lg font-bold">{{ event.capacity }}</p>
                </div>
                <v-btn color="primary" class="mt-auto">Buy tickets</v-btn>
            </div>
        </div>
        <v-divider />
        <h4 class="text-2xl font-bold pt-3">Event details</h4>
        <div>{{ event.description }}</div>
    </div>
    <div v-else>
        No event found
    </div>
</template>
<script lang="ts" setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useEventStore } from '../stores/event';

const route = useRoute();

const eventStore = useEventStore();
const { getEvent } = eventStore;
const { event } = storeToRefs(eventStore);

const eventId = computed(() => route.params.id.toString());

onMounted(async () => {
    try {
        await getEvent(eventId.value);
    } catch (error) {
        console.error(error)
    }
});


</script>
