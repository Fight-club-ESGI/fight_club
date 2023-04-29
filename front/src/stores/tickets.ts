import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { ticketService } from "../service/api/index";
import { ITicket, ITicketCategory, ICreateTicket } from "@/service/api/tickets";
import { ITicketEvent } from "@/interfaces/event";

export const useTicketStore = defineStore('ticket', () => {

    const tickets = ref<ITicket[]>([]);
    const ticketEvent = ref<ITicketEvent[]>([]);
    const ticketCategories = ref<ITicketCategory[]>([]);

    const ticketsNumber = computed(() => tickets.value.length);
    const availableTickets = computed(() => tickets.value.map(t => t.availability === true).length);

    async function getTickets(eventId: string) {
        try {
            const res = await ticketService._getTickets(eventId);
            tickets.value = res;
        } catch (err) {
            throw err;
        }
    }

    async function createTicket(payload: ICreateTicket) {
        try {
            const res: ITicket = await ticketService._createTicket(payload);
            tickets.value.push(res);
        } catch (err) {
            throw err;
        }
    }

    async function createTicketEvent(payload: ITicketEvent) {
        try {
            const res: ITicket = await ticketService._createTicketEvent(payload);
            ticketEvent.value.push(res);
        } catch (err) {
            throw err;
        }
    }

    async function getTicketCategories() {
        try {
            const res = await ticketService._getTicketCategories();
            ticketCategories.value = res;
        } catch (err) {
            throw err;
        }
    }

    async function getTicketsEvent(eventId: string) {
        try {
            const res = await ticketService._getTicketsEvent(eventId);
            ticketEvent.value = res;
        } catch (err) {
            throw err;
        }
    }

    return { tickets, ticketCategories, ticketEvent, getTickets, createTicket, getTicketCategories, createTicketEvent, getTicketsEvent, ticketsNumber, availableTickets }
});