<template>
    <div>
        <v-dialog v-model="dialog" max-width="75%">
            <template v-slot:activator="{ props }">
                <span v-bind="props"> Update </span>
            </template>

            <v-card v-if="category">
                <v-card-title> Update category </v-card-title>
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
                        <v-btn color="secondary" @click="submit()">Update</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { IWeightCategory } from '@/interfaces/payload';
import { useCategoryStore } from '@/stores/category';

export default defineComponent({
    props: {
        category: {
            type: Object as PropType<IWeightCategory>,
            required: true
        },
    },
    setup(props) {
        // Shallow copy of category from pinia store
        const category = ref({ ...props.category });
        const categoryStore = useCategoryStore();
        const { updateCategory } = categoryStore;

        const form = ref();
        const dialog = ref<boolean>(false);
        const valid = ref<boolean>(false);

        const rules = {
            required: (value: any) => !!value || 'Required.',
            positiveValue: (value: any) => value > 0 || 'Weight cannot be negative',
            higherThanMinimal: (value: any) => value > category.value.minWeight || 'Maximal weight need to be superior then minimal weight',
        };

        const submit = async () => {
            try {
                const { valid } = await form.value.validate();
                if (valid) {
                    await updateCategory({
                        id: category.value.id,
                        name: category.value.name,
                        minWeight: category.value.minWeight,
                        maxWeight: category.value.maxWeight,
                    });
                    dialog.value = false;
                }
            } catch (error: any) {
                createToast(error, { position: 'bottom-right', type: 'danger' });
            }
        };

        return { dialog, valid, rules, submit, form, category };
    },
});
</script>
