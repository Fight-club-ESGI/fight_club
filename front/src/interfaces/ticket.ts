interface ITicket {
    id: string
    price: number
    availability: boolean,
    event: string
    ticketCategory: ITicketCategory
}

interface IMyTicket {
    id: string
    reference: string
    event: string
    category: string
    price: number
    created_at: string
}

interface IOrder {
    id: string
    tickets: IMyTicket[]
}

interface ICreateTicket {
    price: number
    availability: boolean,
    event: string
    ticketCategory: string
}

interface ITicketCategory {
    id: string
    name: string
    tickets: ITicket[]
    createdAt: string
    updatedAt: string
    isActive: boolean
}

interface ICreateTicketCategory {
    name: string
}

interface ICreateTicketEvent {
    price: number
    event: string
    maxQuantity: number
    ticketCategory: string
}

export type { ITicket, IMyTicket, IOrder, ICreateTicket, ITicketCategory, ICreateTicketCategory, ICreateTicketEvent }