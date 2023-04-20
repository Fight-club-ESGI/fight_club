import { defineStore } from "pinia";
import { ref } from "vue";
import { walletService } from "../service/api";
import {WalletTransactionInterface} from "@/interfaces/responseAPI";

export const useWalletStore = defineStore('wallet', () => {

    const wallet = ref<number>();
    const walletHistoryData = ref<Array<WalletTransactionInterface>>([
      {
        id: null,
        amount: null,
        status: null,
        createdAt: null,
        updatedAt: null
      }
    ]);

    async function walletHistory() {
        try {
            walletHistoryData.value = await walletService._walletHistory();
        } catch (error) {
            throw error;
        }
    }

    async function deposit(amount: string) {
        try {
            const res = await walletService._deposit(amount);
            wallet.value = res.amount;

            return res.url;
        } catch (error) {
            throw error;
        }
    }

    async function withdraw(amount: string) {
        try {
            const res = await walletService._withdraw(amount);
            wallet.value = res.amount;

            return res.message;
        } catch (error) {
            throw error;
        }
    }

    async function transactionConfirmation(transaction_id: string) {
        try {
            //const res = await

            //return res
        } catch (error) {
            throw error;
        }
    }

    return { deposit, wallet, walletHistory, withdraw, walletHistoryData }
});
