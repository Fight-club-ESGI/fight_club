import { defineStore } from "pinia";
import { FightBetI } from "../interfaces/payload";
import { ref } from "vue";
import { betService } from "../service/api";

export const useBetStore = defineStore('bet', () => {

    const bet = ref<FightBetI>();
    const bets = ref<FightBetI[]>([]);

    async function betWallet(payload: { fight: string, betOn: string, amount: number }) {
        try {
            const res = await betService._betWallet(payload);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function betDirect(payload: { fight: string, betOn: string, amount: number }) {
        try {
            const res = await betService._betDirect(payload);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function createBet(payload: FightBetI) {
        try {
            const res = await betService._createBet(payload);
            bets.value.push(res);
        } catch (error) {
            throw error;
        }
    }

    async function getBet(payload: { id: string, betId: string }) {
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

    return { betWallet, betDirect, createBet, getBet, getBets, bet, bets }
});