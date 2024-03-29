<template>
    <v-dialog v-model="dialog" max-width="75%">
        <template v-slot:activator="{ props }">
            <span color="primary" v-bind="props">Update</span>
        </template>

        <v-card class="text-center">
            <v-card-title class="font-bold p-10">
                Update fighter
            </v-card-title>
            <div class="w-full flex px-10">
                <v-form v-model="valid" ref="form" class="flex flex-col w-full">
                    <div>
                        <v-file-input type="file" accept="image/*" v-model="file" label="File input"
                                      @change="uploadFile" />
                        <v-text-field v-model="fighter.firstname" :rules="[rules.required]" placeholder="Firstname" label="Firstname" />
                        <v-text-field v-model="fighter.lastname" :rules="[rules .required]" placeholder="Lastname" label="Lastname" />
                        <v-text-field v-model="fighter.birthdate" :rules="[rules.required]" type="date" label="Birthday" />
                        <v-autocomplete v-model="fighter.nationality" :items="nationalityJson" :rules="[rules.required]" label="Nationality" placeholder="Nationality" color="secondary" style="max-height: 450px" />
                        <v-slider v-model="fighter.height" label="Height (cm)" min="70" max="230" :step="1" color="primary" track-color="secondary" thumb-label="always">
                            <template v-slot:thumb-label="{ modelValue }">
                                {{ modelValue }}
                            </template>
                        </v-slider>
                        <v-select v-model="fighter.gender" :rules="[rules.required]" label="Gender" placeholder="Gender"
                                  :items="['male', 'female']" color="secondary" variant="plain" />
                        <v-text-field v-model.number="fighter.weight" :rules="[rules.weight]" type="number" max="400" min="52"
                                      label="Weight">
                            <template v-slot:details>
                                <div v-if="division">
                                    Category:
                                    <span :style="{ color: division.color }">{{ division.name }}</span>
                                </div>
                            </template>
                        </v-text-field>
                    </div>
                </v-form>
                <div class="flex w-full">
                    <fighter
                        class="w-92 mx-auto text-left"
                        :fighter="fighter"
                        :admin="admin"
                        :preview="true"
                    />
                </div>
            </div>
            <v-card-actions>
                <v-row justify="end" class="px-4">
                    <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                    <v-btn color="secondary" @click="update()">Update</v-btn>
                </v-row>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" setup>
import {ref, computed, PropType, reactive} from 'vue';
import nationalityJson from '@/data/nationality.json';
import { createToast } from 'mosha-vue-toastify';
import { IFighter } from '@/interfaces/fighter';
import { useFighterStore } from '@/stores/fighter';
import {UpdateFighter} from "@/interfaces/fighter";
import Fighter from '@/components/Fighter/Fighter.vue';

const props = defineProps({
    fighter: {
        type: Object as PropType<IFighter>,
        required: true
    },
    admin: {type: Boolean, required: true}
})

const fighter = reactive<UpdateFighter>({
    firstname: props.fighter.firstname,
    lastname: props.fighter.lastname,
    height: props.fighter.height,
    weight: props.fighter.weight,
    nationality: props.fighter.nationality,
    gender: props.fighter.gender,
    birthdate: props.fighter.birthdate,
    imageFile: '',
    imageName: props.fighter.imageName,
    imageSize: props.fighter.imageSize,
});
fighter.birthdate = fighter.birthdate.substring(0, 10);

const fighterStore = useFighterStore();
const { updateFighter } = fighterStore;

const form = ref();
const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);

const file = ref();
const image = ref();

const divisionByWeight = [
    {
        name: 'Strawweight',
        weight: 52.2,
        color: '#01682a',
    },
    {
        name: 'Flyweight',
        weight: 56.7,
        color: '#1d6a1c',
    },
    {
        name: 'Bantamweight',
        weight: 61.2,
        color: '#306c05',
    },
    {
        name: 'Featherweight',
        weight: 65.8,
        color: '#416e00',
    },
    {
        name: 'Lightweight',
        weight: 70.3,
        color: '#526e00',
    },
    {
        name: 'Super lightweight',
        weight: 74.8,
        color: '#50bf8b',
    },
    {
        name: 'Welterweight',
        weight: 77.1,
        color: '#636e00',
    },
    {
        name: 'Super welterweight',
        weight: 79.4,
        color: '#756d00',
    },
    {
        name: 'Middleweight',
        weight: 83.9,
        color: '#886a00',
    },
    {
        name: 'Super middleweight',
        weight: 88.5,
        color: '#9b6600',
    },
    {
        name: 'Light heavyweight',
        weight: 93.0,
        color: '#af5f00',
    },
    {
        name: 'Cruiserweight',
        weight: 102.1,
        color: '#c35600',
    },
    {
        name: 'Heavyweight',
        weight: 120.2,
        color: '#d84900',
    },
    {
        name: 'Super heavyweight',
        weight: 120.3,
        color: '#ff0000',
    },
];

const division = computed(() => {
    const closest = divisionByWeight
        .map((division) => division.weight)
        .reduce(function (prev, curr) {
            return Math.abs(curr - fighter.weight) < Math.abs(prev - fighter.weight) ? curr : prev;
        });
    return divisionByWeight.find((division) => division.weight === closest);
});

const rules = {
    required: (value: any) => !!value || 'Required.',
    weight: (value: number) => (value >= 52 && value <= 400) || 'Weight must be between 52kg and 400kg',
};

const update = async () => {
    try {
        const { valid } = await form.value.validate();
        if (valid) {
            const formData = new FormData();

            for (const key in fighter) {
                const value = fighter[key];

                if (value !== undefined) {
                    if (key === 'imageFile' && (value === '' || value === 'null')) break
                    formData.append(key, value);
                }
            }

            await updateFighter(formData, props.fighter.id);
            dialog.value = false;
        }
    } catch (error: any) {
        createToast(error, { position: 'bottom-right', type: 'danger' });
    }
    dialog.value = false;
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
        fighter.imageFile = image.value
        fighter.imageName = generateURL(image.value);
    } catch (e) {
        console.log(e);
    }
};

</script>
