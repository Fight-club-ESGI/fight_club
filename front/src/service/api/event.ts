import { client, clientWithoutAuth, clientFormData } from "..";
import type { CreateEvent, IEvent } from "@/interfaces/event";

const namespace = '/events';

class Event {
    async _getEvents(datePeriod: string = 'after', order: string = 'desc', page: number = 1): Promise<IEvent[]> {
        try {
            const res = await clientWithoutAuth.get(`${namespace}?order[timeStart]=${order}&page=${page}&timeEnd[${datePeriod}]=now`);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getAdminEvents(datePeriod: string = 'after', order: string = 'desc', page: number = 1): Promise<IEvent[]> {
        try {
            const res = await client.get(`${namespace}/admin?order[timeStart]=${order}&page=${page}&timeEnd[${datePeriod}]=now`);
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

    async _getEventAdmin(id: string): Promise<IEvent> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createEvent(payload: FormData): Promise<IEvent> {
        try {
            const res = await clientFormData.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _upadateEvent(payload: FormData, id: string): Promise<IEvent> {
        try {
            const uri = `${namespace}/${id}`
            const res = await clientFormData.patch(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _deleteEvent(id: string): Promise<void> {
        try {
            const uri = `${namespace}/${id}?isActive=true`;
            const res = await client.delete(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const eventService = new Event();

export default eventService;