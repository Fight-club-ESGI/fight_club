import { defineStore } from "pinia";
import { ref } from "vue";
import { weightCategoryService } from "../service/api";

export const useCategoryStore = defineStore('category', () => {

    const categories = ref([]);
    
    async function getCategories() {
        try {
            const res = await weightCategoryService._getCategories();
            categories.value = res;
            return res;
        } catch (error) {
            return error;
        }
    }

    async function postCategory(category: any) {
        try {
            const res = await weightCategoryService._postCategory(category);
            categories.value.push(res);
            return res;
        } catch (error) {
            return error;
        }
    }

    async function updateCategory(category: any) {
        try {
            const res = await weightCategoryService._updateCategory(category);
            const categoryToUpdate = categories.value.findIndex(category => category.id === res.id);
            categories.value.splice(categoryToUpdate, 1, res);
            return res;
        } catch (error) {
            return error;
        }
    }

    async function deleteCategory(categoryId: string) {
        try {
            const res = await weightCategoryService._deleteCategory(categoryId);
            const categoryToRemove = categories.value.findIndex(category => category.id === res.id);
            categories.value.splice(categoryToRemove, 1);
            return res.data;
        } catch (error) {
            return error;
        }
    }

    return { categories, getCategories, postCategory, updateCategory }
});