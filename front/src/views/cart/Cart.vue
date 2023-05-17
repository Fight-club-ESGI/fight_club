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
                        <div v-for="item in cartItems" :key="item.id" class="flex space-x-5 justify-between items-center">
                            <div class="font-extrabold">
                                {{ item.ticketEvent.event.name }}
                                <span class="text-sm text-gray-400"> | {{
                                    item.ticketEvent.ticketCategory.name }}
                                    ({{
                                        item.ticketEvent.price }}€)</span>
                            </div>
                            <!-- Quantity input number -->

                            <div class="flex gap-x-7 justify-self-end">
                                <v-text-field type="number" v-model="item.quantity" @input="updateItem(item)" min="1"
                                    append-icon="mdi-plus" @click:append="increment(item)" prepend-icon="mdi-minus"
                                    @click:prepend="decrement(item)" outlined density="compact" class="w-40"
                                    hide-details></v-text-field>

                                <v-btn class="rounded" @click="removeItem(item)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
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
                            <v-menu open-on-hover>
                                <template v-slot:activator="{ props }">
                                    <v-btn color="primary" v-bind="props" class="" variant="tonal">
                                        Pay
                                    </v-btn>
                                </template>

                                <div class="bg-white rounded-lg elevation-2 mt-2">
                                    <ul class="py-2 px-2 text-lightgray text-small font-normal cursor-pointer">
                                        <li @click="check_out('stripe')"
                                            class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                            with Stripe
                                        </li>
                                        <li @click="check_out('wallet')"
                                            class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                            with my Wallet
                                        </li>
                                    </ul>
                                </div>
                            </v-menu>
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
const { getCart, removeFromCart, updateCartItem, checkout, clearCart } = cartStore;

let timeout: ReturnType<typeof setTimeout> | null

const increment = (item: CartItemInterface) => {
    item.quantity = Math.min(maxCanAddToCart(item), Number(item.quantity) + 1);
    updateItem(item);
}

const decrement = (item: CartItemInterface) => {
    item.quantity = Math.max(1, Number(item.quantity) - 1);
    updateItem(item);
}

const checkNumber = (item: CartItemInterface) => {
    item.quantity = Math.min(maxCanAddToCart(item), Math.max(1, Number(item.quantity)));
}

const maxCanAddToCart = (item: CartItemInterface) => {
    return item.ticketEvent.maxQuantity - item.ticketEvent.tickets.length;

}

const removeItem = async (item: CartItemInterface) => {
    await removeFromCart(item);
}

const updateItem = async (item: CartItemInterface) => {
    checkNumber(item)
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

const check_out = async (type: string) => {
    try {
        await checkout(type);

        createToast('Checkout successful', {
            type: 'success',
            position: 'bottom-right'
        });

        await clearCart();
    }
    catch {
        createToast('Error while checking out', {
            type: 'danger',
            position: 'bottom-right'
        });
    }
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
