import { IFight } from "./fight"

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
    event: ITicketEvent
    category: string
    price: number
    order: ITicketOrder
    createdAt: string
}

interface ITicketOrder {
    id: string
    reference: string
    customer: string
    createdAt: string
}

interface ITicketEvent {
    id: string
    name: string
    timeStart: string
    timeEnd: string
    location: string
    description: string
    fights: IFight[]
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

export type { ITicket, IMyTicket, ICreateTicket, ITicketCategory, ICreateTicketCategory, ICreateTicketEvent }