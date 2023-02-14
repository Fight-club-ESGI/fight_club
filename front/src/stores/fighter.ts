import { defineStore } from "pinia";
import { FighterI } from "../interfaces/payload";
import { ref } from "vue";
import { fighterService } from "../service/api/index";

export const useFighterStore = defineStore('fighter', () => {

    const fighter = ref<FighterI>();
    const fighters = ref<FighterI[]>([]);

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
            fighter.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function deleteFighter(id: string) {
        try {
            const res = await fighterService._deleteFighter(id);
        } catch (error) {
            throw error;
        }
    }

    return { fighter, fighters, getFighter, getFighters, updateFighter, createFighter, deleteFighter }
});