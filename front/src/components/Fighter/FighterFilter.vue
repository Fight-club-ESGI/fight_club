<template>
    <div>
        <v-list class="sticky top-[64px]">
            <v-list-item @click="clearAll()" prepend-icon="mdi-close">
                <v-list-item-action class="font-bold">Clear all</v-list-item-action>
            </v-list-item>
            <v-list-item>
                <v-text-field v-model="filters.search" placeholder="Search ..." prepend-icon="mdi-magnify" density="compact" hide-details />
            </v-list-item>
            <v-list-group value="Gender">
                <template v-slot:activator="{ props }">
                    <v-list-item v-bind="props" prepend-icon="mdi-gender-male-female" title="Gender"></v-list-item>
                </template>

                <v-list-item v-for="(gender, i) of filters.gender" :key="i" :value="gender">
                    <v-checkbox v-model="gender.value" :label="gender.name" density="compact" hide-details color="primary" />
                </v-list-item>
            </v-list-group>

            <v-list-group value="Division">
                <template v-slot:activator="{ props }">
                    <v-list-item v-bind="props" prepend-icon="mdi-weight" title="Division"></v-list-item>
                </template>

                <v-list-item v-for="(division, i) in filters.divisionClass" :key="i" :value="division">
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
                <v-autocomplete
                    v-model="filters.nationality"
                    :items="nationalityJson"
                    prepend-icon="mdi-flag"
                    clearable
                    placeholder="Nationality"
                    density="compact"
                    hide-details
                />
            </v-list-item>
        </v-list>
    </div>
</template>
<script lang="ts">
import { defineComponent, reactive, watch, onMounted, toRefs } from 'vue';
import divisionByWeight from '@/utilities/divisionByWeight';
import nationalityJson from '@/data/nationality.json';
import { storeToRefs } from 'pinia';
import { useCategoryStore } from '@/stores/category';
import { useFighterStore } from '@/stores/fighter';
export default defineComponent({
    setup(props, { emit }) {
        const categoyStore = useCategoryStore();
        const { categories } = storeToRefs(categoyStore);
        const { getCategories } = categoyStore;
        const fighterStore = useFighterStore();
        const { updateFilter } = fighterStore;

        let filters = reactive({
            divisionClass: categories.value,
            nationality: null,
            search: '',
            gender: [
                {
                    name: 'male',
                    value: false,
                },
                {
                    name: 'female',
                    value: false,
                },
            ],
        });

        onMounted(async () => {
            try {
                await getCategories();
            } catch {}
        });

        const clearAll = () => {
            filters.divisionClass.forEach((division) => (division.value = false));
            filters.nationality = null;
            filters.gender.forEach((gender) => (gender.value = false));
            filters.search = '';
        };

        watch(filters, () => {
            let f = { ...filters };
            f.gender = filters.gender
                .filter((g) => g.value)
                .map((g) => {
                    if (g.value) return g.name;
                });
            f.divisionClass = filters.divisionClass
                .filter((d) => d.value)
                .map((division) => {
                    if (division.value) return division.id;
                });
            emit('filterUpdated', f);
            updateFilter(f);
        });

        return { divisionByWeight, nationalityJson, filters, clearAll };
    },
});
</script>
<style lang=""></style>
