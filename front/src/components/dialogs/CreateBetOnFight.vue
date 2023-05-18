<template>
    <div>
        <v-dialog class="w-2/3" v-model="dialog">
            <template v-slot:activator="{ props }">
                <div class="flex w-full">
                    <v-card v-bind="props" class="flex cursor-pointer w-30 relative bg-neutral-600 text-white items-center mx-auto rounded-sm">
                        <p class="text-center w-full font-weight-bold">
                            Bets
                        </p>
                    </v-card>
                </div>
            </template>
            <v-card class="text-center">
                <v-card-title class="font-bold p-10">
                    Bet on the fight
                </v-card-title>
                <div class="w-full flex px-10">
                    <v-card class="w-full w-92">
                        azeazea
                    </v-card>
                    <div class="mx-auto">
                        vs
                    </div>
                    <v-card class="w-full h-92 bg-red-100 w-92">
                        eazeaz
                    </v-card>
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
import { useEventStore } from '@/stores/event';
import {useBetStore} from "@/stores/bet";

const props = defineProps({
    admin: {type: Boolean, required: true},
    fight: {type: IFight, required: true},
});

const betStore = useBetStore();
const {  } = betStore;

const form = ref();
const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);

const file = ref();
const image = ref();

let event = reactive<CreateBet>({
    fight: '',
    betOn: '',
    amount: '',
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