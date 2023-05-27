<template>
    <v-row no-gutters justify="center">
        <v-col cols="10" md="6" lg="5">
            <v-card class="pa-5 mt-12">
                <p class="font-weight-bold">Select your tickets</p>
                <v-row no-gutters v-for="ticket in tickets" class="my-6" align="center" justify="space-between">
                    <v-col cols="2">
                        {{ ticket.name }}
                    </v-col>
                    <v-col cols="8">
                        <v-row no-gutters justify="end" align="center">
                            <v-btn icon="mdi-minus" :disabled="ticket.quantity < 1" class="mr-3" size="30"
                                @click="ticket.quantity--" color="primary"></v-btn>
                            <div class="custom-price text-right">{{ ticket.quantity }} x {{ ticket.price }} €</div>
                            <v-btn icon="mdi-plus" class="ml-3" size="30" @click="ticket.quantity++"
                                color="primary"></v-btn>
                        </v-row>
                    </v-col>
                </v-row>
                <v-divider class="my-6"></v-divider>
                <v-row no-gutters justify="space-between">
                    <v-col cols="6">Total price</v-col>
                    <v-col cols="6">
                        <p class="custom-total text-right">{{ total }} €</p>
                    </v-col>
                </v-row>
                <v-row no-gutters justify="center" class="my-6">
                    <v-btn color="primary">Next step</v-btn>
                </v-row>
                <p class="text-center">The tickets will be automatically sent to you by e-mail.</p>
            </v-card>
        </v-col>
    </v-row>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue';
const total = computed(() => {
    let counter = 0;
    tickets.forEach((element) => {
        counter += element.quantity * element.price;
    });
    return counter;
});
const tickets = reactive([
    {
        name: 'Gold',
        price: 100,
        quantity: 0,
    },
    {
        name: 'Silver',
        price: 30,
        quantity: 0,
    },
    {
        name: 'Bronze',
        price: 15,
        quantity: 0,
    },
]);
</script>

<style scoped>
.custom-price {
    font-size: 28px;
    font-style: italic;
    font-weight: bold;
}

.custom-total {
    font-size: 36px;
    font-style: italic;
    font-weight: bold;
}
</style>
