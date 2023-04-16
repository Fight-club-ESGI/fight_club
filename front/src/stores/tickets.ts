import { defineStore } from "pinia";
import { ref } from "vue";
import { ticketService } from "../service/api/index";
import { ITicket, ITicketCategory } from "@/service/api/tickets";
export const useTicketStore = defineStore('ticket', () => {

    const tickets = ref<ITicket[]>([]);
    const ticketCategories = ref<ITicketCategory[]>([]);

    async function getTickets(eventId: string) {
        try {
            const res = await ticketService._getTickets(eventId);
            tickets.value = res;
        } catch (err) {
            throw err;
        }
    }

    async function createTicket(payload: { event: string, price: string, availbility: number, ticketCategory: string }) {
        try {
            const res: ITicket = await ticketService._createTicket(payload);
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

    return { tickets, ticketCategories, getTickets, createTicket, getTicketCategories }
});