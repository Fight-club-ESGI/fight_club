<template>
    <div class="flex flex-col h-full px-5 pb-5">
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div class="flex h-10/12 space-x-5">
            <div class="flex flex-col h-full w-1/4 rounded ">
                <div class="flex font-bold text-2xl h-1/12 items-center">Your wallet funds</div>
                <div class="h-11/12">
                    <div class="text-center bg-neutral-100 p-10 rounded">
                        <div class="font-bold text-3xl p-6">{{ wallet.amount }} €</div>
                    </div>
                    <div>
                        <v-text-field type="number" v-model.number="wallet_input_amount" />
                    </div>
                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <v-btn block class="rounded" @click="wallet_withdraw()">Withdraw</v-btn>
                        </div>
                        <div class="w-1/2">
                            <v-btn block class="rounded" color="secondary" @click="wallet_deposit()">Deposit</v-btn>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-3/4">
                <div class="flex text-2xl h-1/12 font-bold items-center">Transaction history</div>
                <div class="w-full h-11/12 overflow-auto rounded bg-neutral-100">
                    <div>
                        <div class="flex p-2 space-x-5 items-center" v-for="(transaction, index) in walletTransactionHistory"
                             :key="transaction.id">
                            {{index}}
                            <div class="w-40 font-bold text-center">
                                {{ transaction.type }}
                            </div>
                            <div class="flex bg-neutral-200 p-3 rounded-lg w-full items-center">
                            <span class="">
                                {{ transaction.amount / 100 }} €
                            </span>
                                <v-spacer />
                                <span class="px-4 font-bold w-32 text-right">
                                {{ transaction.status }}
                            </span>
                                <v-btn v-if="transaction.status === 'pending'" icon height="30" width="30" color="secondary"
                                       class="rounded mr-3" @click="transactionConfirmation(transaction.id)">
                                    <Icon height="25" icon="material-symbols:refresh" />
                                </v-btn>
                                <Icon height="25" :icon="transactionStatusIcon(transaction.status)" />
                            </div>
                            <Icon height="40" icon="material-symbols:calendar-month" />
                            <div class="w-64 font-bold bg-neutral-200 p-3 rounded-lg">
                                {{
                                    new Date(transaction.createdAt).toLocaleString('en-GB', {
                                        year: 'numeric',
                                        month: '2-digit',
                                        day: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })
                                }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia';
import { defineComponent, onMounted, ref } from 'vue';
import { useWalletStore } from '@/stores/wallet';
import { createToast } from 'mosha-vue-toastify';
import { useUserStore } from '@/stores/user';
import { useWalletTransactionStore } from "@/stores/walletTransaction";
import { Icon } from "@iconify/vue/dist/iconify.js";

const wallet_amount = ref(0);
const wallet_input_amount = ref('0');
const walletStore = useWalletStore();
const { deposit, withdraw, getWallet } = walletStore;
const { wallet } = storeToRefs(walletStore);

const walletTransactionStore = useWalletTransactionStore();

const { getWalletTransactionHistory, transactionConfirmation } = walletTransactionStore
const { walletTransactionHistory } = storeToRefs(walletTransactionStore);
const { user } = storeToRefs(useUserStore());
const wallet_deposit = async () => {
    try {
        window.location.href = await deposit(wallet_input_amount.value);
        createToast('Deposit success', { position: 'bottom-right', type: 'success' });
    } catch (e: any) {
        createToast(e.response.data, { position: 'bottom-right', type: 'danger' });
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

const transactionStatusIcon = (status: string) => {
    switch (status) {
        case 'accepted':
            return 'emojione:white-heavy-check-mark'
        case 'pending':
            return 'twemoji:hourglass-not-done'
        default:
            return 'emojione:no-entry'
    }
}

onMounted(async () => {
    try {
        await getWallet();
        await getWalletTransactionHistory('asc');
    } catch (e) { }
});

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Wallet'
    }
];
</script>
