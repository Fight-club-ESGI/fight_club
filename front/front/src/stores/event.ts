import { defineStore } from "pinia";
import { eventService } from "../service/api";
import { ref } from "vue"
import { EventI } from "../interfaces/payload";

export const useEventStore = defineStore('event', () => {
    const event = ref<EventI>();
    const events = ref<EventI[]>([]);

    async function createEvent(payload: EventI) {
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

    async function updateEvent(payload: EventI) {
        try {
            const res = await eventService._upadateEvent(payload);
            // fighter.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function deleteEvent(id: string) {
        try {
            const res = await eventService._deleteEvent(id);
        } catch (error) {
            throw error;
        }
    }
    
    return { createEvent, getEvent, getEvents, updateEvent, deleteEvent, events, event }
});