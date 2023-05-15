<template>
    <div class="bg-white rounded my-4">
        <div class="flex p-3 items-center">
            <div v-if="bet.status === 'won'" class="p-2 w-20 text-center bg-green-500 rounded text-white mr-3 font-bold">
                Win
            </div>
            <div v-if="bet.status === 'finished'" class="p-2 w-20 text-center bg-red-500 rounded text-white mr-3">
                Lose
            </div>
            <div class="text-neutral-400">
                Event ----
            </div>
            <v-spacer />
            <div>
                date of event
            </div>
        </div>
        <v-divider />
        <div class="flex p-3 space-x-5">
            <div class="w-full bg-neutral-200 rounded">
                <div class="p-5 text-center">Fighter 1</div>
                <v-divider />
                <div class="p-3 text-center">COTE 1</div>
            </div>
            <div class="w-full bg-secondary rounded relative">
                <span class="absolute right-0 p-2">
                    <Icon height="30" icon="material-symbols:check-small-rounded" />
                </span>
                <div class="p-5 text-center font-bold">Fighter 1</div>
                <v-divider />
                <div class="p-3 text-center">COTE 1</div>
            </div>
        </div>
        <div class="flex p-3 space-x-5">
            <div class="w-full text-center bg-secondary rounded">
                <p class="p-3">Bet</p>
                <v-divider />
                <p class="p-3">10€</p>
            </div>
            <div class="w-full text-center bg-secondary rounded">
                <p class="p-3">Possible Gain</p>
                <v-divider />
                <p class="p-3 font-bold text-xl">3€</p>
            </div>
        </div>
        <div class="p-3 text-neutral-400 flex items-center">
            <span>Date of bet</span>
            <v-spacer />
            <span>Référence - UUID</span>
            <v-btn icon size="30"
                class="rounded text-neutral-400 hover:bg-neutral-200 active:bg-neutral-400 active:text-white" color="white">
                <Icon icon="material-symbols:content-copy-outline-rounded" />
            </v-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from "@iconify/vue/dist/iconify.js";
defineProps({
    bet: {
        type: Object,
        default(rawProps: any) {
            return { message: 'hello' };
        },
    },
});
</script>
