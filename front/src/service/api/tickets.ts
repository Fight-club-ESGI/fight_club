import { client } from "../index";
import { ITicketEvent } from "@/interfaces/event";
const ticketNamespace = '/tickets';
const ticketCategoryNamespace = '/ticket_categories';
const ticketEventNamespace = '/ticket_events';

export interface ITicket {
    id: string
    price: number
    availability: boolean,
    event: string
    ticketCategory: ITicketCategory
}

export interface ICreateTicket {
    price: number
    availability: boolean,
    event: string
    ticketCategory: string
}

export interface ITicketCategory {
    id: string
    name: string
    tickets: ITicket[]
    createdAt: string
    updatedAt: string
}

export interface ICreateTicketCategory {
    name: string
}

class Ticket {

    async _getTickets(eventId: string) {
        try {
            const uri = `/events/${eventId}${ticketNamespace}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createTicket(payload: ICreateTicket): Promise<ITicket> {
        try {
            const uri = `${ticketNamespace}`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getTicketCategories(): Promise<ITicketCategory[]> {
        try {
            const uri = `${ticketCategoryNamespace}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getTicketsEvent(id: string): Promise<ITicketCategory[]> {
        try {
            const uri = `/events/${id}/ticket_event`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createTicketCategory(payload: ICreateTicketCategory): Promise<ITicketCategory> {
        try {
            const uri = `${ticketCategoryNamespace}`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createTicketEvent(payload: ITicketEvent): Promise<any> {
        try {
            const uri = `${ticketEventNamespace}`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _updateTicketEvent(payload: { eventId: string, ticketCategoryId: string, maxQuantity: number }) {
        try {
            const uri = `/events/${payload.eventId}/tickets_event/${payload.ticketCategoryId}`;
            const res = await client.patch(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const ticketService = new Ticket();

export default ticketService;