<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>

        <v-container>
            <fighter-details />
        </v-container>
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
