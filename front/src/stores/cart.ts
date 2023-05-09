import { defineStore } from "pinia";
import { ref } from "vue";
import { cartService } from "../service/api";
import { CartInterface } from "@/interfaces/responseAPI";
import { ITicketEvent } from "@/interfaces/event";

export const useCartStore = defineStore('cart', () => {

    const cart = ref<CartInterface>();

    const cartHistory = ref<Array<CartInterface>>([
        {
            id: null,
            amount: null,
            status: null,
            wallet: null,
            type: null,
            stripe_ref: null,
            createdAt: null,
            updatedAt: null
        }
    ]);

    async function getCartHistory() {
        try {
            cartHistory.value = await cartService._cartHistory();
        } catch (error) {
            throw error;
        }
    }

    async function addToCart(payload: Array<ITicketEvent>) {
        try {
            cart.value = await cartService._addToCart(payload);
        } catch (error) {
            throw error;
        }
    }

    async function transactionConfirmation(transaction_id: string) {
        try {
            cart.value = await cartService._confirmation(transaction_id);

            if (cartHistory) {

                let idx = cartHistory.value.findIndex(transaction => transaction.id === cart.value.id)
                if (idx !== -1) {
                    cartHistory.value[idx] = cart.value;
                }
            }
        } catch (error) {
            throw error;
        }
    }

    return { cart, cartHistory, getCartHistory, transactionConfirmation }
});
