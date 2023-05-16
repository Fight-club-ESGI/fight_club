<template>
    <div>
        <v-dialog v-model="dialog" style="width: 50%">
            <template v-slot:activator="{ props }">
                <v-btn color="primary" variant="tonal" v-bind="props"> Create ticket event</v-btn>
            </template>

            <v-card class="pa-4">
                <v-card-title> Create ticket event </v-card-title>
                <v-container>
                    <v-form v-model="valid" ref="form">
                        <v-row>
                            <v-text-field v-model.number="price" type="number" min="1"
                                :rules="[rules.required, rules.ticketPrice]" placeholder="0" label="Ticket Price" />
                        </v-row>
                        <v-row>
                            <v-select v-model="category" item-value="name" :items="ticketCategoriesName"
                                :rules="[rules.required]" placeholder="Categories" label="Ticket category">
                            </v-select>
                        </v-row>
                        <v-row>
                            <v-text-field v-model.number="maxQuantity" :rules="[rules.required, rules.ticketPlaces]"
                                type="number" min="1" placeholder="1" label="Number of tickets" />
                        </v-row>
                    </v-form>
                </v-container>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="resetForm()">Cancel</v-btn>
                        <v-btn color="secondary" @click="submit()">Create</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, watch } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { useTicketStore } from '@/stores/tickets';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';


const route = useRoute();
const ticketStore = useTicketStore();
const { ticketCategories } = storeToRefs(ticketStore);
const { getTicketCategories, createTicketEvent } = ticketStore;

const eventId = computed(() => route.params.id.toString());
const ticketCategoriesName = computed(() => ticketCategories.value.map(c => c.name));

const form = ref();
const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);
const maxQuantity = ref<number>(1);
const price = ref<number>(1);
const category = ref<string>('');

onMounted(async () => {
    try {
        await getTicketCategories();
    } catch (error) {
        console.error(error)
    }
});

watch(dialog, (open: boolean) => {
    if (!open) {
        resetForm()
    }
})

const rules = {
    required: (value: any) => !!value || 'Required.',
    ticketPrice: (value: any) => value > 0 || 'Ticket must have a valid price',
    ticketPlaces: (value: any) => value > 0 || 'Number of places must be valid',
};

const resetForm = () => {
    category.value = '';
    maxQuantity.value = 1;
    price.value = 1;
    dialog.value = false;
}

const submit = async () => {
    try {
        const { valid } = await form.value.validate();
        if (valid) {
            const payload = {
                price: price.value,
                maxQuantity: maxQuantity.value,
                event: '/events/' + eventId.value,
                ticketCategory: '/ticket_categories/' + ticketCategories.value.find(t => t.name === category.value)?.id
            }

            await createTicketEvent(payload);

            dialog.value = false;
        }
    } catch (error: any) {
        console.error(error)
        createToast(error, { position: 'bottom-right', type: 'danger' });
    }
};

</script>
