import { defineStore } from "pinia";
import { ComputedRef, computed, ref } from "vue";
import { cartService } from "../service/api";
import { CartInterface, CartItemInterface } from "@/interfaces/responseAPI";

export const useCartStore = defineStore('cart', () => {

    const cart = ref<CartInterface>();

    const cartItems: ComputedRef<CartItemInterface[]> = computed(() => {
        if (cart.value === undefined) return [];
        return cart.value.cartItems;
    });

    const cartTotalItems: ComputedRef<number> = computed(() => {
        if (cart.value === undefined) return 0;
        return cart.value.cartItems.reduce((acc, item: CartItemInterface) => item.quantity ? acc += Number(item.quantity) : acc, 0);
    });

    const cartTotalPrice: ComputedRef<number> = computed(() => {
        if (cart.value === undefined) return 0;
        return cart.value.cartItems.reduce((acc, item: CartItemInterface) => item.quantity ? acc += Number(item.quantity * item.ticketEvent.price) : acc, 0);
    });

    async function getCart(): Promise<CartInterface> {
        try {
            const res = await cartService._getCart();
            cart.value = res;
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function addToCart(payload: { cart: string, ticketEvent: string, quantity: number }) {
        try {
            const cartItem = await cartService._addToCart(payload);
            let found = false;
            cart.value?.cartItems.forEach((item: CartItemInterface, index: number) => {
                if (item.ticketEvent.id === cartItem.ticketEvent.id) {
                    cart.value?.cartItems.splice(index, 1, cartItem);
                    found = true;
                }
            });
            if (!found) cart.value?.cartItems.push(cartItem);
        } catch (error) {
            throw error;
        }
    }

    async function updateCartItem(payload: CartItemInterface) {
        try {
            payload.quantity = Math.max(1, Math.min(10, payload.quantity));
            const cartItem = await cartService._updateCartItem(payload);
            cart.value?.cartItems.forEach((item: CartItemInterface, index: number) => {
                if (item.id === cartItem.id) {
                    cart.value?.cartItems.splice(index, 1, cartItem);
                }
            });

        } catch (error) {
            throw error;
        }
    }

    async function removeFromCart(payload: CartItemInterface) {
        try {
            cart.value = await cartService._removeFromCart(payload);
        } catch (error) {
            throw error;
        }
    }

    async function clearCart() {
        try {
            const res = await cartService._clearCart();
            cart.value = res;
        } catch (error) {
            throw error;
        }
    }

    async function checkout(type: string) {
        try {
            const res = await cartService._checkout(type);
            cart.value = res;
        } catch (error) {
            throw error;
        }
    }

    return { cart, cartItems, cartTotalItems, cartTotalPrice, getCart, addToCart, updateCartItem, removeFromCart, clearCart, checkout };
});
