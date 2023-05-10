<script setup lang="ts">
import UpdateFight from "@/components/dialogs/UpdateFight.vue"
import { IFight } from '@/interfaces/figth';
import { PropType, computed } from 'vue';
import { Icon } from "@iconify/vue/dist/iconify.js";
import { useFightStore } from '@/stores/fight';
const fightStore = useFightStore();
const { removeFight } = fightStore

const props = defineProps({
    fight: {
        type: Object as PropType<IFight>,
        required: true
    }
});

const fullNameFighterA = computed(() => {
    return `${props.fight.fighterA.firstname} ${props.fight.fighterA.lastname}`
})

const fullNameFighterB = computed(() => {
    return `${props.fight.fighterB.firstname} ${props.fight.fighterB.lastname}`
})

const remove = async (id: string) => {
    try {
        await removeFight(id);
    } catch (error) {

    }
}

</script>

<template>
    <v-card class="pa-4">
        <div class="flex justify-center flex-col">
            <div class="flex justify-around gap-x-3 pb-3">
                <div class="rounded-xl pa-2 elevation-2">

                    <v-img height="50" width="50"
                        src="https://upload.wikimedia.org/wikipedia/commons/9/95/Vue.js_Logo_2.svg"></v-img>
                </div>
                <div class="border rounded-xl pa-2 elevation-2">
                    <v-img height="50" width="50"
                        src="https://upload.wikimedia.org/wikipedia/commons/a/ae/Nuxt_logo.svg"></v-img>
                </div>
            </div>
            <div class="text-center py-2">
                <p class="text-lg font-semibold">{{ fullNameFighterA }}</p>
                <p>VS</p>
                <p class="text-lg font-semibold">{{ fullNameFighterB }}</p>
            </div>
            <v-card-actions>
                <update-fight :fight-id="props.fight.id"></update-fight>
                <v-btn @click="remove(fight.id)" color="primary" variant="tonal" class="elevation-2 flex-1">
                    <Icon icon="material-symbols:delete-rounded" height="1rem" />
                </v-btn>
            </v-card-actions>
        </div>
    </v-card>
</template>