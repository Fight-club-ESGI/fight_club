import { defineStore } from "pinia";
import { ref } from "vue";
import { walletService } from "../service/api";
import { WalletInterface } from "@/interfaces/responseAPI";

export const useWalletStore = defineStore('wallet', () => {

    const wallet = ref<WalletInterface>(
        {
            id: null,
            amount: null,
            createdAt: null,
            updatedAt: null
        }
    );

    async function getWallet() {
        try {
            const res = await walletService._getWallet();
            wallet.value = res;

            return res.url;
        } catch (error) {
            throw error;
        }
    }

    async function deposit(amount: string) {
        try {
            const res = await walletService._deposit(amount);
            wallet.value.amount = res.amount;

            return res.url;
        } catch (error) {
            throw error;
        }
    }

    async function withdraw(amount: string) {
        try {
            const res = await walletService._withdraw(amount);
            wallet.value.amount = res.amount;

            return res.message;
        } catch (error) {
            throw error;
        }
    }

    return { deposit, wallet, withdraw, getWallet }
});
