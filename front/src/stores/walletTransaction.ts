import { defineStore } from "pinia";
import { ref } from "vue";
import { walletTransactionService } from "../service/api";
import { WalletTransactionInterface } from "@/interfaces/responseAPI";

export const useWalletTransactionStore = defineStore('wallet_transaction', () => {

    const walletTransaction = ref<WalletTransactionInterface>(
        {
            id: '',
            amount: 0,
            status: '',
            wallet: '',
            type: '',
            stripe_ref: '',
            createdAt: '',
            updatedAt: ''
        }
    );

    const walletTransactionHistory = ref<Array<WalletTransactionInterface>>([
        {
            id: '',
            amount: 0,
            status: '',
            wallet: '',
            type: '',
            stripe_ref: '',
            createdAt: '',
            updatedAt: ''
        }
    ]);

    async function getWalletTransactionHistory(order: string = 'desc') {
        try {
            walletTransactionHistory.value = await walletTransactionService._walletHistory(order);
        } catch (error) {
            throw error;
        }
    }

    async function adminGetWalletTransactionHistory() {
        try {
            walletTransactionHistory.value = await walletTransactionService._walletHistory();
        } catch (error) {
            throw error;
        }
    }

    async function transactionConfirmation(transaction_id: string) {
        try {
            walletTransaction.value = await walletTransactionService._confirmation(transaction_id);

            if (walletTransactionHistory) {

                let idx = walletTransactionHistory.value.findIndex(transaction => transaction.id === walletTransaction.value.id)
                if (idx !== -1) {
                    walletTransactionHistory.value[idx] = walletTransaction.value;
                }
            }
        } catch (error) {
            throw error;
        }
    }

    return { walletTransaction, walletTransactionHistory, getWalletTransactionHistory, transactionConfirmation }
});
