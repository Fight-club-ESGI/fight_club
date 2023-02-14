<template>
    <v-container>
        <div class="flex grid grid-cols-4 gap-4">
            <fighter-filter @filterUpdated="filter = $event" class="col-span-1 sticky top-[64px]" />
            <div v-if="fighters" class="col-span-3 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" no-gutters>
                <div v-for="fighter in fighterFiltered" :key="fighter.id">
                    <fighter :fighter="fighter"></fighter>
                </div>
            </div>
        </div>
    </v-container>
</template>
<script lang="ts">
import FighterFilter from '@/components/Fighter/FighterFilter.vue';
import Fighter from '@/components/Fighter/Fighter.vue';
import { defineComponent, onMounted, ref, computed } from 'vue';
import { useFighterStore } from '@/stores/fighter';
import { storeToRefs } from 'pinia';
export default defineComponent({
    components: { Fighter, FighterFilter },
    setup() {
        const fighterStore = useFighterStore();
        const { getFighters } = fighterStore;
        const { fighters } = storeToRefs(fighterStore);
        const filter = ref();

        const fighterFiltered = computed(() => {
            let filteredFighter = fighters.value;
            if (filter.value) {
                if (filter.value.search !== '') {
                    filteredFighter = filteredFighter.filter(fighter => {
                        return fighter.firstname.indexOf(filter.value.search) != -1 ||
                            fighter.lastname.indexOf(filter.value.search) != -1
                    });
                }
                if (filter.value.nationality) {
                    filteredFighter = filteredFighter.filter(fighter => {
                        return fighter.nationality.indexOf(filter.value.nationality) != -1;
                    });
                }
                if (filter.value.divisionClass) {
                    filteredFighter = filteredFighter.filter(fighter => {
                        return fighter.weight;
                    })
                    console.log(filter.value)
                }
                if (filter.value.gender) {
                    filteredFighter = filteredFighter.filter(fighter => fighter.gender);
                }
                return filteredFighter;
            }
            return filteredFighter;
        });

        onMounted(async () => {
        try {
            await getFighters();
        } catch (error) {
            
        }
        });

        return { fighters, fighterFiltered, filter }
    }
});

</script>
<style lang="">
    
</style>