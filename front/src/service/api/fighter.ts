import {client, clientFormData, clientWithoutAuth} from "..";
import { CreateFighter, IFighter } from "@/interfaces/fighter";

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

    async _createFighter(payload: FormData): Promise<IFighter> {
        try {
            const res = await clientFormData.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _upadateFighter(payload: FormData, id: string): Promise<IFighter> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await clientFormData.patch(uri, payload);
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