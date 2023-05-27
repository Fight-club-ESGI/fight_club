import { defineStore } from "pinia";
import { CreateFighter, IFighter } from "@/interfaces/fighter";
import { ref, computed, ComputedRef, Ref } from "vue";
import { fighterService } from "../service/api/index";
import { IFight } from "@/interfaces/figth";

export const useFighterStore = defineStore('fighter', () => {

    const fighter = ref<IFighter>();
    const fighters = ref<IFighter[]>([]);

    interface FighterFilter {
        divisionClass: string[],
        nationality: string | null
        search: string
        gender: string[]
    }

    let filter: Ref<FighterFilter> = ref({
        divisionClass: [],
        nationality: null,
        search: '',
        gender: [],
    });

    const fighterHistoryMatches: ComputedRef<IFight[]> = computed(() => {
        return fighter.value?.fights.reduce((acc, sum) => {
            console.log(sum)
            if (sum.winnerValidation) acc.push(sum);
            return acc;
        }, []).sort(function (a: IFight, b: IFight) {
            // @ts-ignore
            return new Date(b.fightDate) - new Date(a.fightDate);
        });
    });

    const fighterWL: ComputedRef<{ win: number, lose: number }> = computed(() => {
        return fighter.value?.fights.reduce((acc, sum) => {
            if (typeof sum.winner === 'string') {
                acc['win'] ? acc['win'] += 1 : acc['win'] = 1;
            } else {
                acc['lose'] ? acc['lose'] += 1 : acc['lose'] = 1;
            }
            return acc;
        }, {});
    })

    const filteredFighters: ComputedRef<IFighter[]> = computed(() => {
        let res = fighters.value;
        res = res.filter((fighter) => {
            if (filter.value.gender.includes(fighter.gender) || filter.value.gender.length === 0) {
                return fighter;
            }
        });

        if (filter.value.search.length !== 0) {
            res = res.filter((fighter) => {
                if (
                    fighter.firstname.toLowerCase().indexOf(filter.value.search.toLowerCase()) !== -1 ||
                    fighter.lastname.toLowerCase().indexOf(filter.value.search.toLowerCase()) !== -1
                )
                    return fighter;
            });
        }

        if (filter.value.nationality) {
            res = res.filter((fighter) => {
                return fighter.nationality === filter.value.nationality;
            });
        }

        if (filter.value.divisionClass) {
            res = res.filter((fighter) => {
                // Still stuff to do here, need to bind a category to a user
                return fighter;
            });
        }
        return res;
    });

    function updateFilter(f: FighterFilter) {
        filter.value = f;
    }

    async function createFighter(payload: FormData) {
        try {
            const res = await fighterService._createFighter(payload);
            fighters.value.push(res);
        } catch (error) {
            throw error;
        }
    }

    async function getFighter(id: string) {
        try {
            const res = await fighterService._getFighter(id);
            fighter.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function getFighters(): Promise<IFighter[]> {
        try {
            const res = await fighterService._getFighters();
            fighters.value = res;
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function updateFighter(payload: FormData, id: string) {
        try {
            const res = await fighterService._upadateFighter(payload, id);
            const fighterToUpdate = fighters.value.findIndex(fighter => fighter.id === id);
            fighters.value.splice(fighterToUpdate, 1, res);
            fighter.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function deleteFighter(id: string) {
        try {
            const res = await fighterService._deleteFighter(id);
            await getFighters();
            return res;
        } catch (error) {
            throw error;
        }
    }

    return { fighter, fighters, filteredFighters, filter, getFighter, getFighters, fighterHistoryMatches, fighterWL, updateFighter, createFighter, deleteFighter, updateFilter }
});