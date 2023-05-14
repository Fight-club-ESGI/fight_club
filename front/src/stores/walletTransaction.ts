import { defineStore } from "pinia";
import { ref } from "vue";
import { walletTransactionService } from "../service/api";
import { WalletTransactionInterface } from "@/interfaces/responseAPI";

export const useWalletTransactionStore = defineStore('wallet_transaction', () => {

    const walletTransaction = ref<WalletTransactionInterface>(
        {
            id: null,
            amount: null,
            status: null,
            wallet: null,
            type: null,
            stripe_ref: null,
            createdAt: null,
            updatedAt: null
        }
    );

    const walletTransactionHistory = ref<Array<WalletTransactionInterface>>([
        {
            id: null,
            amount: null,
            status: null,
            wallet: null,
            type: null,
            stripe_ref: null,
            createdAt: null,
            updatedAt: null
        }
    ]);

    async function getWalletTransactionHistory() {
        try {
            walletTransactionHistory.value = await walletTransactionService._walletHistory();
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
