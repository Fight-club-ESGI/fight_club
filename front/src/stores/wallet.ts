import { defineStore } from "pinia";
import { ref } from "vue";
import { walletService } from "../service/api";

export const useWalletStore = defineStore('wallet', () => {

    const wallet = ref<number>();

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

    return { deposit, wallet, withdraw }
});
