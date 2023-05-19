<template>
    <div>
        <v-dialog v-model="dialog" class="w-2/3">
            <template v-slot:activator="{ props }">
                <v-btn color="primary" variant="tonal" v-bind="props"> Create fight </v-btn>
            </template>

            <v-card class="text-center">
                <v-card-title class="font-bold p-10">
                    Create a fight
                </v-card-title>
                <div class="w-full flex px-10">
                    <v-form class="flex flex-col w-full" v-model="valid" ref="form">
                        <div>
                            <v-select v-model="fighterA" :rules="[rules.required]" label="fighter one"
                                :items="fighterItems" />
                            <v-select v-model="fighterB" :rules="[rules.required, rules.sameFighter]" label="Against"
                                :items="fighterItems" :disabled="!fighterA" />
                            <v-text-field v-model="fightDate" :rules="[rules.required, rules.date, rules.logicDate]"
                                type="datetime-local" label="Fight date" />
                        </div>
                    </v-form>
                </div>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="resetForm()">Cancel</v-btn>
                        <v-btn color="secondary" @click="submit()">Create</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts" setup>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { useFightStore } from '@/stores/fight';
import { CreateFight } from '@/interfaces/fight';
import { useFighterStore } from '@/stores/fighter';
import { useEventStore } from '@/stores/event';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';

const route = useRoute();
const fighterStore = useFighterStore();
const { getFighters } = fighterStore;
const { fighters } = storeToRefs(fighterStore)
const fightStore = useFightStore();
const { createFight } = fightStore;
const eventStore = useEventStore();
const { event } = storeToRefs(eventStore);

const form = ref();
const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);
const fighterA = ref<string>('');
const fighterB = ref<string>('');
const fightDate = ref<string>('');

onMounted(async () => {
    try {
        fighters.value = await getFighters();
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
    date: (value: any) => new Date(value) >= new Date() || 'Date must be in the future',
    sameFighter: (value: any) => fighterA.value !== fighterB.value || 'The fighter cannot fight him self',
    logicDate: (value: any) => value >= event.value?.timeStart && value < event.value?.timeEnd
        || `Fight date must be between ${new Date(event.value?.timeStart).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' })} and ${new Date(event.value?.timeEnd).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' })}`,
};

let fight = reactive<CreateFight>({
    event: '',
    fighterA: '',
    fighterB: '',
    fightDate: ''
})

watch(dialog, (open: boolean) => {
    if (!open) {
        resetForm()
    }
})

const resetForm = () => {
    fight = {
        event: '',
        fighterA: '',
        fighterB: '',
        fightDate: ''
    }
    dialog.value = false;
}

const submit = async () => {
    try {
        const { valid } = await form.value.validate();
        if (valid) {
            fight.event = route.params.id.toString();
            fight.fighterA = fighterA.value;
            fight.fighterB = fighterB.value;
            fight.fightDate = fightDate.value;
            await createFight(fight);
            dialog.value = false;
        }
    } catch (error: any) {
        createToast(error, { position: 'bottom-right', type: 'danger' });
    }
};


</script>
