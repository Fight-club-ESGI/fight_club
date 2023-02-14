<template>
    <div v-if="fighter">
        <div class="text-xl">
            {{ fullName }}
        </div>
        <div>Height: {{ fighter.height }} cm</div>
        <div>Weight: {{ fighter.weight }} kg</div>
        <div class="flex">
            <v-icon icon="mdi-flag"></v-icon>
            <p class="italic">{{ fighter.nationality }}</p>
        </div>
        <fighter-matches class="pt-5"/>
    </div>
</template>
  
<script lang="ts">
import { storeToRefs } from 'pinia';
import { defineComponent, computed, onMounted } from 'vue';
import { useRoute } from "vue-router";
import { useFighterStore } from '@/stores/fighter';
import { createToast } from 'mosha-vue-toastify';
import FighterMatches from '../Fighter/FighterMatches.vue';
export default defineComponent({
    components: { FighterMatches },
    setup() {
        const route = useRoute();
        const fighterStore = useFighterStore();

        const { getFighter } =  fighterStore;
        const { fighter } =  storeToRefs(fighterStore);

        const  fighterId= computed(() => route.params.id);

        onMounted(async () => {
            try {
                await getFighter(fighterId.value);
            } catch (error) {
                createToast('Error while fetching fighter', { position: 'bottom-right', type: 'danger' })
            }
        });

        const fullName = computed(() => fighter.value?.firstname + ' ' + fighter.value?.lastname );

        return { fighterId, fighter, fullName }
    }
});
</script>