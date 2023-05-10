import { EventI, FighterI, UserI } from "@/interfaces/payload";
import { client } from "..";

const namespace = '/fights';

export interface IFight {
    event: EventI
    fighterA: FighterI
    fighterB: FighterI
    winner: FighterI
    loser: FighterI
    winnerValidation: boolean
    adminValidatorA: UserI
    adminValidatorB: UserI
}

export interface CreateFight {
    event: string
    fighterA: string
    fighterB: string
}

class Fight {

    async _getFight(fightId: string): Promise<IFight> {
        try {
            const uri = `${namespace}/${fightId}`
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getFights(): Promise<IFight[]> {
        try {
            const res = await client.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createFight(payload: CreateFight): Promise<IFight> {
        try {
            const data = {
                event: `/events/${payload.event}`,
                fighterA: `/fighters/${payload.fighterA}`,
                fighterB: `/fighters/${payload.fighterB}`,
            }
            const res = await client.post(namespace, data);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _validateFight(fightId: string): Promise<string> {
        try {
            const uri = `${namespace}/${fightId}/validation`;
            const res = await client.post(uri, {});
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _selectWinner(payload: { fightId: string, winnerId: string }): Promise<string> {
        try {
            const uri = `${namespace}/${payload.fightId}/winner`;
            const res = await client.post(uri, { winner_id: payload.winnerId });
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const fightService = new Fight();

export default fightService;