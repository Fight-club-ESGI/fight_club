<template>
    <v-card class="py-2 px-4">
        <v-row no-gutters justify="space-between">
            <v-col cols="12">
                <div class="my-2 font-weight-bold text-xl">
                    {{ ticket.eventTitle }}
                </div>
                <div class="my-2 font-weight-bold">
                    {{ ticket.location }} - {{ DateTime.fromISO(ticket.eventDate, { locale: 'en' }).toFormat('ff') }}
                </div>
                <v-row no-gutters justify="space-between">
                    <div class="my-1">Seat {{ ticket.placeSeat }} - {{ ticket.category }}</div>
                    <div class="my-1 text-right font-weight-bold">{{ formatMoneyOrRating(ticket.price) }} €</div>
                </v-row>
            </v-col>
        </v-row>
        <v-divider class="my-2"></v-divider>
        <p class="mb-1 text-grey-darken-1">Ref {{ ticket.ref }} - {{ DateTime.fromISO(ticket.purchaseDate, { locale: 'en' }).toFormat('ff') }}</p>
    </v-card>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon';
import { formatMoneyOrRating } from '@/service/helpers';
defineProps({
    ticket: {
        type: Object,
        default(rawProps) {
            return { message: 'hello' };
        },
    },
});
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
.custom-rating {
    color: white;
    background: rgb(var(--v-theme-primary));
    border-radius: 5px;
}
.custom-result {
    background: rgb(var(--v-theme-lightgray));
    border-radius: 5px;
}
</style>
