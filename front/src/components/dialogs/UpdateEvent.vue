<template>
    <div>
        <v-dialog v-model="dialog" max-width="75%">
            <template v-slot:activator="{ props }">
                <span color="primary" v-bind="props"> Update </span>
            </template>
            <v-card>
                <v-card-title> Update event </v-card-title>
                <v-container>
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
                </v-container>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                        <v-btn color="secondary" @click="saveEvent()">Update</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts" setup>
import { ref } from 'vue';
import { useEventStore } from '@/stores/event';
import { PropType } from 'vue';
import { EventI } from '@/interfaces/payload';
const eventStore = useEventStore();
const { updateEvent } = eventStore;
const props = defineProps({
    event: Object as PropType<EventI>
});

const event = ref({ ...props.event });

const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);

const saveEvent = async () => {
    try {
        const { ticketEvents, fights, ...payload } = event.value;
        await updateEvent(payload);
        dialog.value = false;
    } catch (error) {
        console.error(error);
    }
};

const rules = {
    required: (value: any) => !!value || 'Required.',
    capacity: (value: any) => value > 0 || 'Capacity must be 1 or higher',
};
</script>
