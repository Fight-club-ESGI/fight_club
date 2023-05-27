<template>
    <div class="grid h-screen place-items-center bg-neutral-800 text-white">
        <div class="flex-column items-center">
            <v-img :src="invalidToken" :width="353" aspect-ratio="16/9" class="mx-auto"></v-img>
            <!-- <div
                class="h-1/2 w-1/2 flex items-center bg-no-repeat bg-cover bg-right opacity-75"
                style="background-image: url('../src/assets/invalidToken.svg');"
            >  -->
            <p class="text-5xl font-extrabold text-center my-6">
                Link validity has expired
            </p>
            <div class="mx-auto w-1/4">
                <v-btn :to="{ name: 'forgot-password' }" block>Back to home</v-btn>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import invalidToken from "../assets/invalidToken.svg";

export default defineComponent({

})
</script>