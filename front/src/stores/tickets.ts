import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { ticketService } from "../service/api/index";
import { ITicket, ITicketCategory, ICreateTicket, ICreateTicketEvent, IOrder } from "@/interfaces/ticket";
import { ITicketEvent, UpdateTicketEvent } from "@/interfaces/event";

export const useTicketStore = defineStore('ticket', () => {

    const tickets = ref<ITicket[]>([]);
    const ticketsEvent = ref<ITicketEvent[]>([]);
    const ticketCategories = ref<ITicketCategory[]>([]);

    const myTickets = ref<IOrder[]>([]);

    const ticketsNumber = computed(() => tickets.value.length);
    const availableTickets = computed(() => ticketsEvent.value.filter((ticketEvent: ITicketEvent) => ticketEvent.tickets.length < ticketEvent.maxQuantity));

    const activeTickets = computed(() => {
        return ticketsEvent.value.filter(ti => ti.isActive);
    });

    async function getTickets(eventId: string) {
        try {
            const res = await ticketService._getTickets(eventId);
            myTickets.value = res;
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

    async function createTicketEvent(payload: ICreateTicketEvent) {
        try {
            const res: ITicketEvent = await ticketService._createTicketEvent(payload);
            ticketsEvent.value.push(res);
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
            ticketsEvent.value = res;
        } catch (err) {
            throw err;
        }
    }

    async function updateTicketEvent(payload: UpdateTicketEvent): Promise<ITicketEvent> {
        try {
            const res = await ticketService._updateTicketEvent(payload);
            const eventId = payload.event.split('/')[2]
            await getTicketsEvent(eventId)
            return res;
        } catch (err) {
            throw err;
        }
    }

    return { tickets, myTickets, activeTickets, ticketCategories, ticketsEvent, getTickets, updateTicketEvent, createTicket, getTicketCategories, createTicketEvent, getTicketsEvent, ticketsNumber, availableTickets }
});