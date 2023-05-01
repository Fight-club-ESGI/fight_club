<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>

        <v-container>
            <EventDetails></EventDetails>
        </v-container>
    </div>
</template>
<script lang="ts" setup>

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
