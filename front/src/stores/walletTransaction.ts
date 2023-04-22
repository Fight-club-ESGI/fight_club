import { defineStore } from "pinia";
import { ref } from "vue";
import { walletTransactionService } from "../service/api";
import {WalletTransactionInterface} from "@/interfaces/responseAPI";

export const useWalletTransactionStore = defineStore('wallet_transaction', () => {

    const walletTransaction = ref<WalletTransactionInterface>(
        {
            id: null,
            amount: null,
            status: null,
            createdAt: null,
            updatedAt: null
        }
    );

    const walletTransactionHistory = ref<Array<WalletTransactionInterface>>([
        {
            id: null,
            amount: null,
            status: null,
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

    async function transactionConfirmation(transaction_id: string) {
        try {
            walletTransaction.value = await walletTransactionService._confirmation(transaction_id);
        } catch (error) {
            throw error;
        }
    }

    return { walletTransaction, walletTransactionHistory, getWalletTransactionHistory, transactionConfirmation }
});
