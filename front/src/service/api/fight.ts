import { client } from "..";

const namespace = '/fights';

class Fight {

    async _getFight(fightId: string): Promise<{}> {
        try {
            const uri = `${namespace}/${fightId}`
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getFights(): Promise<[]> {
        try {
            const res = await client.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createFight(payload: { event: string, fighterA: string, fighterB: string, winnerValidation: boolean }): Promise<string> {
        try {
            const res = await client.post(namespace, payload);
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