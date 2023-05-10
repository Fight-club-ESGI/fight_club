import { defineStore } from "pinia";
import { eventService, ticketService } from "../service/api";
import { ref } from "vue"
import { IEvent } from "@/interfaces/event";

export const useEventStore = defineStore('event', () => {
    const event = ref<IEvent>();
    const events = ref<IEvent[]>([]);

    async function createEvent(payload: IEvent) {
        try {
            const res = await eventService._createEvent(payload);
            events.value.push(res);
        } catch (error) {
            throw error;
        }
    }

    async function getEvent(id: string) {
        try {
            const res = await eventService._getEvent(id);
            event.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function getEvents() {
        try {
            const res = await eventService._getEvents();
            events.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function updateEvent(payload: IEvent) {
        try {
            const res = await eventService._upadateEvent(payload);
            events.value.splice(events.value.findIndex(e => e.id === res.id), 1, res);
        } catch (error) {
            throw error;
        }
    }

    async function deleteEvent(id: string) {
        try {
            await eventService._deleteEvent(id);
            events.value.splice(events.value.findIndex(e => e.id === id), 1);
        } catch (error) {
            throw error;
        }
    }

    return { createEvent, getEvent, getEvents, updateEvent, deleteEvent, events, event }
});