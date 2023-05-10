<script setup lang=ts>
import { IFight } from '@/service/api/fight';
import { PropType, computed } from 'vue';

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

</script>

<template>
    <div class="flex items-center gap-x-4 pa-3 hover:bg-surface/50 cursor-pointer hover:rounded-lg">
        <v-icon icon="mdi-remove"></v-icon>
        <v-card class="pa-3 flex-1" variant="tonal">
            <template #title>{{ fullNameFighterA }} <v-icon
                    :icon="props.fight.fighterA.gender === 'male' ? 'mdi-male' : 'mdi-woman'">
                </v-icon>
            </template>
            <v-card-item>{{ props.fight.fighterA.height }} cm | {{ props.fight.fighterA.weight }} kg | {{
                props.fight.fighterA.nationality }}</v-card-item>
        </v-card>
        <span class="text-2xl font-bold text-primary">VS</span>
        <v-card class="pa-3 flex-1" variant="tonal">
            <template #title>{{ fullNameFighterB }} <v-icon
                    :icon="props.fight.fighterA.gender === 'female' ? 'mdi-woman' : 'mdi-male'">
                </v-icon>
            </template>
            <v-card-item>
                {{ props.fight.fighterB.height }} cm | {{ props.fight.fighterB.weight }} kg | {{
                    props.fight.fighterB.nationality }}
            </v-card-item>
        </v-card>
    </div>
</template>