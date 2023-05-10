import { IFighter } from "./fighter"
import { IEvent } from "./event"
import { IUser } from "./user"

export interface IFight {
    event: IEvent
    fighterA: IFighter
    fighterB: IFighter
    winner: IFighter
    loser: IFighter
    winnerValidation: boolean
    adminValidatorA: IUser
    adminValidatorB: IUser
}

export interface CreateFight {
    event: string
    fighterA: string
    fighterB: string
}