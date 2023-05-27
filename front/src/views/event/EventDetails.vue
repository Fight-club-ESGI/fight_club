<template>
    <div class="h-full">
        <event-details>
            <template v-slot:breadcrumbs>
                <v-breadcrumbs :items="items" />
            </template>
        </event-details>
    </div>
</template>
<script lang="ts" setup>
import EventDetails from '@/components/EventDetails.vue';
const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Events',
        to: { name: 'events' }
    },
    {
        title: 'Event details',
    }
];
</script>
