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

export type { ITicket, ICreateTicket, ITicketCategory, ICreateTicketCategory }