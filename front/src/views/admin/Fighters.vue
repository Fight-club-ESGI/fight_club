<template>
    <v-container>
        <v-tabs v-model="tab" color="primary" align-tabs="center">
            <v-tab value="fighters">Fighters</v-tab>
            <v-tab value="category">Category</v-tab>
        </v-tabs>

        <v-window v-model="tab">
            <v-window-item value="fighters">
                <div class="flex grid grid-cols-4 gap-4 mt-3">
                    <div class="col-span-1">
                        <create-fighter class="flex justify-start pb-2" />
                        <fighter-filter class="sticky top-[64px]" />
                    </div>
                    <div class="col-span-3" no-gutters>
                        <fighter-table :fighters="filteredFighters" class="w-full" />
                    </div>
                </div>
            </v-window-item>

            <v-window-item value="category"> <fighter-weight-category></fighter-weight-category> </v-window-item>
        </v-window>
    </v-container>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed, isRef } from 'vue';
import FighterTable from '@/components/Fighter/FighterTable.vue';
import CreateFighter from '@/components/dialogs/CreateFighter.vue';
import { useFighterStore } from '@/stores/fighter';
import { storeToRefs } from 'pinia';
import FighterFilter from '@/components/Fighter/FighterFilter.vue';
import FighterWeightCategory from '@/components/Fighter/FighterWeightCategory.vue';

export default defineComponent({
    components: { FighterTable, CreateFighter, FighterFilter, FighterWeightCategory },
    setup() {
        const fighterStore = useFighterStore();
        const { getFighters } = fighterStore;
        const { fighters, filteredFighters } = storeToRefs(fighterStore);

        const tab = ref();

        onMounted(async () => {
            try {
                await getFighters();
            } catch (error) {}
        });

        return { tab, fighters, filteredFighters };
    },
});
</script>
