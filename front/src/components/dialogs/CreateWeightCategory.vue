<template>
    <div>
        <v-dialog v-model="dialog" max-width="50%">
            <template v-slot:activator="{ props }">
                <v-btn color="primary" v-bind="props"> Create a category </v-btn>
            </template>

            <v-card>
                <v-card-title> Create a category </v-card-title>
                <v-container>
                    <v-form v-model="valid" ref="form">
                        <v-row justify="space-between" class="align-center">
                            <v-col>
                                <v-text-field v-model="category.name" :rules="[rules.required]" placeholder="Name"
                                    label="Name" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model.number="category.minWeight"
                                    :rules="[rules.required, rules.positiveValue]" placeholder="minimal weight requirement"
                                    label="Minimal weight" type="number" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model.number="category.maxWeight"
                                    :rules="[rules.required, rules.positiveValue, rules.higherThanMinimal]"
                                    placeholder="maximal weight" label="Maximal weight" type="number" />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                        <v-btn color="secondary" @click="submit()">Create</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref, computed, reactive } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { CreateWeightCategory } from '@/interfaces/payload';
import { useCategoryStore } from '@/stores/category';

export default defineComponent({
    setup() {
        const categoryStore = useCategoryStore();
        const { postCategory } = categoryStore;

        const form = ref();
        const dialog = ref<boolean>(false);
        const valid = ref<boolean>(false);

        const category = reactive<CreateWeightCategory>({
            name: '',
            minWeight: 0,
            maxWeight: 0
        });

        const rules = {
            required: (value: any) => !!value || 'Required.',
            positiveValue: (value: any) => value > 0 || 'Weight cannot be negative',
            higherThanMinimal: (value: any) => value > category.minWeight || 'Maximal weight need to be superior then minimal weight',
        };

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

        return { dialog, valid, rules, submit, form, category };
    },
});
</script>
