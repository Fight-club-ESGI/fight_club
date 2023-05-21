import { client } from "..";
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

    async _addToCart(payload: { cart: string, ticketEvent: string, quantity: number }): Promise<CartItemInterface> {
        try {

            const cart = await this._getCart();
            const cartItem = cart.cartItems.find(item => item.ticketEvent.id === payload.ticketEvent);
            if (cartItem) {
                const uri = `${cartItemURL}/${cartItem.id}`;
                const data = {
                    quantity: Math.min(10, cartItem.quantity + payload.quantity)
                }
                const res = await client.patch(uri, data);
                return res.data;
            }

            const uri = `${cartItemURL}`;
            const data = {
                cart: `/carts/${payload.cart}`,
                ticketEvent: `/ticket_events/${payload.ticketEvent}`,
                quantity: payload.quantity
            }
            const res = await client.post(uri, data);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _updateCartItem(payload: CartItemInterface): Promise<CartItemInterface> {


        try {
            payload.quantity = Math.min(10, Math.max(1, Number(payload.quantity)));
            const cart = await this._getCart();
            const uri = `${cartItemURL}/${payload.id}`;
            const data = {
                cart: `/carts/${cart.id}`,
                ticketEvent: `/ticket_events/${payload.ticketEvent.id}`,
                quantity: Number(payload.quantity)
            }
            const res = await client.patch(uri, data);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _removeFromCart(payload: CartItemInterface): Promise<CartInterface> {
        try {
            const uri = `${cartItemURL}/${payload.id}`
            const res = await client.delete(uri);

            const cart = await this._getCart();
            return cart;
        } catch (error) {
            throw error;
        }
    }

    async _clearCart(): Promise<CartInterface> {
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

    async _checkout(type: string): Promise<CartInterface> {
        try {
            const cart = await this._getCart();
            const uri = `${cartURL}/${cart.id}/checkout/${type}`;
            const res = await client.post(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const cartService = new Cart();

export default cartService;