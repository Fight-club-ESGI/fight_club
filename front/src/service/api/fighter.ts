import { client } from "..";
import { FighterI } from "../../interfaces/payload";

const namespace = '/fighters';

class Fighter {
    
    async _getFighters(): Promise<FighterI[]> {
        try {
            const res = await client.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getFighter(id: string): Promise<FighterI> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createFighter(payload: FighterI): Promise<FighterI> {
        try {
            const res = await client.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _upadateFighter(payload: FighterI): Promise<FighterI> {
        try {
            const res = await client.put(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _deleteFighter(id: string): Promise<void> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.delete(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const fighterService = new Fighter();

export default fighterService;