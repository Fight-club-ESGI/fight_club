<template>
    <v-card class="pa-4">
        <div class="flex justify-center flex-col">
            <div class="flex justify-center gap-x-3 pb-3">
                <div class="rounded-xl pa-2 elevation-2">

                    <v-img height="50" width="50"
                        src="https://upload.wikimedia.org/wikipedia/commons/9/95/Vue.js_Logo_2.svg"></v-img>
                </div>
            </div>
            <div class="text-left py-2">
                <div class="pa-3 flex flex-col gap-y-2 items-start">
                    <h3 class="text-center text-lg font-semibold pb-2 whitespace-nowrap">{{ fighterName }}</h3>
                    <div class="flex items-center">
                        <Icon icon="material-symbols:flag" />
                        <p class="pl-2 whitespace-nowrap">{{ fighter.nationality }}</p>
                    </div>
                    <div class="flex items-center">
                        <Icon icon="material-symbols:weight" />
                        <p class="pl-2">{{ fighter.weight }} kg</p>
                    </div>
                    <div class="flex items-center">
                        <Icon icon="mdi:human-male-height-variant" />
                        <p class="pl-2">{{ fighter.height }} cm</p>
                    </div>
                </div>
            </div>
            <div>
                <v-card-actions>
                    <div v-if="isAdmin" class="flex flex-1">
                        <update-fighter :fighter="fighter" />
                        <v-btn @click="remove(fighter.id)" color="primary" variant="tonal" class="elevation-2 flex-1">
                            Delete
                        </v-btn>
                    </div>
                    <v-btn v-else @click="goToFighterDetails()" color="primary" variant="tonal" class="elevation-2 flex-1">
                        details
                    </v-btn>
                </v-card-actions>
            </div>
        </div>
    </v-card>
</template>
<script lang="ts" setup>
import UpdateFighter from '../dialogs/UpdateFighter.vue';
import { Icon } from '@iconify/vue';
import { toRefs, PropType, computed } from 'vue';
import { IFighter } from '@/interfaces/fighter';
import { useRouter } from 'vue-router';
import { useFighterStore } from '@/stores/fighter';

const props = defineProps({
    fighter: {
        type: Object as PropType<IFighter>,
        required: true,
        default: () => { },
    },
    isAdmin: {
        type: Boolean as PropType<boolean>,
        default: false
    }
})

const { fighter } = toRefs(props);
const router = useRouter();

const fighterStore = useFighterStore();
const { deleteFighter } = fighterStore;

const fighterName = computed(() => {
    return `${fighter.value.firstname} ${fighter.value.lastname}`;
});

const goToFighterDetails = () => {
    router.push({ name: 'fighter-details', params: { id: fighter.value.id } });
};

const remove = async (fighterId: string) => {
    try {
        await deleteFighter(fighterId);
    } catch (error) {
        console.error(error)
    }
};

</script>
