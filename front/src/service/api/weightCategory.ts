import { client } from "../index";
import { CreateWeightCategory, IWeightCategory } from "@/interfaces/payload";
const namespace = "/weight_categories";

class WeightCategory {

    _getCategories = async (): Promise<IWeightCategory[]> => {
        try {
            const uri = namespace;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    _postCategory = async (payload: CreateWeightCategory): Promise<IWeightCategory> => {
        try {
            const uri = namespace;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    _updateCategory = async (category: Partial<IWeightCategory>): Promise<IWeightCategory> => {
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