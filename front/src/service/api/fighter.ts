import { client, clientWithoutAuth } from "..";
import { IFighter } from "@/interfaces/fighter";

const namespace = '/fighters';

class Fighter {

    async _getFighters(): Promise<IFighter[]> {
        try {
            const res = await clientWithoutAuth.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getFighter(id: string): Promise<IFighter> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await clientWithoutAuth.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createFighter(payload: IFighter): Promise<IFighter> {
        try {
            const res = await client.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _upadateFighter(payload: IFighter): Promise<IFighter> {
        try {
            const uri = `${namespace}/${payload.id}`;
            const res = await client.patch(uri, payload);
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