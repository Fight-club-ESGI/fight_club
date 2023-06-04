<template>
    <v-card @click="goToFighterDetails()" class="cursor-pointer h-92 relative bg-neutral-800 text-white">
        <div :style="fighter.imageName ? `background-image: url('${fighter.imageName}')` : `background-image: url('https://api.multiavatar.com/${fighter.firstname}${fighter.lastname}.png?apikey=XdoCH30EA6grGx')`"
            class="h-1/2 bg-cover bg-center">
            <div class="h-full w-full bg-gradient-to-t from-neutral-800 to-transparent" />
        </div>
        <div class="pa-5 h-1/2 flex flex-column relative overflow-auto">
            <v-menu v-if="admin && pathIncludeAdmin && !preview">
                <template v-slot:activator="{ props }">
                    <v-btn class="absolute right-4 p-1 rounded" color="transparent" v-bind="props"
                        icon="mdi-dots-horizontal" size="x-medium" />
                </template>
                <v-list class="p-0 text-center">
                    <v-list-item value="update-event">
                        <update-fighter :fighter="fighter" :admin="admin" />
                    </v-list-item>
                    <v-list-item value="delete-fighter" class="bg-secondary"
                        @click="deleteF(fighter.id)">Delete</v-list-item>
                </v-list>
            </v-menu>
            <div class="flex items-center">
                <div class="text-2xl font-bold w-2/3 truncate">{{ fighterName }}</div>
                <Icon class="text-2xl ml-2" :icon="fighter.gender === 'male' ? 'mdi:gender-male' : 'mdi:gender-female'" />
            </div>
            <div class="mt-auto flex flex-column gap-y-1">
                <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-lg">
                    <Icon icon="material-symbols:flag" />
                    <p class="text-sm font-bold">{{ fighter.nationality }}</p>
                </div>
                <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-lg">
                    <Icon icon="material-symbols:weight" />
                    <p class="text-sm font-bold">{{ fighter.weight }} kg</p>
                </div>
                <div class="flex align-center gap-2 bg-neutral-600 p-1 rounded-lg">
                    <Icon icon="mdi:human-male-height-variant" />
                    <p class="text-sm font-bold">{{ fighter.height }} cm</p>
                </div>
            </div>
        </div>
    </v-card>
</template>
<script lang="ts" setup>
import UpdateFighter from '../dialogs/UpdateFighter.vue';
import { Icon } from '@iconify/vue';
import { toRefs, PropType, computed, ref } from 'vue';
import { IFighter } from '@/interfaces/fighter';
import { useRoute, useRouter } from 'vue-router';
import { useFighterStore } from '@/stores/fighter';

const props = defineProps({
    fighter: { type: Object as PropType<IFighter>, required: true },
    admin: { type: Boolean, default: false },
    preview: { type: Boolean, default: false },
})

const { fighter } = toRefs(props);
const router = useRouter();
const route = useRoute()

const fighterStore = useFighterStore();
const { deleteFighter } = fighterStore;

const pathIncludeAdmin = ref(route.path.includes('admin'))

const fighterName = computed(() => {
    return `${fighter.value.firstname} ${fighter.value.lastname}`;
});

const goToFighterDetails = () => {
    router.push({ name: 'fighter-details', params: { id: fighter.value.id } });
};

const deleteF = async (fighterId: string) => {
    try {
        await deleteFighter(fighterId);
    } catch (error) {
        console.error(error)
    }
};

</script>
