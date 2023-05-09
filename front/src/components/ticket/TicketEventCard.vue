<script lang="ts" setup>

import { ITicketEvent } from '@/interfaces/event';
import { useCartStore } from '@/stores/cart';
import { createToast } from 'mosha-vue-toastify';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import { PropType } from 'vue';
import { ref } from 'vue';

const cartStore = useCartStore()
const { addToCart } = cartStore;
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
</script>

<template>
    <v-card>
        <v-card-title>
            <span class="font-bold">Ticket category: </span>
            <span>{{ props.ticketEvent.ticketCategory.name }}</span>
        </v-card-title>
        <v-card-text>
            <span class="font-bold">Price: </span>
            <span>{{ props.ticketEvent.price }} €</span>
        </v-card-text>
        <v-card-text>
            <span class="font-bold">Max quantity: </span>
            <span>{{ props.ticketEvent.maxQuantity }}</span>
        </v-card-text>
        <v-card-actions>
            <v-text-field v-model.number="quantity" placeholder="Quantity" type="number" min="1" max="10" step="1"
                density="compact"></v-text-field>
            <v-btn color="primary" text @click="addCart(props.ticketEvent.id)" class="ml-auto" variant="tonal">Add to
                cart</v-btn>
        </v-card-actions>
    </v-card>
</template>
