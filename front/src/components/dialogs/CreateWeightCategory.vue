<template>
    <div>
        <v-dialog v-model="dialog" max-width="50%">
            <template v-slot:activator="{ props }">
                <v-btn color="primary" v-bind="props" variant="tonal"> Create a category </v-btn>
            </template>

            <v-card class="text-center">
                <v-card-title class="font-bold p-10"> Create a category </v-card-title>
                <div class="w-full flex px-10">
                    <v-form v-model="valid" ref="form" class="flex flex-col w-full">

                        <v-text-field v-model="category.name" :rules="[rules.required]" placeholder="Name" label="Name" />

                        <v-text-field v-model.number="category.minWeight" :rules="[rules.required, rules.positiveValue]"
                            placeholder="minimal weight requirement" label="Minimal weight" type="number" />

                        <v-text-field v-model.number="category.maxWeight"
                            :rules="[rules.required, rules.positiveValue, rules.higherThanMinimal]"
                            placeholder="maximal weight" label="Maximal weight" type="number" />

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
import { watch, ref, reactive } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { CreateWeightCategory } from '@/interfaces/payload';
import { useCategoryStore } from '@/stores/category';


const categoryStore = useCategoryStore();
const { postCategory } = categoryStore;

const form = ref();
const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);

let category = reactive<CreateWeightCategory>({
    name: '',
    minWeight: 0,
    maxWeight: 0
});

watch(dialog, (open: boolean) => {
    if (!open) {
        resetForm()
    }
});

const rules = {
    required: (value: any) => !!value || 'Required.',
    positiveValue: (value: any) => value > 0 || 'Weight cannot be negative',
    higherThanMinimal: (value: any) => value > category.minWeight || 'Maximal weight need to be superior then minimal weight',
};

const resetForm = () => {
    category = {
        name: '',
        minWeight: 0,
        maxWeight: 0
    }
    dialog.value = false;
}
const submit = async () => {
    try {
        const { valid } = await form.value.validate();
        if (valid) {
            await postCategory(category);
            dialog.value = false;
            category.name = '';
            (category.minWeight = 0), (category.maxWeight = 0);
        }
    } catch (error: any) {
        createToast(error, { position: 'bottom-right', type: 'danger' });
    }
};
</script>
