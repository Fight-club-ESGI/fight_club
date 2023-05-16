import { ITicketEvent } from './event'
import { IUser } from './user'

export interface userInterface {
    id: string
    username: string
    roles: Array<string>
    email: string | null
    sponsorshipAsSponsor: Array<string>
    createdAt: string
    updatedAt: string
    wallet: WalletInterface
    cart: Array<CartInterface>
}

export interface WalletInterface {
    id: string
    amount: number
    createdAt: string
    updatedAt: string
}

export interface CartInterface {
    id: string
    cartItems: Array<CartItemInterface>
    createdAt: string
    updatedAt: string
}

export interface CartItemInterface {
    id: string
    cart: string
    ticketEvent: ITicketEvent
    quantity: number
    createdAt: string
    updatedAt: string
}

export interface SponsorshipResponseI {
    sponsor: string
    sponsored: IUser
    email_validation: boolean
    sponsor_validation: boolean
    id: string
    emailValidation: boolean
    sponsorValidation: boolean
    status: string
}

export interface WalletTransactionInterface {
    id: string
    amount: number
    status: string
    wallet: string
    type: string
    stripe_ref: string
    createdAt: string
    updatedAt: string
}
