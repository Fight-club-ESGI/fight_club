<template>
    <v-container>
        <v-tabs v-model="tab" color="primary" align-tabs="center">
            <v-tab value="fighters">Fighters</v-tab>
            <v-tab value="category">Category</v-tab>
        </v-tabs>

        <v-window v-model="tab">
            <v-window-item value="fighters">
                <div class="flex grid grid-cols-4 gap-4">
                    <div class="col-span-1">
                        <create-fighter class="flex justify-start pb-2" />
                        <fighter-filter @filterUpdated="filterFighter($event)" class="sticky top-[64px]" />
                    </div>
                    <div v-if="fighters" class="col-span-3 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" no-gutters>
                        <div v-for="fighter in fightersFiltered" :key="fighter.id">
                            <fighter :fighter="fighter" />
                        </div>
                    </div>
                </div>
            </v-window-item>

            <v-window-item value="category"> <fighter-weight-category></fighter-weight-category> </v-window-item>
        </v-window>
    </v-container>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue';
import Fighter from '@/components/Fighter/Fighter.vue';
import CreateFighter from '@/components/dialogs/CreateFighter.vue';
import { useFighterStore } from '@/stores/fighter';
import { storeToRefs } from 'pinia';
import FighterFilter from '@/components/Fighter/FighterFilter.vue';
import FighterWeightCategory from '@/components/Fighter/FighterWeightCategory.vue';

export default defineComponent({
    components: { Fighter, CreateFighter, FighterFilter, FighterWeightCategory },
    setup() {
        const fighterStore = useFighterStore();
        const { getFighters } = fighterStore;
        const { fighters } = storeToRefs(fighterStore);

        const tab = ref();
        const fightersFiltered = ref([]);

        onMounted(async () => {
            try {
                await getFighters();
            } catch (error) {}
        });

        const filterFighter = (filter: any) => {
            fightersFiltered.value = fighters.value;
            fightersFiltered.value = fightersFiltered.value.filter((fighter) => {
                if (filter.gender.includes(fighter.gender) || filter.gender.length === 0) {
                    return fighter;
                }
            });

            if (filter.search.length !== 0) {
                fightersFiltered.value = fightersFiltered.value.filter((fighter) => {
                    if (
                        fighter.firstname.toLowerCase().indexOf(filter.search.toLowerCase()) !== -1 ||
                        fighter.lastname.toLowerCase().indexOf(filter.search.toLowerCase()) !== -1
                    )
                        return fighter;
                });
            }

            if (filter.nationality) {
                fightersFiltered.value = fightersFiltered.value.filter((fighter) => {
                    return fighter.nationality === filter.nationality;
                });
            }

            if (filter.divisionClass) {
                fightersFiltered.value = fightersFiltered.value.filter((fighter) => {
                    return fighter;
                });
            }

            return fightersFiltered.value;
        };

        return { tab, fighters, filterFighter, fightersFiltered };
    },
});
</script>
