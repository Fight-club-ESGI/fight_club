<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <fighter-details />
    </div>
</template>

<script lang="ts" setup>
import FighterDetails from '@/components/Fighter/FighterDetails.vue';

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Fighters',
        to: { name: 'fighters' }
    },
    {
        title: 'Fighters details',
    }
];
</script>
