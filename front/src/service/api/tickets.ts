import { client } from "../index";
import { ITicketEvent, UpdateTicketEvent } from "@/interfaces/event";
import { ICreateTicket, ITicket, ICreateTicketCategory, ITicketCategory, ICreateTicketEvent } from "@/interfaces/ticket";
const ticketNamespace = '/tickets';
const ticketCategoryNamespace = '/ticket_categories';
const ticketEventNamespace = '/ticket_events';

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

    async _getTicketsEvent(id: string): Promise<ITicketEvent[]> {
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

    async _createTicketEvent(payload: ICreateTicketEvent): Promise<ITicketEvent> {
        try {
            const uri = `${ticketEventNamespace}`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _updateTicketEvent(payload: UpdateTicketEvent) {
        try {
            const uri = `${ticketEventNamespace}/${payload.id}`;
            const res = await client.patch(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const ticketService = new Ticket();

export default ticketService;