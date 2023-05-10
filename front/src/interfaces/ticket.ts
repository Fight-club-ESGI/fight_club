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