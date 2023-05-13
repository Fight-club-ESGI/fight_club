<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div class="flex h-full space-x-5 p-5 justify-center">
            <div class="flex flex-col h-full min-w-2/4 rounded">
                <div class="flex font-bold text-2xl h-1/12 items-center">Your cart</div>
                <div class="h-11/12">
                    <div class="text-center bg-neutral-100 p-10 rounded">
                        <div v-if="cartTotalItems === 0">
                            <div class="font-bold text-3xl p-6">Your cart is empty</div>
                        </div>
                        <div v-for="item in cartItems" :key="item.id" class="flex flex-row space-x-5">
                            <div class="flex flex-col w-full">
                                <div class="font-bold text-xl">
                                    {{ item.ticketEvent.event.name }} - {{ item.ticketEvent.ticketCategory.name }} ({{
                                        item.ticketEvent.price }}€)
                                </div>
                                <!-- Quantity input number -->

                                <div class="flex flex-row space-x-5">
                                    <div class="w-1/2">
                                        <v-text-field type="number" label="Quantity" v-model="item.quantity"
                                            @input="updateItem(item)" min="1" max="10" outlined
                                            density="compact"></v-text-field>
                                    </div>
                                </div>

                                <!-- End quantity input number -->
                                <div class="flex flex-row space-x-5">
                                    <div class="w-1/2">
                                        <v-btn block class="rounded" @click="removeItem(item)">Remove</v-btn>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <div class="flex flex-col p-5">
                                <div class="flex flex-row justify-between">
                                    <div class="font-bold text-xl">Total items</div>
                                    <div class="font-bold text-xl">{{ cartTotalItems }}</div>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <div class="font-bold text-xl">Total price</div>
                                    <div class="font-bold text-xl">{{ cartTotalPrice.toFixed(2) }}€</div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <v-btn block class="rounded" color="secondary" @click="checkout()"
                                :disabled="cartTotalItems === 0">Go to
                                checkout</v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { CartItemInterface } from '@/interfaces/responseAPI';
import { useCartStore } from '@/stores/cart';
import { createToast } from 'mosha-vue-toastify';
import { storeToRefs } from 'pinia';

const cartStore = useCartStore();
const { cartTotalItems, cartItems, cartTotalPrice } = storeToRefs(cartStore);
const { getCart, removeFromCart, updateCartItem } = cartStore;

let timeout: ReturnType<typeof setTimeout> | null

const removeItem = async (item: CartItemInterface) => {
    await removeFromCart(item);
}

const updateItem = async (item: CartItemInterface) => {

    item.quantity = Math.min(10, Math.max(1, Number(item.quantity)));

    if (timeout !== null) {
        clearTimeout(timeout);
    }

    timeout = setTimeout(async () => {
        try {
            await updateCartItem(item);
        } catch {
            await getCart();
            createToast('Error while updating cart', {
                type: 'danger',
                position: 'bottom-right'
            });
        }
    }, 500);
}

const checkout = () => {

}

const items = [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Cart'
    }
];
</script>
