import { client } from "../index";

const ticketNamespace = '/tickets';
const ticketCategoryNamespace = '/ticket_categories';

export interface ITicket {
    id: string
    price: number
    event: string
    ticketCategory: string | null
}

export interface ITicketCategory {
    id: string
    name: number
    tickets: ITicket[]
    createdAt: string
    updatedAt: string
}

class Ticket {

    async _getTickets(eventId: string) {
        try {
            const uri = `${ticketNamespace}/${eventId}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createTicket(payload: { event: string, price: string, availbility: number, ticketCategory: string }): Promise<ITicket> {
        try {
            const uri = `${ticketNamespace}/${payload.event}`;
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
}

const ticketService = new Ticket();

export default ticketService;