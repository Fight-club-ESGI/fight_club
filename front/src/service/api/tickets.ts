import { client } from "../index";

const ticketNamespace = '/tickets';
const ticketCategoryNamespace = '/ticket_categories';

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
}

const ticketService = new Ticket();

export default ticketService;