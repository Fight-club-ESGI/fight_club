<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <div v-if="cart" class="flex h-full space-x-5 p-5 justify-center">
            <div class="flex flex-col h-full min-w-2/4 rounded">
                <div class="flex font-bold text-2xl h-1/12 items-center">Your cart</div>
                <div class="h-11/12">
                    <div class="text-center bg-neutral-100 p-10 rounded">
                        <div v-if="cart.length === 0">
                            <div class="font-bold text-3xl p-6">Your cart is empty</div>
                        </div>
                        <div v-for="item in cart" :key="item.id" class="flex flex-row space-x-5">
                            <div class="w-1/4">
                                <v-img :src="item.image" aspect-ratio="1"></v-img>
                            </div>
                            <div class="flex flex-col w-3/4">
                                <div class="font-bold text-xl">
                                    {{ item.name }}
                                </div>
                                <div class="flex flex-row space-x-5">
                                    <div class="w-1/2">
                                        <v-btn block class="rounded" @click="remove_from_cart(item)">Remove</v-btn>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-1/2">
                        </div>
                        <div class="w-1/2">
                            <v-btn block class="rounded" color="secondary" @click="wallet_deposit()">Go to checkout</v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia';
import { defineComponent, onMounted, ref } from 'vue';
import { useWalletStore } from '@/stores/wallet';
import { createToast } from 'mosha-vue-toastify';
import { useUserStore } from '@/stores/user';
import { useWalletTransactionStore } from "@/stores/walletTransaction";
import { Icon } from "@iconify/vue/dist/iconify.js";

const wallet_amount = ref(0);
const wallet_input_amount = ref('0');
const cartStore = useWalletStore();
const { deposit, withdraw, getWallet } = cartStore;
const { cart } = storeToRefs(cartStore);

const walletTransactionStore = useWalletTransactionStore();

const { getCartHistory, transactionConfirmation } = cartStore
const { cartHistory } = storeToRefs(cartStore);
const { user } = storeToRefs(useUserStore());


onMounted(async () => {
    try {
        await getCart();
        await getCartHistory();
    } catch (e) { }
});

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
