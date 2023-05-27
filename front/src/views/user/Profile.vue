<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>

        <profile />
    </div>
    <!-- <router-view /> -->
</template>

<script lang="ts" setup>
import Profile from '@/components/profile/Profile.vue';

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Profile',
    }
];
</script>
