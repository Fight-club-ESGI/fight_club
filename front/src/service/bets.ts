import { CurrentBetI, FightI } from '@/interfaces/payload';

export function setCurrentBetToLocalStorage(currentBet: CurrentBetI) {
    localStorage.setItem('currentBet', JSON.stringify(currentBet));
}

export function getCurrentBetFromLocalStorage() {
    const currentBet = localStorage.getItem('currentBet');
    if (currentBet) return currentBet;
    return [];
}
