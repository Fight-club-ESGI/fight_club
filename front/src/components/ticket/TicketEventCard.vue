<script lang="ts" setup>
import { ITicketEvent } from '@/interfaces/event';
import { formatNumber } from '@/service/helpers';
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
    quantity.value = Math.min(maxCanAddToCart.value, Math.max(1, Number(quantity.value))) || 1;
}

const increment = () => {
    quantity.value++;
    checkNumber();
}

const decrement = () => {
    quantity.value--;
    checkNumber();
    quantity.value = Math.min(maxCanAddToCart.value, Math.max(1, Number(quantity.value)));
}

const getPriceUnit = computed(() => {
    return formatNumber(props.ticketEvent.price).split(',')[0];
});

const getPriceDecimal = computed(() => {
    return formatNumber(props.ticketEvent.price).split(',')[1];
});

const ticketCategoryColor = (name: string) => {

    const colors: { [key: string]: string } = {
        "GOLD": "amber-darken-3",
        "SILVER": "blue-grey-lighten-1",
        "VIP": "light-blue-darken-2",
        "V_VIP": "light-blue-darken-4",
        "PEUPLE": "grey-darken-1"
    }

    return colors[name];
}
</script>

<template>
    <div class="py-3">
        <v-card class="py-3 px-4 w-[350px] shadow-md" v-if="ticketEvent.event">
            <v-chip class="my-3" :color="ticketCategoryColor(ticketEvent.ticketCategory.name)">{{
                ticketEvent.ticketCategory.name
            }}</v-chip>
            <div v-if="new Date() > new Date(ticketEvent.event.timeEnd)">
                <span class="font-bold">Sold : </span>
                <span v-if="props.ticketEvent.tickets.length < props.ticketEvent.maxQuantity">{{
                    props.ticketEvent.tickets.length }} / {{ props.ticketEvent.maxQuantity }}</span>
                <span v-else
                    class="bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-red-900 dark:text-red-300 border border-red-800">SOLD
                    OUT</span>
            </div>
            <div v-else>
                <span class="font-bold">Available : </span>
                <span v-if="props.ticketEvent.tickets.length < props.ticketEvent.maxQuantity">{{
                    props.ticketEvent.maxQuantity - props.ticketEvent.tickets.length }} /
                    {{ props.ticketEvent.maxQuantity }} <span v-if="cartQuantity > 0">({{
                        cartQuantity }} in your cart)
                    </span>
                </span>
                <span v-else
                    class="bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-red-900 dark:text-red-300 border border-red-800">SOLD
                    OUT</span>
            </div>
            <v-divider class="my-3 border-black"></v-divider>

            <div class="flex justify-between items-center py-5">

                <div class="flex flex-col justify-start items-center gap-5"
                    v-if="!(new Date() > new Date(ticketEvent.event.timeStart)) && isConnected">
                    <v-text-field v-model.number="quantity" append-icon="mdi-plus" @click:append="increment"
                        prepend-icon="mdi-minus" @click:prepend="decrement" @input="checkNumber" min="1" step="1"
                        density="compact" hide-details class="max-w-40 text-center" type="number"></v-text-field>
                    <v-btn text @click="addCart(props.ticketEvent.id)" :disabled="!canAddToCart" variant="tonal"
                        :color="ticketCategoryColor(ticketEvent.ticketCategory.name)">Add
                        to
                        cart</v-btn>
                </div>
                <div class="text-right font-weight-bold text-2xl self-end">
                    {{ getPriceUnit }}<span class="text-grey-darken-1 text-lg">,{{ getPriceDecimal }}</span>
                    €
                </div>
            </div>
        </v-card>
    </div>
</template>