import { client } from "../index";
import type { FightBetI } from "../../interfaces/payload";

const namespace = '/fightbets';

class Bet {

    async _betWallet(payload: { fight: string, betOn: string, amount: number }) {
        try {
            const uri = `${namespace}/wallet`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _betDirect(payload: { fight: string, betOn: string, amount: number }): Promise<string> {
        try {
            const uri = `${namespace}/direct`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getEventBets(id: string): Promise<FightBetI[]> {
        try {
            const uri = `${namespace}/${id}`
            const res = await client.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getBet(payload: { id: string, betId: string }): Promise<FightBetI> {
        try {
            const uri = `${namespace}/${payload.id}/bets/${payload.betId}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createBet(payload: any): Promise<FightBetI> {
        try {
            const res = await client.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const betService = new Bet();

export default betService;