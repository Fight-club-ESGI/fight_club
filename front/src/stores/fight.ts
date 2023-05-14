import { defineStore, storeToRefs } from "pinia";
import { ref } from "vue";
import { fightService } from "../service/api/index";
import { CreateFight, IFight, UpdateFight } from "@/interfaces/figth";
import { useEventStore } from "./event";

export const useFightStore = defineStore('fight', () => {

    const eventStore = useEventStore();
    const { getEvent } = eventStore;
    const { event } = storeToRefs(eventStore);
    const fight = ref<IFight>();
    const fights = ref<IFight[]>([]);

    async function getFight(fightId: string): Promise<IFight> {
        try {
            const res = await fightService._getFight(fightId);
            fight.value = res;
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function getFights(): Promise<IFight[]> {
        try {
            const res = await fightService._getFights();
            fights.value = res;
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function createFight(payload: CreateFight): Promise<IFight> {
        try {
            const res = await fightService._createFight(payload);
            await getEvent(payload.event);
            fights.value.push(res);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function validateFight(fightId: string): Promise<string> {
        try {
            const res = await fightService._validateFight(fightId);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function selectWinner(payload: { fightId: string, winnerId: string }): Promise<string> {
        try {
            const res = await fightService._selectWinner(payload);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function removeFight(id: string): Promise<void> {
        try {
            const res = await fightService._removeFight(id);
            fights.value = await fightService._getFights();
            // @ts-ignore
            await getEvent(event.value?.id);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function updateFight(payload: UpdateFight): Promise<IFight> {
        try {
            const res = await fightService._updateFight(payload);
            fights.value = await fightService._getFights();
            // @ts-ignore
            await getEvent(event.value?.id);
            return res;
        } catch (error) {
            throw error;
        }
    }

    return { getFight, getFights, createFight, validateFight, selectWinner, removeFight, updateFight, fight, fights }
});