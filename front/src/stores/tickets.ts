import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { ticketService } from "../service/api/index";
import { ITicket, ITicketCategory, ICreateTicket, ICreateTicketEvent } from "@/interfaces/ticket";
import { ITicketEvent, UpdateTicketEvent } from "@/interfaces/event";
import { useEventStore } from "./event";

export const useTicketStore = defineStore('ticket', () => {

    const eventStore = useEventStore();
    const { updateEvent } = eventStore;

    const tickets = ref<ITicket[]>([]);
    const ticketsEvent = ref<ITicketEvent[]>([]);
    const ticketCategories = ref<ITicketCategory[]>([]);

    const myTickets = ref<IOrder[]>([]);

    const ticketsNumber = computed(() => tickets.value.length);
    const availableTickets = computed(() => ticketsEvent.value.filter((ticketEvent: ITicketEvent) => ticketEvent.tickets.length < ticketEvent.maxQuantity));

    const availableTicketsEvent = computed(() => {
        const ticketEventCategories = ticketsEvent.value.map(tE => tE.ticketCategory.name);
        return ["GOLD", "VIP", "V_VIP", "SILVER", "PEUPLE"].filter(c => !ticketEventCategories.includes(c));
    });

    const maxCapacity = computed(() => {
        return ticketsEvent.value.reduce((acc, curr) => acc + curr.maxQuantity, 0)
    });

    const selectedTicketEvent = ref();

    async function getTickets() {
        try {
            const res = await ticketService._getMyTickets();
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
            const eventId = payload.event.split('/')[2]

            await updateEvent(eventId, { capacity: maxCapacity.value + res.maxQuantity })
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

            await getTicketsEvent(eventId);
            await updateEvent(eventId, { capacity: maxCapacity.value })

            selectedTicketEvent.value = null;
            return res;
        } catch (err) {
            throw err;
        }
    }

    return { tickets, myTickets, ticketCategories, ticketsEvent, selectedTicketEvent, getTickets, updateTicketEvent, createTicket, availableTicketsEvent, getTicketCategories, createTicketEvent, getTicketsEvent, ticketsNumber, availableTickets }
});