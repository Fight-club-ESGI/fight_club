import { IFighter } from "./fighter";

export interface SigninI {
    email: string;
    password: string;
}

export interface SignupI {
    username: string;
    email: string;
    password: string;
}

export interface TokenI {
    token: string;
    refresh_token: string;
}

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

export interface SponsorshipI {
    sponsor: string;
    sponsored: string;
    emailValidation: boolean;
    sponsorValidation: boolean;
}

export interface WeightCategoryP {
    minWeight: number;
    maxWeight: number;
    name: string;
}

export interface WeightCategory {
    minWeight: number;
    maxWeight: number;
    name: string;
    id: string;
    updatedAt: string;
    createdAt: string;
}
