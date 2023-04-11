<template>
    <v-container>
        <div class="w-1/2 border p-5 m-auto">
            <p class="font-medium text-lg">An admin invited you to become VIP !</p>
            <p class="text-sm py-4">Click the button below to activate your new status</p>
            <div class="flex justify-center">
                <v-btn @click="activateVIPStatus()" color="secondary">Activate</v-btn>
            </div>
        </div>
    </v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { useSponsorshipStore } from '@/stores/sponsorship';
import { useRouter, useRoute } from 'vue-router';
export default defineComponent({
    setup() {
        const router = useRouter();
        const route = useRoute();

        const sponsorshipStore = useSponsorshipStore();
        const { validateEmail } = sponsorshipStore;

        const activateVIPStatus = async () => {
            try {
                await validateEmail(route.params.id);
                await router.push({ name: 'home' });
            } catch (e) {}
        };

        return { activateVIPStatus };
    },
});
</script>
