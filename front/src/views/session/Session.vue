<template>
    <div class="h-full w-full flex">
        <div class="h-full w-full hidden md:flex flex items-center bg-no-repeat bg-cover bg-right opacity-75"
            :style="{ backgroundImage: 'url(' + imageUrl + ')' }">
            <div class="text-center w-full font-bold text-6xl px-20 text-white">
                Welcome to Thunderous Knockout
            </div>
        </div>
        <div class="h-full w-full flex items-center text-center bg-neutral-800 text-white">
            <RouterView></RouterView>
        </div>
    </div>
</template>

<script lang="ts" setup>
import imageUrl from '@/assets/johann-walter-bantz-Clv9DfJLwac-unsplash.jpg';
</script>

<style scoped>
.custom-link:link,
.custom-link:visited {
    text-decoration: none;
}
</style>
