import { ITicket, ITicketCategory } from "./ticket";
import { IFight } from "./figth";

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

export interface CreateEvent {
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
}