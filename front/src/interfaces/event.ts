import { ITicket, ITicketCategory } from "@/service/api/tickets";
import { FightI } from "./payload";

interface IEvent {
    id: string
    name: string
    location: string
    description: string
    image: string
    capacity: number
    vip: boolean
    category: string
    fight: FightI[]
    locationLink: string
    startTimestamp: string
    endTimestamp: string
}

export interface ITicketEvent {
    event: IEvent
    ticketCategory: ITicketCategory
    price: number
    maxQuantity: number
    id: string
    tickets: ITicket[]
}