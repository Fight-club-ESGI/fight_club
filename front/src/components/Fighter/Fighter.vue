<template>
    <v-card
        class="cursor-pointer h-92 relative bg-neutral-800 text-white"
    >
        <div :style="fighter.imageName ? `background-image: url('${fighter.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`"
             class="h-1/2 bg-cover bg-center">
            <div class="h-full w-full bg-gradient-to-t from-neutral-800 to-transparent" />
        </div>
        <div class="pa-5 h-1/2 flex flex-column relative overflow-auto">
            <v-menu v-if="admin && pathIncludeAdmin && !preview" >
                <template v-slot:activator="{ props }">
                    <v-btn class="absolute right-4 p-1 rounded" color="transparent" v-bind="props" icon="mdi-dots-horizontal"
                           size="x-medium" />
                </template>
                <v-list class="p-0 text-center">
                    <v-list-item value="update-event">
                        <update-fighter :fighter="fighter" />
                    </v-list-item>
                    <v-list-item value="delete-fighter" class="bg-secondary" @click="deleteE(event.id)">Delete</v-list-item>
                </v-list>
            </v-menu>
            <p class="text-2xl font-bold pb-5">{{ fighterName }}</p>
            <div class="mt-auto flex gap-x-4">
                <div class="flex align-center gap-2 bg-neutral-600 p-2 rounded-lg">
                    <Icon icon="material-symbols:flag" />
                    <p class="text-sm font-bold">{{ fighter.nationality }}</p>
                </div>
                <div class="flex align-center gap-2 bg-neutral-600 p-2 rounded-lg">
                    <Icon icon="material-symbols:weight" />
                    <p class="text-sm font-bold">{{ fighter.weight }} kg</p>
                </div>
                <div class="flex align-center gap-2 bg-neutral-600 p-2 rounded-lg">
                    <Icon icon="mdi:human-male-height-variant" />
                    <p class="text-sm font-bold">{{ fighter.height }} cm</p>
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
    </v-card>
</template>
<script lang="ts" setup>
import UpdateFighter from '../dialogs/UpdateFighter.vue';
import { Icon } from '@iconify/vue';
import {toRefs, PropType, computed, ref} from 'vue';
import { IFighter } from '@/interfaces/fighter';
import {useRoute, useRouter} from 'vue-router';
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
