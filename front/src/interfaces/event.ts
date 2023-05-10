import { IFight } from "@/service/api/fight";
import { ITicket, ITicketCategory } from "@/service/api/tickets";

export interface IEvent {
    id: string
    name: string
    location: string
    description: string
    image: string
    capacity: number
    vip: boolean
    category: string
    locationLink: string
    timeStart: string
    timeEnd: string
    imageFile: File | null
    imageName: string
    imageSize: string
    ticketEvents: []
    fights: IFight[]
}

export interface ITicketEvent {
    event: IEvent
    ticketCategory: ITicketCategory
    price: number
    maxQuantity: number
    id: string
    tickets: ITicket[]
}