import { defineStore } from "pinia";
import { weightCategoryService } from "../service/api";

export const useCategoryStore = defineStore('category', () => {

    async function getCategories() {
        try {
            const res = await weightCategoryService._getCategories();
            return res;
        } catch (error) {
            return error;
        }
    }

    async function postCategory(category: any) {
        try {
            const res = await weightCategoryService._postCategory(category);
            return res;
        } catch (error) {
            return error;
        }
    }

    async function updateCategory(category: any) {
        try {
            const res = await weightCategoryService._updateCategory(category);
            return res;
        } catch (error) {
            return error;
        }
    }

    return { getCategories, postCategory, updateCategory }
});