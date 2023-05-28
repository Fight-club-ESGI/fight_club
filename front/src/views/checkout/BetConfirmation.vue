<template>
    <div class="flex flex-column h-full place-content-center items-center">
        <div v-if="walletTransaction.status === 'accepted'" class="flex flex-col text-center items-center">
            <Icon height="200" icon="emojione:white-heavy-check-mark" />
            <p class="text-3xl font-bold p-4">
                Payment approved
            </p>
        </div>
        <div v-else-if="walletTransaction.status === 'rejected'" class="flex flex-col text-center items-center">
            <Icon height="200" icon="emojione:no-entry" />
            <p class="text-3xl font-bold p-4">Payment rejected</p>
        </div>
        <div v-else-if="walletTransaction.status === 'cancelled'" class="flex flex-col text-center items-center">
            <Icon height="200" icon="emojione:no-entry" />
            <p class="text-3xl font-bold p-4">Payment cancelled</p>
        </div>
        <div v-else-if="walletTransaction.status === 'pending'" class="flex flex-col text-center items-center">
            <Icon height="200" icon="emojione:three-thirty" />

            <p class="text-3xl font-bold p-4">Payment pending</p>
        </div>
        <div>
            <v-btn :to="{ name: 'user-wallet' }" variant="tonal">
                Back to wallet
            </v-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute } from "vue-router";
import { storeToRefs } from "pinia";
import { useWalletTransactionStore } from "@/stores/walletTransaction";
import { Icon } from "@iconify/vue/dist/iconify.js";

const walletTransactionStore = useWalletTransactionStore();

const route = useRoute();

const { transactionBetConfirmation } = walletTransactionStore;
const { walletTransaction } = storeToRefs(walletTransactionStore);

transactionBetConfirmation(route.query['transaction_id'])
</script>

<style scoped>
.custom-font {
    font-size: 36px;
    font-weight: bold;
}
</style>
