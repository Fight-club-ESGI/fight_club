<template>
    <div v-if="fighter" class="pa-5 flex flex-col gap-y-7">
        <v-card border class="flex flex-col pa-7 gap-y-3">
            <div class="flex gap-x-2 items-center">
                <Icon icon="ph:hand-fist" /> MMA - {{ fighter.nationality.toLocaleUpperCase() }}
            </div>
            <div class="flex gap-x-5">
                <div>
                    <div class="rounded-xl pa-2 elevation-2">
                        <v-img height="75" width="75"
                            src="https://upload.wikimedia.org/wikipedia/commons/9/95/Vue.js_Logo_2.svg"></v-img>
                    </div>
                </div>
                <div class="flex flex-col gap-y-4">
                    <div class="text-lg">
                        {{ fullName }}
                    </div>
                    <div>
                        Age: {{ age }}
                        ({{ new Date(fighter.birthdate).toLocaleDateString('en-GB', {
                            month: 'long', day: '2-digit', year:
                                'numeric'
                        }) }})
                    </div>
                </div>
            </div>
            <div>Height: {{ fighter.height }} cm</div>
            <div>Weight: {{ fighter.weight }} kg</div>
            <div class="flex">
                <v-icon icon="mdi-flag"></v-icon>
                <p class="italic">{{ fighter.nationality }}</p>
            </div>
        </v-card>
        <v-card border class="pa-7">
            <h3></h3>
        </v-card>
    </div>
</template>

<script lang="ts" setup>
import { Icon } from '@iconify/vue';
import { storeToRefs } from 'pinia';
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useFighterStore } from '@/stores/fighter';
import { createToast } from 'mosha-vue-toastify';

const route = useRoute();
const fighterStore = useFighterStore();

const { getFighter } = fighterStore;
const { fighter } = storeToRefs(fighterStore);

const fighterId = computed(() => route.params.id.toString()).value;

onMounted(async () => {
    try {
        await getFighter(fighterId);
    } catch (error) {
        createToast('Error while fetching fighter', {
            position: 'bottom-right',
            type: 'danger',
        });
    }
});

const age = computed(() => {
    var ageDifMs = Date.now() - new Date(fighter.value?.birthdate).getTime();
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    return Math.abs(ageDate.getUTCFullYear() - 1970);
})
const fullName = computed(() => fighter.value?.firstname + ' ' + fighter.value?.lastname);


</script>
