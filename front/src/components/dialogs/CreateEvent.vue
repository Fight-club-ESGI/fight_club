<template>
    <div>
        <v-dialog v-model="dialog">
            <template v-slot:activator="{ props }">
                <v-btn variant="tonal" color="secondary" v-bind="props"> Register an event </v-btn>
            </template>

            <v-card>
                <v-card-title>Register an event</v-card-title>
                <div>
                    <v-form v-model="valid" ref="form">
                        <v-row justify="space-between" class="align-center">
                            <v-col>
                                <v-text-field v-model="event.name" :rules="[rules.required]" placeholder="Name"
                                    label="Name" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model="event.location" :rules="[rules.required]" placeholder="Location"
                                    label="Location" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model="event.description" :rules="[rules.required]" type="text"
                                    placeholder="Description" label="Description" />
                            </v-col>
                        </v-row>
                        <v-row class="align-center">
                            <v-col>
                                <v-text-field v-model.number="event.capacity" :rules="[rules.required, rules.capacity]"
                                    type="number" placeholder="Capacity" label="Capacity" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model="event.locationLink" placeholder="Location link" label="Location link"
                                    hint="If no image is provided, a default template" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model="event.timeStart" :rules="[rules.required]" type="date"
                                    placeholder="Start event" label="Start event" />
                            </v-col>
                        </v-row>
                        <v-row class="align-center">
                            <v-col>
                                <v-text-field v-model="event.timeEnd" :rules="[rules.required]" type="date"
                                    placeholder="End event" label="End event" />
                            </v-col>
                            <v-col>
                                <v-checkbox v-model="event.vip" variant="primary" label="VIP" />
                            </v-col>
                            <v-col></v-col>
                        </v-row>
                    </v-form>
                </div>
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
import { defineComponent, ref, computed, reactive } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { EventI } from '@/interfaces/payload';
import { useEventStore } from '@/stores/event';

export default defineComponent({
    setup() {
        const eventStore = useEventStore();
        const { createEvent } = eventStore;

        const form = ref();
        const dialog = ref<boolean>(false);
        const valid = ref<boolean>(false);

        const event = reactive<EventI>({
            name: '',
            location: '',
            description: '',
            image: '',
            capacity: 0,
            category: null,
            locationLink: '',
            timeStart: '',
            timeEnd: '',
            fight: [],
            vip: false,
        });

        const rules = {
            required: (value: any) => !!value || 'Required.',
            capacity: (value: any) => value > 0 || 'Capacity must be 1 or higher',
        };

        const submit = async () => {
            try {
                const { valid } = await form.value.validate();
                if (valid) {
                    await createEvent(event);
                    dialog.value = false;
                }
            } catch (error: any) {
                createToast(error, { position: 'bottom-right', type: 'danger' });
            }
        };

        return { dialog, valid, rules, submit, form, event };
    },
});
</script>
