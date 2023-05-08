import { client, clientWithoutAuth } from "..";
import type { CartInterface, CartItemInterface } from "../../interfaces/responseAPI";

const cartURL = '/carts';
const cartItemURL = '/cart_items';

class Cart {

    async _getCart(): Promise<CartInterface> {
        try {
            const uri = `/my-cart`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _addToCart(payload: { productId: string, quantity: number }): Promise<CartInterface> {
        try {
            const uri = `${cartItemURL}`;
            const res = await client.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _removeFromCart(payload: CartItemInterface): Promise<CartInterface> {
        try {
            const uri = `${cartItemURL}/${payload.id}`
            const res = await client.patch(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _clearCart(): Promise<void> {
        try {
            const cart = await this._getCart();
            const payload = {
                ...cart,
                cartItems: []
            }
            const uri = `${cartURL}/${cart.id}`;
            const res = await client.patch(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const cartService = new Cart();

export default cartService;