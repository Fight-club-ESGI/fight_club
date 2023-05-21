import { IFighter } from "./fighter";
import {IFight} from "@/interfaces/fight";
import {IUser} from "@/interfaces/user";
import {WalletTransactionInterface} from "@/interfaces/responseAPI";

export interface FightBetI {
    id: string;
    fightId: string;
    fighterA: IFighter;
    fighterB: IFighter;
    expectedWinner: string;
    odds: number;
}

export interface CurrentBetI {
    id: string;
    bets: FightBetI[];
    amount: number;
}

export interface IBet {
    id: string,
    fight: IFight,
    betOn: IFighter,
    bettor: IUser,
    walletTransaction: WalletTransactionInterface,
    amount: number,
    status: string,
    createdAt: string,
    updatedAt: string,
}

export interface CreateBetI {
    fight: string,
    betOn: string,
    amount: number,
}