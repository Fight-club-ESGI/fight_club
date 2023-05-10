import { IFighter } from "./fighter"
import { IEvent } from "./event"
import { IUser } from "./user"

interface IFight {
    event: IEvent
    fighterA: IFighter
    fighterB: IFighter
    winner: IFighter
    loser: IFighter
    winnerValidation: boolean
    adminValidatorA: IUser
    adminValidatorB: IUser
}

interface CreateFight {
    event: string
    fighterA: string
    fighterB: string
}

export type { IFight, CreateFight }