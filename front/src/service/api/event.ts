import { client, clientWithoutAuth } from "..";
import type { IEvent } from "@/interfaces/event";

const namespace = '/events';

class Event {

    async _getEvents(): Promise<IEvent[]> {
        try {
            const res = await clientWithoutAuth.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getEvent(id: string): Promise<IEvent> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await clientWithoutAuth.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createEvent(payload: IEvent): Promise<IEvent> {
        try {
            const res = await client.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _upadateEvent(payload: IEvent): Promise<IEvent> {
        try {
            const uri = `${namespace}/${payload.id}`
            const res = await client.patch(uri, payload);
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