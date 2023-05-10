interface ITicket {
    id: string
    price: number
    availability: boolean,
    event: string
    ticketCategory: ITicketCategory
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

export type { ITicket, ICreateTicket, ITicketCategory, ICreateTicketCategory, ICreateTicketEvent }