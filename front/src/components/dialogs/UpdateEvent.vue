<template>
    <div>
        <v-dialog v-model="dialog" max-width="75%">
            <template v-slot:activator="{ props }">
                <span color="primary" v-bind="props">Update</span>
            </template>

            <v-card class="text-center">
                <v-card-title class="font-bold p-10">
                    Update event
                </v-card-title>
                <div class="w-full flex px-10">
                    <v-form class="flex flex-col w-full" v-model="valid" ref="form">
                        <div>
                            <v-file-input type="file" accept="image/*" v-model="file" label="File input"
                                          @change="uploadFile" />
                            <v-text-field v-model="event.name" :rules="[rules.required]" placeholder="Name" label="Name" />
                            <v-text-field v-model="event.description" :rules="[rules.required]" type="text"
                                          placeholder="Description" label="Description" />
                            <v-text-field v-model="event.location" :rules="[rules.required]" placeholder="Location"
                                          label="Location" />
                            <v-text-field v-model.number="event.capacity" :rules="[rules.required, rules.capacity]"
                                          type="number" placeholder="Capacity" label="Capacity" />
                            <v-text-field v-model="event.locationLink" placeholder="Location link" label="Location link"/>
                            <v-text-field v-model="event.timeStart.split('T')[0]" :rules="[rules.required]" type="date"
                                          placeholder="Start event" label="Start event" />
                            <v-text-field v-model="event.timeEnd.split('T')[0]" :rules="[rules.required]" type="date"
                                          placeholder="End event" label="End event" />
                            <div class="flex">
                                <v-checkbox v-model="event.vip" variant="primary" label="VIP" />
                                <v-checkbox v-model="event.display" variant="primary" label="Display" />
                            </div>
                        </div>
                    </v-form>
                    <div class="flex w-full">
                        <event class="mx-auto text-left w-92" :event="event" :admin="admin" :preview="true" />
                    </div>
                </div>
                <v-card-actions class="justify-end">
                    <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                    <v-btn color="secondary" @click="saveEvent()">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts" setup>
import {reactive, ref} from 'vue';
import { useEventStore } from '@/stores/event';
import { PropType } from 'vue';
import {CreateEvent, IEvent, UpdateEvent} from '@/interfaces/event';
const eventStore = useEventStore();
const { updateEvent } = eventStore;
import Event from '../Event.vue';

const props = defineProps({
    event: {type: Object as PropType<IEvent>, required: true},
    admin: {type: Boolean, required: true}
});

const event = reactive<UpdateEvent>({
    name: props.event.name,
    location: props.event.location,
    description: props.event.description,
    category: props.event.category,
    capacity: props.event.capacity,
    locationLink: props.event.locationLink,
    timeStart: props.event.timeStart,
    timeEnd: props.event.timeEnd,
    vip: props.event.vip,
    imageFile: 'null',
    display: props.event.display,
    imageName: props.event.imageName,
    imageSize: props.event.imageSize
});

const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);

const file = ref();
const image = ref();

const rules = {
    required: (value: any) => !!value || 'Required.',
    capacity: (value: any) => value > 0 || 'Capacity must be 1 or higher',
};

const saveEvent = async () => {
    try {
        const formData = new FormData();

        for (const key in event) {
            const value = event[key];

            if (value !== undefined) {
                if (key === 'imageFile' && (value === '' || value === 'null')) break
                formData.append(key, value);
            }
        }

        await updateEvent(formData, props.event.id);
        dialog.value = false;
    } catch (error) {
        console.error(error);
    }
};

const generateURL = (file: File) => {
    let fileSrc = URL.createObjectURL(file);
    setTimeout(() => {
        URL.revokeObjectURL(fileSrc);
    }, 1000);
    return fileSrc;
}

const uploadFile = async (e: any) => {
    try {
        image.value = e.target.files[0]
        event.imageFile = image.value
        event.imageName = generateURL(image.value);
    } catch (e) {
        console.log(e);
    }
};
</script>
