<template>
    <table v-if="fighters && fighters.length > 0" class="w-ful text-left pl-2 pb-2">
        <thead>
            <tr>
                <th>Icon</th>
                <th>Name</th>
                <th>Weight</th>
                <th>Division</th>
                <th>Height</th>
                <th>Nationality</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(fighter, i) of fighters" :key="fighter.id" :class="[i % 2 !== 1 ? 'bg-white' : ''] + ' h-10'">
                <td>
                    <v-avatar size="small"><v-img :src="`https://xsgames.co/randomusers/assets/avatars/pixel/43.jpg`"></v-img></v-avatar>
                </td>
                <td>{{ fighter.firstname }} {{ fighter.lastname }}</td>
                <td>{{ fighter.weight }} kg</td>
                <td>Division</td>
                <td>{{ fighter.height }} cm</td>
                <td>{{ fighter.nationality }}</td>
                <td>
                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" v-bind="props" icon="mdi-dots-horizontal" size="x-small" />
                        </template>
                        <v-list>
                            <v-list-item value="update-fighter"><update-fighter :fighter="fighter" /></v-list-item>
                            <v-list-item value="delete-fighter" @click="remove(fighter.id)">Delete</v-list-item>
                        </v-list>
                    </v-menu>
                </td>
            </tr>
        </tbody>
    </table>
    <v-alert v-else color="blue-grey-lighten-4">Create a fighter</v-alert>
</template>

<script lang="ts">
import { defineComponent, PropType, toRefs, ref } from 'vue';
import { FighterI } from '@/interfaces/payload';
import { useRouter } from 'vue-router';
import { useFighterStore } from '@/stores/fighter';
import UpdateFighter from '../dialogs/UpdateFighter.vue';
export default defineComponent({
    components: { UpdateFighter },
    props: {
        fighters: {
            type: Array as PropType<FighterI[]>,
        },
    },
    setup(props) {
        const { fighters } = toRefs(props);
        const router = useRouter();
        const fighterStore = useFighterStore();
        const { deleteFighter } = fighterStore;

        const goToFighterDetails = (fighter) => {
            router.push({ name: 'fighter-details', params: { id: fighter.value.id } });
        };

        const remove = async (fighterId: string) => {
            try {
                await deleteFighter(fighterId);
            } catch (error) {}
        };

        return { fighters, remove };
    },
});
</script>

<style scoped>
table {
    border-spacing: 1;
    border-collapse: collapse;
    background: white;
    border-radius: 6px;
    overflow: hidden;
    width: 100%;
    margin: 0 auto;
    position: relative;
}
td,
th {
    padding-left: 8px;
}

thead tr {
    height: 60px;
    background: #ffed86;
    font-size: 16px;
}
tbody tr {
    height: 48px;
    border-bottom: 1px solid #e3f1d5;
}
tbody tr:last-child {
    border: 0;
}
</style>
