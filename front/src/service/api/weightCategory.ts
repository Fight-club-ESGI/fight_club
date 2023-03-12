import { client } from "../index";

const namespace = "/categories";

class WeightCategory {

    _getCategories = async () => {
        try {
            const uri = namespace;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    _postCategory = async (payload: any) => {
        try {
            const uri = namespace;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    _updateCategory = async (category: any) => {
        try {
            const uri = `${namespace}/${category.id}`;
            const res = await client.post(uri, category);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const weightCategoryService = new WeightCategory();

export default weightCategoryService;