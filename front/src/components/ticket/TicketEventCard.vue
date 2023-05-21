<script lang="ts" setup>
import { ITicketEvent } from '@/interfaces/event';
import { useCartStore } from '@/stores/cart';
import { useUserStore } from '@/stores/user';
import { createToast } from 'mosha-vue-toastify';
import { storeToRefs } from 'pinia';
import { computed, ref } from 'vue';
import { PropType } from 'vue';

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

const cartQuantity = computed(() => {
    let cartQuantity = cart.value?.cartItems.reduce((acc, item) => {
        if (item.ticketEvent && item.ticketEvent.id == props.ticketEvent.id) {
            return acc + item.quantity;
        }
        return acc;
    }, 0) || 0;

    return cartQuantity;
});

const canAddToCart = computed(() => {
    if (maxCanAddToCart.value < quantity.value || maxCanAddToCart.value <= 0)
        return false;

    return true;

});

const maxCanAddToCart = computed(() => {

    return props.ticketEvent.maxQuantity - props.ticketEvent.tickets.length - cartQuantity.value;

});

const maxCanAddToCart = computed(() => {
    let max = props.ticketEvent.maxQuantity - props.ticketEvent.tickets.length - cartQuantity.value;

    checkNumber();

    return max > 0 ? max : 0;
});

const maxCanAddToCart = computed(() => {
    let max = props.ticketEvent.maxQuantity - props.ticketEvent.tickets.length - cartQuantity.value;

    checkNumber();

    return max > 0 ? max : 0;
});

const addCart = async (ticketEvent: string) => {
    if (!canAddToCart.value) {
        createToast('Not enough tickets available', {
            type: 'danger',
            position: 'bottom-right'
        });
        return;
    }

    try {
        await addToCart({ cart: cart.value?.id, ticketEvent, quantity: quantity.value })
        createToast('Ticket added to cart', {
            type: 'success',
            position: 'bottom-right'
        });

        checkNumber();
    }
    catch {
        createToast('Error while adding ticket to cart', {
            type: 'danger',
            position: 'bottom-right'
        });
    }
}

const checkNumber = () => {
    quantity.value = Math.min(maxCanAddToCart.value, Math.max(1, Number(quantity.value)));
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
                }} <span v-if="cartQuantity > 0">( {{ cartQuantity }} in your cart )</span></span>
            </div>
        </v-card-text>
        <div v-if="new Date() <= new Date(ticketEvent.event.timeEnd) && isConnected">
            <v-card-actions class="d-flex items-center justify-center">
                <v-text-field v-model.number="quantity" append-icon="mdi-plus" @click:append="increment"
                    prepend-icon="mdi-minus" @click:prepend="decrement" @input="checkNumber" min="1" step="1"
                    density="compact" hide-details class="max-w-34 text-center" type="number"></v-text-field>
                <v-btn color="primary" text @click="addCart(props.ticketEvent.id)" :disabled="!canAddToCart" class="ml-5"
                    variant="tonal">Add to
                    cart</v-btn>
            </v-card-actions>
        </div>
    </v-card>
</template>