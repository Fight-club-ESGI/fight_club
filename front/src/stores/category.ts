import { defineStore } from "pinia";
import { ref, Ref } from "vue";
import { weightCategoryService } from "../service/api";
import { WeightInterface, WeightInterfaceI } from "../../interfaces/payload";

export const useCategoryStore = defineStore('category', () => {

    const categories: Ref<WeightInterface[]> = ref<WeightInterface[]>([]);

    async function getCategories(): Promise<WeightInterface[]> {
        try {
            const res = await weightCategoryService._getCategories();
            categories.value = res;
            return res;
        } catch (error) {
            return error;
        }
    }

    async function postCategory(category: WeightInterfaceI): Promise<WeightInterface> {
        try {
            const res = await weightCategoryService._postCategory(category);
            categories.value.push(res);
            return res;
        } catch (error) {
            return error;
        }
    }

    async function updateCategory(category: Partial<WeightInterface>): Promise<WeightInterface> {
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
            const categoryToRemove = categories.value.findIndex(category => category.id === categoryId);
            categories.value.splice(categoryToRemove, 1);
            return res.data;
        } catch (error) {
            return error;
        }
    }

    return { categories, getCategories, postCategory, updateCategory, deleteCategory }
});