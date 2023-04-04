<template>
    <div>
        <v-dialog v-model="dialog" max-width="50%">
            <template v-slot:activator="{ props }">
                <span color="primary" v-bind="props"> Update </span>
            </template>

            <v-card>
                <v-card-title> Update fighter '{{ fighter.firstname }} {{ fighter.lastname }}'</v-card-title>
                <v-container>
                    <v-form v-model="valid" ref="form">
                        <v-row justify="space-between" class="align-center">
                            <v-col>
                                <v-text-field v-model="fighter.firstname" :rules="[rules.required]" placeholder="Firstname" label="Firstname" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model="fighter.lastname" :rules="[rules.required]" placeholder="Lastname" label="Lastname" />
                            </v-col>
                            <v-col>
                                <v-text-field v-model="fighter.birthdate" :rules="[rules.required]" type="date" label="Birthday" />
                            </v-col>
                        </v-row>
                        <v-row class="align-center">
                            <v-col>
                                <v-autocomplete
                                    v-model="fighter.nationality"
                                    :items="nationalityJson"
                                    solo
                                    :rules="[rules.required]"
                                    variant="outlined"
                                    label="Nationality"
                                    placeholder="Nationality"
                                    density="compact"
                                    color="secondary"
                                    style="max-height: 450px"
                                />
                            </v-col>
                            <v-col>
                                <v-slider
                                    v-model="fighter.height"
                                    label="Height (cm)"
                                    min="70"
                                    max="230"
                                    :step="1"
                                    color="primary"
                                    track-color="secondary"
                                    thumb-label="always"
                                >
                                    <template v-slot:thumb-label="{ modelValue }">
                                        {{ modelValue }}
                                    </template>
                                </v-slider>
                            </v-col>
                            <v-col>
                                <v-select
                                    v-model="fighter.gender"
                                    :rules="[rules.required]"
                                    label="Gender"
                                    placeholder="Gender"
                                    :items="['male', 'female']"
                                    color="secondary"
                                />
                            </v-col>
                        </v-row>
                        <v-row class="align-center">
                            <v-col cols="4">
                                <v-text-field v-model.number="fighter.weight" :rules="[rules.weight]" type="number" max="400" min="52" label="Weight">
                                    <template v-slot:details>
                                        <div v-if="division">
                                            Category:
                                            <span :style="{ color: division.color }">{{ division.name }}</span>
                                        </div>
                                    </template>
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                        <v-btn color="secondary" @click="update()">Update</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref, computed, reactive, PropType } from 'vue';
import nationalityJson from '@/data/nationality.json';
import { createToast } from 'mosha-vue-toastify';
import { FighterI } from '@/interfaces/payload';
import { useFighterStore } from '@/stores/fighter';

export default defineComponent({
    props: {
        fighter: {
            type: Object as PropType<FighterI>,
        },
    },
    setup(props) {
        let fighter = ref({ ...props.fighter });
        fighter.value.birthdate = fighter.value.birthdate.substring(0, 10);
        const fighterStore = useFighterStore();
        const { updateFighter } = fighterStore;

        const form = ref();
        const dialog = ref<boolean>(false);
        const valid = ref<boolean>(false);

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
                    return Math.abs(curr - fighter.value.weight) < Math.abs(prev - fighter.value.weight) ? curr : prev;
                });
            return divisionByWeight.find((division) => division.weight === closest);
        });

        const rules = {
            required: (value: any) => !!value || 'Required.',
            weight: (value: number) => (value >= 52 && value <= 400) || 'Weight must be between 52kg and 400kg',
        };

        const update = async () => {
            try {
                const { createdAt, updatedAt, ...fighterObj } = fighter.value;
                const { valid } = await form.value.validate();
                if (valid) {
                    await updateFighter(fighterObj);
                }
            } catch (error: any) {
                createToast(error, { position: 'bottom-right', type: 'danger' });
            }
            dialog.value = false;
        };

        return { dialog, valid, fighter, division, nationalityJson, rules, update, form };
    },
});
</script>
