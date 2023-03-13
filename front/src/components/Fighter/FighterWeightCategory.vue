<template>
    <v-list :items="categories"></v-list>
</template>

<script lang="ts">
import { useCategoryStore } from '@/stores/category';
import { createToast } from 'mosha-vue-toastify';
import { defineComponent, onMounted } from 'vue';
import { storeToRefs } from 'pinia';

export default defineComponent({
    setup() {
        const categoryStore = useCategoryStore();
        const { getCategories } = categoryStore;
        const { categories } = storeToRefs(categoryStore);

        onMounted(async () => {
            try {
                await getCategories();
            } catch (error) {
                createToast('Error while getting categories', { type: 'danger', position: 'bottom-right' });
            }
        });

        return { categories };
    },
});
</script>
