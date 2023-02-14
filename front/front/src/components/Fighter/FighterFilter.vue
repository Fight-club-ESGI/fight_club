<template>
    <div>
        <v-list class="sticky top-[64px]">
            <v-list-item @click="clearAll()" prepend-icon="mdi-close">
                <v-list-item-action class="font-bold">Clear all</v-list-item-action>
            </v-list-item>
            <v-list-item >
                <v-text-field v-model="filters.search" placeholder="Search ..." prepend-icon="mdi-magnify" density="compact" hide-details/>
            </v-list-item>
            <v-list-group value="Gender">
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-gender-male-female"
                        title="Gender"
                    ></v-list-item>
                </template>

                <v-list-item
                    v-for="(gender, i) of filters.gender" :key="i"
                    :value="gender"
                >
                    <v-checkbox v-model="gender.value" :label="gender.name" density="compact" hide-details color="primary" />
                </v-list-item>
            </v-list-group>

            <v-list-group value="Division">
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-weight"
                        title="Division"
                    ></v-list-item>
                </template>

                <v-list-item
                    v-for="(division, i) in filters.divisionClass" :key="i"
                    :value="division"
                >
                    <v-checkbox v-model="division.value" :label="division.name" density="compact" hide-details :color="division.color" />
                </v-list-item>
            </v-list-group>

            <!-- <v-list-group value="Height">
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-human-male-height"
                        title="Height"
                    ></v-list-item>
                </template>

                <v-list-item
                    v-for="(division, i) in divisionByWeight" :key="i"
                    :value="division"
                    :color="division.color"
                >
                    <template v-slot:prepend="{ isSelected, isActive }">
                        <v-checkbox :label="division.name" density="compact" hide-details></v-checkbox>
                    </template>
                </v-list-item>
            </v-list-group> -->
            <v-list-item>
                <v-autocomplete v-model="filters.nationality" :items="nationalityJson" prepend-icon="mdi-flag" clearable placeholder="Nationality" density="compact" hide-details />
            </v-list-item>
        </v-list>
    </div>
</template>
<script lang="ts">
import { defineComponent, reactive, watch } from 'vue';
import divisionByWeight from '@/utilities/divisionByWeight';
import nationalityJson from '@/data/nationality.json';

export default defineComponent({
    setup(props, { emit }) {
        const filters = reactive({
            divisionClass: [
                {
                    name: 'Strawweight',
                    weight: 52.2,
                    color: '#01682a',
                    value: false
                },
                {
                    name: 'Flyweight',
                    weight: 56.7,
                    color: '#1d6a1c',
                    value: false
                },
                {
                    name: 'Bantamweight',
                    weight: 61.2,
                    color: '#306c05',
                    value: false
                },
                {
                    name: 'Featherweight',
                    weight: 65.8,
                    color: '#416e00',
                    value: false
                },
                {
                    name: 'Lightweight',
                    weight: 70.3,
                    color: '#526e00',
                    value: false
                },
                {
                    name: 'Super lightweight',
                    weight: 74.8,
                    color: '#50bf8b',
                    value: false
                },
                {
                    name: 'Welterweight',
                    weight: 77.1,
                    color: '#636e00',
                    value: false
                },
                {
                    name: 'Super welterweight',
                    weight: 79.4,
                    color: '#756d00',
                    value: false
                },
                {
                    name: 'Middleweight',
                    weight: 83.9,
                    color: '#886a00',
                    value: false
                },
                {
                    name: 'Super middleweight',
                    weight: 88.5,
                    color: '#9b6600',
                    value: false
                },
                {
                    name: 'Light heavyweight',
                    weight: 93.0,
                    color: '#af5f00',
                    value: false
                },
                {
                    name: 'Cruiserweight',
                    weight: 102.1,
                    color: '#c35600',
                    value: false
                },
                {
                    name: 'Heavyweight',
                    weight: 120.2,
                    color: '#d84900',
                    value: false
                },
                {
                    name: 'Super heavyweight',
                    weight: 120.3,
                    color: '#ff0000',
                    value: false
                }
            ],
            nationality: null,
            search: '',
            gender: [
                {
                    name: 'Male',
                    value: false
                },
                {
                    name: 'Female',
                    value: false
                }
            ]
        });

        const clearAll = () => {
            filters.divisionClass.forEach(division => division.value = false);
            filters.nationality = null;
            filters.gender.forEach(gender => gender.value = false);
            filters.search = '';
        }

        watch(filters, () => {
            emit('filterUpdated', filters);
        })

        return { divisionByWeight, nationalityJson, filters, clearAll }
    }
});
</script>
<style lang="">
    
</style>