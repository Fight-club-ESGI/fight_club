<template>
    <div>
        <v-dialog v-model="dialog">
            <template v-slot:activator="{ props }">
                <v-btn color="primary" v-bind="props"> Create tickets </v-btn>
            </template>

            <v-card>
                <v-card-title> Create ticket(s) </v-card-title>
                <v-container>
                    <v-form v-model="valid" ref="form">
                        <v-row justify="space-between" class="align-center">
                            <v-col>
                                <v-text-field v-model.number="ticket.price" type="number" min="1"
                                    :rules="[rules.required, rules.ticketPrice]" placeholder="0" label="Ticket Price" />
                            </v-col>
                            <v-col>
                                <v-select v-model="ticket.ticketCategory" item-value="name" :items="ticketCategoriesName"
                                    :rules="[rules.required]" placeholder="Categories" label="Ticket category">
                                </v-select>
                            </v-col>
                            <v-col>
                                <v-text-field v-model="numberOfTickets" :rules="[rules.required, rules.ticketPlaces]"
                                    type="number" min="1" placeholder="1" label="Number of tickets" />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                        <v-btn color="secondary" @click="submit()">Create</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref, computed, reactive, onMounted } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { ITicket, ICreateTicketEvent } from '@/service/api/tickets';
import { useTicketStore } from '@/stores/tickets';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';

export default defineComponent({
    setup() {
        const route = useRoute();
        const ticketStore = useTicketStore();
        const { tickets, ticketCategories } = storeToRefs(ticketStore);
        const { getTicketCategories, getTicketEvents, createTicketEvent } = ticketStore;

        const eventId = computed(() => route.params.id.toString());
        const ticketCategoriesName = computed(() => ticketCategories.value.map(c => c.name));

        const form = ref();
        const dialog = ref<boolean>(false);
        const valid = ref<boolean>(false);
        const numberOfTickets = ref<number>(1);

        onMounted(async () => {
            try {
                await getTicketCategories();
                await getTicketEvents(eventId.value);
            } catch (error) {
                console.error(error)
            }
        });

        const ticket = reactive<ICreateTicketEvent>({
            price: 1,
            event: eventId.value,
            maxQuantity: 0,
            ticketCategory: ''
        });

        const rules = {
            required: (value: any) => !!value || 'Required.',
            ticketPrice: (value: any) => value > 0 || 'Ticket must have a valid price',
            ticketPlaces: (value: any) => value > 0 || 'Number of places must be valid',
        };

        const submit = async () => {
            try {
                const { valid } = await form.value.validate();
                if (valid) {
                    const { price, maxQuantity } = ticket;

                    const payload: ICreateTicketEvent = {
                        price,
                        maxQuantity,
                        event: '/events/' + ticket.event,
                        ticketCategory: '/ticket_categories/' + ticketCategories.value.find(t => t.name === ticket.ticketCategory)?.id
                    }

                    for (let place = 0; place < numberOfTickets.value; place++) {
                        await createTicketEvent(payload);
                    }

                    dialog.value = false;
                }
            } catch (error: any) {
                console.error(error)
                createToast(error, { position: 'bottom-right', type: 'danger' });
            }
        };

        return { dialog, valid, rules, submit, form, ticket, numberOfTickets, ticketCategories, ticketCategoriesName };
    },
});
</script>
