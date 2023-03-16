<template>
    <profile />
    <!-- <router-view /> -->
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Profile from '@/components/profile/Profile.vue';
export default defineComponent({
    components: { Profile },
    setup() {
        return {};
    },
});
</script>
