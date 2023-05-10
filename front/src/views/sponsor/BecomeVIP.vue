<script setup lang="ts">
import { useRoute, useRouter } from "vue-router";
import { computed } from "vue";
import { useSponsorshipStore } from '@/stores/sponsorship';
const sponsorshipStore = useSponsorshipStore();
const { validateEmail } = sponsorshipStore;
const route = useRoute();
const router = useRouter();

const token = computed(() => route.query.token).value;

const becomeVIP = async () => {
    try {
        await validateEmail(token as string);
        router.push({ name: 'home' });
    } catch (error) {

    }
}
</script>

<template>
    <div class="grid h-screen place-items-center bg-neutral-800 text-white">
        <div class="flex-column items-center">
            <v-img src="../src/assets/becomeVIP.svg" :width="353" aspect-ratio="16/9" class="mx-auto"></v-img>
            <!-- <div
                class="h-1/2 w-1/2 flex items-center bg-no-repeat bg-cover bg-right opacity-75"
                style="background-image: url('../src/assets/invalidToken.svg');"
            >  -->
            <p class="text-5xl font-extrabold text-center my-6">
                Become VIP by clicking the button bellow
            </p>
            <div class="mx-auto w-1/4">
                <v-btn @click="becomeVIP()" block>VIP</v-btn>
            </div>
        </div>
    </div>
</template>