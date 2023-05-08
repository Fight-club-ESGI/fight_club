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

    async function addToCart(payload: { productId: string, quantity: number }) {
        try {
            cart.value = await cartService._addToCart(payload);
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
            await cartService._clearCart();
            cart.value = undefined;
        } catch (error) {
            throw error;
        }
    }

    return { cart, cartItems, addToCart, removeFromCart, clearCart };
});
