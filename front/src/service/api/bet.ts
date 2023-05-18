import { client } from "../index";
import type { FightBetI } from "@/interfaces/bet";
import {CreateBetI} from "@/interfaces/bet";

const namespace = '/bets';

class Bet {

    async _betWallet(payload: CreateBetI) {
        try {
            const uri = `${namespace}/payment/wallet`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _betDirect(payload: CreateBetI): Promise<string> {
        try {
            const uri = `${namespace}/payment/direct`;
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
}

const betService = new Bet();

export default betService;