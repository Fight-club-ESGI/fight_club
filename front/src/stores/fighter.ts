import { defineStore } from "pinia";
import { FighterI } from "../interfaces/payload";
import { ref, computed, ComputedRef, Ref } from "vue";
import { fighterService } from "../service/api/index";

export const useFighterStore = defineStore('fighter', () => {

    const fighter = ref<FighterI>();
    const fighters = ref<FighterI[]>([]);

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

    const filteredFighters: ComputedRef<FighterI[]> = computed(() => {
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

    async function createFighter(payload: FighterI) {
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

    async function getFighters() {
        try {
            const res = await fighterService._getFighters();
            fighters.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function updateFighter(payload: FighterI) {
        try {
            const res = await fighterService._upadateFighter(payload);
            const fighterToUpdate = fighters.value.findIndex(fighter => fighter.id === payload.id);
            fighters.value.splice(fighterToUpdate, 1, res);
            fighter.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function deleteFighter(id: string) {
        try {
            const res = await fighterService._deleteFighter(id);
            const fighterToRemove = fighters.value.findIndex(fighter => fighter.id === id);
            fighters.value.splice(fighterToRemove, 1);
            return res;
        } catch (error) {
            throw error;
        }
    }

    return { fighter, fighters, filteredFighters, filter, getFighter, getFighters, updateFighter, createFighter, deleteFighter, updateFilter }
});