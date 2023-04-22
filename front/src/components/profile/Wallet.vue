<template>
    <div class="flex h-full space-x-5 p-10">
        <v-card class="pa-10 w-1/4 rounded" >
            <div class="text-center">
                <div class="text-3xl mb-6">{{ user.wallet.amount }} €</div>
            </div>
            <div>
                <v-text-field type="number" v-model.number="wallet_input_amount" />
            </div>
            <div class="flex">
                <div class="w-1/2">
                    <v-btn block color="secondary" class="mr-2" @click="wallet_withdraw()"> Withdraw </v-btn>
                </div>
                <div class="w-1/2">
                    <v-btn block class="ml-2" @click="wallet_deposit()"> Deposit </v-btn>
                </div>
            </div>
        </v-card>
        <v-card class="pa-5 w-3/4 rounded">
            <div class="text-2xl font-bold">Transaction history</div>
            <v-card class="pa-5 mt-12" v-for="transaction in walletHistoryData">
                {{ transaction.id }}
                {{ transaction.createdAt }}
                {{ transaction.updatedAt }}
                {{ transaction.amount }}
                {{ transaction.status }}
            </v-card>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia';
import { defineComponent, onMounted, ref } from 'vue';
import { useWalletStore } from '@/stores/wallet';
import { createToast } from 'mosha-vue-toastify';
import { useUserStore } from '@/stores/user';
import {useWalletTransactionStore} from "@/stores/walletTransaction";

const wallet_amount = ref(0);
const wallet_input_amount = ref('0');
const walletStore = useWalletStore();
const { deposit, withdraw, wallet } = walletStore;

const walletTransactionStore = useWalletTransactionStore();

const { getWalletTransactionHistory } = walletTransactionStore
const { walletHistoryData } = storeToRefs(walletTransactionStore);

const { user } = storeToRefs(useUserStore());
const wallet_deposit = async () => {
    try {
        window.location.href = await deposit(wallet_input_amount.value);
        createToast('Deposit success', { position: 'bottom-right', type: 'success' });
    } catch (e) {
        createToast('Error during deposit', { position: 'bottom-right', type: 'danger' });
    }
};

const wallet_withdraw = async () => {
    try {
        let message = await withdraw(wallet_input_amount.value);
        createToast(message, { position: 'bottom-right', type: 'success' });
    } catch (e) {
        createToast('Error during withdraw ', {
            position: 'bottom-right',
            type: 'success',
        });
    }
};

onMounted(async () => {
    try {
        await getWalletTransactionHistory();
    } catch (e) {}
});
</script>
