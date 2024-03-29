<template>
    <create-weight-category />
    <div class="mt-2">
        <v-list v-if="categories.length > 0">
            <v-list-item v-for="category in categories" :key="category.id" :value="category.id">
                <template #title>
                    {{ category.name }}
                </template>

                <template #subtitle>
                    <span class="text-green font-bold text-lg">{{ category.minWeight }} kg</span> -
                    <span class="text-red font-bold text-lg">{{ category.maxWeight }} kg</span>
                </template>

                <template #append>
                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" size="small" v-bind="props" icon="mdi-dots-horizontal"></v-btn>
                        </template>

                        <v-list>
                            <v-list-item value="update-category"><update-category :category="category" /> </v-list-item>
                            <v-list-item @click="remove(category)" value="delete-category" color="red-darken-1"> Delete
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-list-item>
        </v-list>
        <template v-else>
            <v-alert variant="outlined" title="No categories yet"
                text="Click the 'create a category' button to create a category"> </v-alert>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { useCategoryStore } from '@/stores/category';
import { createToast } from 'mosha-vue-toastify';
import { defineComponent, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import CreateWeightCategory from '@/components/dialogs/CreateWeightCategory.vue';
import UpdateCategory from '@/components/dialogs/UpdateCategory.vue';
import { IWeightCategory } from '@/interfaces/payload';

const categoryStore = useCategoryStore();
const { getCategories, deleteCategory } = categoryStore;
const { categories } = storeToRefs(categoryStore);

onMounted(async () => {
    try {
        await getCategories();
    } catch (error) {
        createToast('Error while getting categories', { type: 'danger', position: 'bottom-right' });
    }
});

const remove = async (category: IWeightCategory) => {
    try {
        await deleteCategory(category.id);
    } catch (error) {
        // @ts-ignore
        createToast(error, { type: 'danger', position: 'bottom-right' });
    }
};

</script>
