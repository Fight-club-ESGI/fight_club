import { client, clientWithoutAuth } from "..";
import type { CartInterface } from "../../interfaces/responseAPI";

const namespace = '/orders';

class Cart {

    async _getCarts(): Promise<CartInterface[]> {
        try {
            const res = await client.get(namespace);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getCart(id: string): Promise<CartInterface> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _addToCart(payload: { productId: string, quantity: number }): Promise<CartInterface> {
        try {
            const uri = `${namespace}/add`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _createCart(payload: CartInterface): Promise<CartInterface> {
        try {
            const res = await client.post(namespace, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _upadateCart(payload: CartInterface): Promise<CartInterface> {
        try {
            const uri = `${namespace}/${payload.id}`
            const res = await client.patch(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _deleteCart(id: string): Promise<void> {
        try {
            const uri = `${namespace}/${id}`;
            const res = await client.delete(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const cartService = new Cart();

export default cartService;