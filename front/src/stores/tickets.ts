import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { ticketService } from "../service/api/index";
import { ITicket, ITicketCategory, ICreateTicket } from "@/service/api/tickets";
export const useTicketStore = defineStore('ticket', () => {

    const tickets = ref<ITicket[]>([]);
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
            console.log('*******', res);
            tickets.value.push(res);
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

    return { tickets, ticketCategories, getTickets, createTicket, getTicketCategories, ticketsNumber, availableTickets }
});