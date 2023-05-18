import { IFight, CreateFight, UpdateFight } from "@/interfaces/fight";
import { client } from "..";

const namespace = '/fights';

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
            const data: CreateFight = {
                event: `/events/${payload.event}`,
                fighterA: `/fighters/${payload.fighterA}`,
                fighterB: `/fighters/${payload.fighterB}`,
                fightDate: payload.fightDate
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

    async _removeFight(id: string): Promise<void> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.delete(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _updateFight(payload: UpdateFight): Promise<IFight> {
        try {
            const uri = `${namespace}/${payload.id}`;
            const data = {
                fighterA: `/fighters/${payload.fighterA}`,
                fighterB: `/fighters/${payload.fighterB}`,
            }
            const res = await client.patch(uri, data);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const fightService = new Fight();

export default fightService;