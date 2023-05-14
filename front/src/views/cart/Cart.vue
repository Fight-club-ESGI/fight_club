<template>
    <div>
        <div class="flex flex-col md:flex-row item-center md:items-start justify-center h-full gap-10 p-5">
            <div class="flex flex-col h-full min-w-1/2 bg-gray-100 p-10 rounded-md shadow">
                <h1 class="font-bold text-2xl my-3">Your cart</h1>
                <div v-if="cartTotalItems === 0" class="text-center">
                    <h2 class="font-bold text-3xl p-6">Your cart is empty</h2>
                </div>
                <div v-else v-for="item in cartItems" :key="item.id"
                    class="flex space-x-5 justify-between items-center border-b-2 py-4">
                    <div class="flex flex-col">
                        <span class="text-lg font-extrabold">
                            {{ item.ticketEvent.event.name }}
                        </span>
                        <span class="text-sm text-gray-500"> {{
                            item.ticketEvent.ticketCategory.name }}
                            ({{ item.ticketEvent.price }}€)</span>
                    </div>
                    <div class="flex items-center justify-end space-x-3">
                        <v-text-field type="number" v-model="item.quantity" @input="updateItem(item)" min="1"
                            append-icon="mdi-plus" @click:append="increment(item)" prepend-icon="mdi-minus"
                            @click:prepend="decrement(item)" outlined density="compact" class="w-40"
                            hide-details></v-text-field>

<<<<<<< HEAD
                        <v-icon @click="removeItem(item)" class="text-red-500">mdi-delete</v-icon>
=======
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
>>>>>>> 535601e (Fix merge issues)
                    </div>
                </div>
            </div>
            <div class="flex flex-col h-full min-w-[380px] w-1/3 max-w-[500px] bg-gray-100 p-10 rounded-md shadow">
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
                <v-menu open-on-hover :disabled="cartTotalItems < 1">
                    <template v-slot:activator="{ props }">
                        <v-btn color="secondary" v-bind="props" class="" variant="tonal">
                            Go to checkout
                        </v-btn>
                    </template>

                    <div class="bg-white rounded-lg elevation-2 mt-2">
                        <ul class="py-2 px-2 text-lightgray text-small font-normal cursor-pointer">
                            <li @click="check_out('stripe')" class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                Pay with Stripe
                            </li>
                            <li @click="check_out('wallet')" class="p-2 flex items-center hover:bg-slate-100 gap-x-2">
                                Pay with my Wallet
                            </li>
                        </ul>
                    </div>
                </v-menu>
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
<<<<<<< HEAD
const { getCart, removeFromCart, updateCartItem, checkout, clearCart } = cartStore;
=======
const { getCart, removeFromCart, updateCartItem, checkout } = cartStore;
>>>>>>> 535601e (Fix merge issues)

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
    }, 1000);
}

const check_out = async (type: string) => {
    try {
        await checkout(type);

        createToast('Checkout successful', {
            type: 'success',
            position: 'bottom-right'
        });
<<<<<<< HEAD

        await clearCart();
=======
>>>>>>> 535601e (Fix merge issues)
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
