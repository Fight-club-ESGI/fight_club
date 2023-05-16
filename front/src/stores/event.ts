import { defineStore } from "pinia";
import { eventService, ticketService } from "../service/api";
import { ref, computed } from "vue"
import {IEvent} from "@/interfaces/event";

export const useEventStore = defineStore('event', () => {
    const event = ref<IEvent>();
    const events = ref<IEvent[]>([]);

    const incomingEvents = computed(() => {
        return events.value.filter(e => new Date(e.timeStart) > new Date()).sort(function (a: IEvent, b: IEvent) {
            return new Date(a.timeStart) - new Date(b.timeStart);
        })
    });

    const fights = computed(() => {
        return event.value?.fights;
    });

    async function createEvent(payload: FormData) {
        try {
            const res = await eventService._createEvent(payload);
            events.value.push(res);
        } catch (error) {
            throw error;
        }
    }

    async function updateEvent(payload: FormData, id: string) {
        try {
            const res = await eventService._upadateEvent(payload, id);
            events.value.splice(events.value.findIndex(e => e.id === res.id), 1, res);
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

    async function deleteEvent(id: string) {
        try {
            await eventService._deleteEvent(id);
            events.value.splice(events.value.findIndex(e => e.id === id), 1);
        } catch (error) {
            throw error;
        }
    }

    return { createEvent, getEvent, getEvents, updateEvent, deleteEvent, events, event, fights, incomingEvents }
});