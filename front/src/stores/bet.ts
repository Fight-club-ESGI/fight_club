import { defineStore } from 'pinia';
import { FightBetI, CurrentBetI, CreateBetI } from '@/interfaces/bet';
import { ref } from 'vue';
import { betService } from '../service/api';
import {createToast} from "mosha-vue-toastify";

export const useBetStore = defineStore('bet', () => {
    const bet = ref<FightBetI>();
    const bets = ref<FightBetI[]>([]);

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
            createToast(error.response.data, { position: 'bottom-right', type: 'danger' });
            throw error;
        }
    }

    async function createBetDirect(payload: CreateBetI) {
        try {
            return await betService._betDirect(payload);
        } catch (error: any) {
            createToast(error.response.data, { position: 'bottom-right', type: 'danger' });
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

    async function getBets(id: string) {
        try {
            const res = await betService._getEventBets(id);
            bets.value = res;
        } catch (error) {
            throw error;
        }
    }

    return { currentBet, $resetCurrentBet, createBetWallet, createBetDirect, getBet, getBets, bet, bets };
});
