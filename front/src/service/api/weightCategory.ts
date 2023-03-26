import { client } from "../index";
import { WeightCategoryP, WeightInterface } from "../../interfaces/payload";
const namespace = "/weight_categories";

class WeightCategory {

    _getCategories = async (): Promise<WeightCategory[]> => {
        try {
            const uri = namespace;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    _postCategory = async (payload: WeightCategoryP): Promise<WeightCategory> => {
        try {
            const uri = namespace;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    _updateCategory = async (category: WeightInterface): Promise<WeightCategory> => {
        try {
            const uri = `${namespace}/${category.id}`;
            const res = await client.put(uri, category);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    _deleteCategory = async (categoryId: string) => {
        try {
            const uri = `${namespace}/${categoryId}`;
            const res = await client.delete(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const weightCategoryService = new WeightCategory();

export default weightCategoryService;