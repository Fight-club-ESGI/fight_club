<template>
    <div>
        <v-breadcrumbs :items="items"></v-breadcrumbs>

        <div v-if="fighter" class="pa-5 flex flex-col gap-y-7">
            <v-card border class="flex flex-col pa-7 gap-y-3">
                <div class="flex gap-x-2 items-center">
                    <Icon icon="ph:hand-fist" /> MMA - {{ fighter.nationality.toLocaleUpperCase() }}
                </div>
                <div class="flex gap-x-5">
                    <div>
                        <div class="rounded-xl pa-2 elevation-2">
                            <v-img height="75" width="75"
                                :src="`https://api.multiavatar.com/${fighter.firstname}${fighter.lastname}.png?apikey=XdoCH30EA6grGx`"></v-img>
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
                    <v-divider :thickness="1" class="border-opacity-100" vertical></v-divider>
                    <div class="flex flex-col gap-y-5">
                        <div class="flex gap-x-2">
                            <div class="flex items-center">
                                <Icon icon="pixelarticons:human-height" /> {{ fighter.height }} cm
                            </div>
                            <v-divider vertical :thickness="1" class="border-opacity-100"></v-divider>
                            <div class="flex items-center">
                                <Icon icon="material-symbols:weight" /> {{ fighter.weight }} kg
                            </div>
                        </div>
                        <div class="text-lg font-medium">
                            <span class="text-green-500">{{ fighterWL.win ? fighterWL.win : 0 }} W</span> / <span
                                class="text-red-500">{{ fighterWL.lose ? fighterWL.lose : 0 }} L</span>
                        </div>
                    </div>

                </div>
            </v-card>
            <h1 class="text-xl font-medium">Matches history</h1>
            <div class="flex flex-col gap-y-2">
                <div v-for="matches in fighterHistoryMatches" :key="matches.id"
                    @click="router.push({ name: 'event-details', params: { id: matches.event.id } })"
                    :class="'cursor-pointer border rounded flex pa-4 ' + (typeof matches.winner === 'string' ? ' bg-gradient-to-r from-emerald-200 via-transparent to-red-200' : ' bg-gradient-to-r from-red-200 via-transparent to-emerald-200')">
                    <div class="flex justify-center flex-1">
                        <template v-if="typeof matches.fighterA === 'string'">
                            <span>{{ fighter.firstname + ' ' + fighter.lastname }}</span>
                        </template>
                        <template v-else-if="typeof matches.fighterB === 'string'">
                            <span>{{ fighter.firstname + ' ' + fighter.lastname }}</span>
                        </template>
                    </div>
                    <div>
                        {{ new Date(matches.fightDate).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' }) }}
                        - <span>{{ matches.event.name }}</span>
                    </div>
                    <div class="flex justify-center flex-1">
                        <template v-if="typeof matches.fighterA === 'object'">
                            <span>{{ matches.fighterA.firstname + ' ' + matches.fighterA.lastname }}</span>
                        </template>
                        <template v-else-if="typeof matches.fighterB === 'object'">
                            <span>{{ matches.fighterB.firstname + ' ' + matches.fighterB.lastname }}</span>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { Icon } from '@iconify/vue';
import { storeToRefs } from 'pinia';
import { computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useFighterStore } from '@/stores/fighter';
import { createToast } from 'mosha-vue-toastify';

const route = useRoute();
const router = useRouter();
const fighterStore = useFighterStore();

const { getFighter } = fighterStore;
const { fighter, fighterHistoryMatches, fighterWL } = storeToRefs(fighterStore);

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
});

const fullName = computed(() => fighter.value?.firstname + ' ' + fighter.value?.lastname);

const items = computed(() => [
    {
        title: 'Home',
        to: { name: 'home' }
    },
    {
        title: 'Fighters',
        to: { name: 'fighters' }
    },
    {
        title: fullName.value,
    }
]);

</script>
