import { IFighter } from "./fighter"
import { IEvent } from "./event"
import { IUser } from "./user"

interface IFight {
    id: string
    event: IEvent
    fighterA: IFighter
    fighterB: IFighter
    winner: IFighter
    loser: IFighter
    winnerValidation: boolean
    adminValidatorA: IUser
    adminValidatorB: IUser
    odds: {
        fighterAOdds: Number,
        fighterBOdds: Number
    }
    fightDate: string
}

interface CreateFight {
    event: string
    fighterA: string
    fighterB: string
    fightDate: string
}

interface UpdateFight {
    id: string
    fighterA: string
    fighterB: string
    fightDate: string
}

export type { IFight, CreateFight, UpdateFight }