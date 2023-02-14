import { defineStore } from "pinia";
import { ref } from "vue";
import { walletService } from "../service/api";

export const useWalletStore = defineStore('wallet', () => {

    const wallet = ref<number>();
    const walletHistoryData = ref([]);

    async function walletHistory() {
        try {
            const res = await walletService._walletHistory();
            walletHistoryData.value = res;
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

    return { deposit, wallet, walletHistory, withdraw, walletHistoryData }
});
