import { defineStore } from "pinia";
import { eventService, ticketService } from "../service/api";
import { ref, computed, ComputedRef } from "vue"
import { IEvent } from "@/interfaces/event";

export const useEventStore = defineStore('event', () => {
    const event = ref<IEvent>();
    const events = ref<IEvent[]>([]);

    const incomingEvents = computed(() => {
        return events.value.filter(e => new Date(e.timeStart) > new Date()).sort(function (a: IEvent, b: IEvent) {
            return new Date(a.timeStart) - new Date(b.timeStart);
        })
    });

    const fightDESC = computed(() => {
        return event.value?.fights.sort(function (a, b) {
            // @ts-ignore
            return new Date(a.fightDate) - new Date(b.fightDate);
        });
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
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function getEventAdmin(id: string) {
        try {
            const res = await eventService._getEventAdmin(id);
            event.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function getEvents(datePeriod: string = 'after') {
        try {
            const res = await eventService._getEvents(datePeriod);
            events.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function getEventsAdmin(datePeriod: string = 'after') {
        try {
            const res = await eventService._getAdminEvents(datePeriod);
            events.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function deleteEvent(id: string) {
        try {
            await eventService._deleteEvent(id);
            await getEventsAdmin();
        } catch (error) {
            throw error;
        }
    }

    return { createEvent, getEvent, getEventAdmin, getEvents, getEventsAdmin, updateEvent, deleteEvent, events, event, fights, incomingEvents, fightDESC }
});