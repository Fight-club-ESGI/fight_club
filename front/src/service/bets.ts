import { CurrentBetI, FightI } from '@/interfaces/payload';

export function setCurrentBetToLocalStorage(currentBet: CurrentBetI) {
    localStorage.setItem('currentBet', JSON.stringify(currentBet));
}

export function getCurrentBetFromLocalStorage() {
    const currentBet = localStorage.getItem('currentBet');
    if (currentBet) return currentBet;
    return [];
}

export function betOnAFight(fight: FightI, expectedWinner: string, selectedRate: number, amount: number = 0) {
    const currentBet: CurrentBetI = {
        fighterA: fight.fighterA,
        fighterB: fight.fighterB,
        expectedWinner: expectedWinner,
        amount: amount,
        rating: selectedRate,
    };
    setCurrentBetToLocalStorage(currentBet);
}
