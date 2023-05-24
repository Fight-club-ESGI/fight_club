import { defineStore } from 'pinia';
import { FightBetI, CurrentBetI, CreateBetI, IBet } from '@/interfaces/bet';
import { ref } from 'vue';
import { betService } from '../service/api';
import { createToast } from "mosha-vue-toastify";

export const useBetStore = defineStore('bet', () => {
    const bet = ref<IBet>();
    const bets = ref<IBet[]>([]);

    const currentBet = ref<CurrentBetI>({
        id: '',
        bets: [],
        amount: 0,
    });

    function $resetCurrentBet() {
        currentBet.value = {
            id: '',
            bets: [],
            amount: 0,
        };
    }

    async function createBetWallet(payload: CreateBetI) {
        try {
            return await betService._betWallet(payload);
        } catch (error: any) {
            throw error;
        }
    }

    async function createBetDirect(payload: CreateBetI) {
        try {
            const res = await betService._betDirect(payload);
            return res
        } catch (error: any) {
            throw error;
        }
    }

    async function getBet(payload: { id: string; betId: string }) {
        try {
            const res = await betService._getBet(payload);
            bet.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function getBets() {
        try {
            const res = await betService._getBets();
            bets.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function getUserBets(status: string = "all", order: string = "desc") {
        try {
            const res = await betService._getUserBets(status, order);
            bets.value = res;
        } catch (error) {
            throw error;
        }
    }

    return { currentBet, getUserBets, $resetCurrentBet, createBetWallet, createBetDirect, getBet, getBets, bet, bets };
});
