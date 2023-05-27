<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <Sponsorship></Sponsorship>
    </div>
</template>
<script lang="ts" setup>
import Sponsorship from '@/components/Sponsorship.vue';

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Sponsorship'
    }
];
</script>
