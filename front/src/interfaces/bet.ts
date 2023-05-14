import { IFighter } from "./fighter";

export interface FightBetI {
    id: string;
    fightId: string;
    fighterA: IFighter;
    fighterB: IFighter;
    expectedWinner: string;
    rating: number;
}

export interface CurrentBetI {
    id: string;
    bets: FightBetI[];
    amount: number;
}
