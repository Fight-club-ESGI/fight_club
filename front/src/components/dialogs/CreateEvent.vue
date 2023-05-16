<template>
    <div>
        <v-dialog class="w-2/3" v-model="dialog">
            <template v-slot:activator="{ props }">
                <v-card v-bind="props" class="flex cursor-pointer h-92 relative bg-neutral-600 text-white items-center">
                    <p class="text-center w-full text-2xl font-weight-bold">
                        New event
                    </p>
                </v-card>
            </template>
            <v-card class="text-center">
                <v-card-title class="font-bold p-10">
                    Register an event
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
                            <v-text-field v-model="event.locationLink" placeholder="Location link" label="Location link"
                                hint="If no image is provided, a default template" />
                            <v-text-field v-model="event.timeStart" :rules="[rules.required, rules.date]" type="date"
                                placeholder="Start event" label="Start event" />
                            <v-text-field v-model="event.timeEnd" :rules="[rules.required, rules.logicDate]" type="date"
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
                    <v-btn color="primary" @click="resetForm()">Cancel</v-btn>
                    <v-btn color="secondary" @click="submit()">Create</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts" setup>
import { ref, reactive, defineProps, watch } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { CreateEvent } from '@/interfaces/event';
import { useEventStore } from '@/stores/event';
import Event from '../Event.vue';

const props = defineProps({
    admin: {type: Boolean, required: true}
});

const eventStore = useEventStore();
const { createEvent } = eventStore;

const form = ref();
const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);

const file = ref();
const image = ref();

let event = reactive<CreateEvent>({
    name: '',
    location: '',
    description: '',
    category: '',
    capacity: 0,
    locationLink: '',
    timeStart: '',
    timeEnd: '',
    vip: false,
    imageFile: '',
    imageName: '',
    imageSize: '',
    display: false,
});

watch(dialog, (open: boolean) => {
    if (!open) {
        resetForm()
    }
})

const rules = {
    required: (value: any) => !!value || 'Required.',
    capacity: (value: any) => value > 0 || 'Capacity must be 1 or higher',
    date: (value: any) => new Date(value) >= new Date() || 'Date must be in the future',
    logicDate: (value: any) => event.timeStart <= event.timeEnd || 'End date must be after start date',
};

const submit = async () => {
    try {
        const { valid } = await form.value.validate();
        if (valid) {
            const formData = new FormData();

            for (const key in event) {
                const value = event[key];

                if (value !== '') {
                    formData.append(key, value);
                }
            }

            await createEvent(formData);
            dialog.value = false;
        }
    } catch (error: any) {
        createToast(error, { position: 'bottom-right', type: 'danger' });
    }
};

const resetForm = () => {
    event = {
        name: '',
        location: '',
        description: '',
        category: '',
        capacity: 0,
        locationLink: '',
        timeStart: '',
        timeEnd: '',
        vip: false,
        imageFile: '',
        imageName: '',
        imageSize: '',
        display: false,
    }
    dialog.value = false;
}

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