<template>
    <v-dialog v-model="dialog" class="w-2/3">
        <template v-slot:activator="{ props }">
            <v-btn v-bind="props" color="secondary" variant="tonal" :disabled="disabled" class="elevation-2 flex-1">
                <Icon icon="material-symbols:edit" height="1rem" />
            </v-btn>
        </template>

        <v-card class="text-center">
            <v-card-title class="font-bold p-10">
                Update a figth
            </v-card-title>
            <div class="w-full flex px-10">
                <v-form class="flex flex-col w-full" v-model="valid" ref="form">
                    <div>
                        <v-select v-model="fighterA" label="fighter one" :items="fighterItems" />
                        <v-select v-model="fighterB" label="Against" :items="fighterItems" :disabled="!fighterA" />
                    </div>
                </v-form>
            </div>
            <v-card-actions>
                <v-row justify="end" class="px-4">
                    <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                    <v-btn color="secondary" @click="submit()">Update</v-btn>
                </v-row>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" setup>
import { Icon } from '@iconify/vue';
import { ref, reactive, onMounted, computed, watch } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { useFightStore } from '@/stores/fight';
import { UpdateFight } from '@/interfaces/figth';
import { useFighterStore } from '@/stores/fighter';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';
import { PropType } from 'vue';

const props = defineProps({
    fightId: {
        type: String as PropType<string>,
        required: true
    },
    disabled: {
        type: Boolean as PropType<boolean>,
        required: true
    }
});

const route = useRoute();
const fighterStore = useFighterStore();
const { getFighters } = fighterStore;
const { fighters } = storeToRefs(fighterStore)
const fightStore = useFightStore();
const { getFight, updateFight } = fightStore;

const form = ref();
const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);
const fighterA = ref<string>('');
const fighterB = ref<string>('');

onMounted(async () => {
    try {
        fighters.value = await getFighters();
        const currentFight = await getFight(props.fightId);
        fighterA.value = currentFight.fighterA.id;
        fighterB.value = currentFight.fighterB.id;
    } catch (error) {

    }
});

const fighterItems = computed(() => {
    return fighters.value.map(fighter => {
        return {
            title: `${fighter.firstname} ${fighter.lastname}`,
            value: fighter.id
        }
    })
})

const rules = {
    required: (value: any) => !!value || 'Required.',
};

const fight = reactive<UpdateFight>({
    id: props.fightId,
    fighterA: '',
    fighterB: ''
})

const submit = async () => {
    try {
        const { valid } = await form.value.validate();
        if (valid) {
            fight.fighterA = fighterA.value;
            fight.fighterB = fighterB.value;
            await updateFight(fight);
            dialog.value = false;
        }
    } catch (error: any) {
        createToast(error, { position: 'bottom-right', type: 'danger' });
    }
};
</script>
