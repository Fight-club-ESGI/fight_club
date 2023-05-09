import { defineStore } from 'pinia';
import { FightBetI, CurrentBetI } from '../interfaces/payload';
import { ref } from 'vue';
import { betService } from '../service/api';
// import { setCurrentBetToLocalStorage, getCurrentBetFromLocalStorage } from '../service/bets';

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

    async function betWallet(payload: { fight: string; betOn: string; amount: number }) {
        try {
            const res = await betService._betWallet(payload);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function betDirect(payload: { fight: string; betOn: string; amount: number }) {
        try {
            const res = await betService._betDirect(payload);
            return res;
        } catch (error) {
            throw error;
        }
    }

    // async function setCurrentBet(bet: CurrentBetI[]) {
    //     try {
    //         const res = setCurrentBetToLocalStorage(bet);
    //         currentBet.value = bet;
    //         return res;
    //     } catch (error) {
    //         throw error;
    //     }
    // }

    async function createBet(payload: FightBetI) {
        try {
            const res = await betService._createBet(payload);
            bets.value.push(res);
        } catch (error) {
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

    return { currentBet, $resetCurrentBet, betWallet, betDirect, createBet, getBet, getBets, bet, bets };
});
