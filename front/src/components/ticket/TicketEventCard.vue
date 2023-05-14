<script lang="ts" setup>

import { ITicketEvent } from '@/interfaces/event';
import { useCartStore } from '@/stores/cart';
import { useUserStore } from '@/stores/user';
import { createToast } from 'mosha-vue-toastify';
import { storeToRefs } from 'pinia';
import { PropType } from 'vue';
import { ref } from 'vue';

const cartStore = useCartStore()
const userStore = useUserStore()
const { addToCart } = cartStore;
const { isConnected } = storeToRefs(userStore);
const { cart } = storeToRefs(cartStore);
const quantity = ref<number>(1);

const props = defineProps({
    ticketEvent: {
        type: Object as PropType<ITicketEvent>,
        required: true
    }
})

const addCart = async (ticketEvent: string) => {
    try {
        await addToCart({ cart: cart.value?.id, ticketEvent, quantity: quantity.value })
        createToast('Ticket added to cart', {
            type: 'success',
            position: 'bottom-right'
        });
    }
    catch {
        createToast('Error while adding ticket to cart', {
            type: 'danger',
            position: 'bottom-right'
        });
    }
}

const checkNumber = () => {
    quantity.value = Math.min(10, Math.max(1, Number(quantity.value)));
}
</script>

<template>
    <v-card :disabled="new Date() > new Date(ticketEvent.event.timeEnd)">
        <v-card-title>
            <span class="font-bold">Ticket category: </span>
            <span>{{ props.ticketEvent.ticketCategory.name }}</span>
        </v-card-title>
        <v-card-text>
            <span class="font-bold">Price: </span>
            <span>{{ props.ticketEvent.price }} €</span>
        </v-card-text>
        <v-card-text>
            <div v-if="new Date() > new Date(ticketEvent.event.timeEnd)">
                <span class="font-bold">Sold : </span>
                <span>{{ props.ticketEvent.tickets.length }} / {{ props.ticketEvent.maxQuantity }}</span>
            </div>
            <div v-else>
                <span class="font-bold">Available : </span>
                <span>{{ props.ticketEvent.maxQuantity - props.ticketEvent.tickets.length }} / {{
                    props.ticketEvent.maxQuantity
                }}</span>
            </div>
        </v-card-text>
        <div v-if="new Date() <= new Date(ticketEvent.event.timeEnd) && isConnected">
            <v-card-actions>
                <v-text-field v-model.number="quantity" placeholder="Quantity" @input="checkNumber" type="number" min="1"
                    max="10" step="1" density="compact"></v-text-field>
                <v-btn color="primary" text @click="addCart(props.ticketEvent.id)" class="ml-auto" variant="tonal">Add to
                    cart</v-btn>
            </v-card-actions>
        </div>
    </v-card>
</template>
