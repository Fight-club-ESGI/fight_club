import { client } from "..";
import type { EventI } from "../../interfaces/payload";

const namespace = '/events';

class Event {
    
    async _getEvents(): Promise<EventI[]> {
        try {
            const res = await client.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getEvent(id: string): Promise<EventI> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createEvent(payload: EventI): Promise<EventI> {
        try {
            const res = await client.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _upadateEvent(payload: EventI): Promise<EventI> {
        try {
            const res = await client.put(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _deleteEvent(id: string): Promise<void> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.delete(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const eventService = new Event();

export default eventService;