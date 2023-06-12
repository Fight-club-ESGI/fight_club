<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import { useUserStore } from '@/stores/user';
import { storeToRefs } from 'pinia';
import HomeFooter from "@/components/HomeFooter.vue";
import { useRouter } from 'vue-router';
import { useEventStore } from '@/stores/event';
import { getEventListeners } from 'events';
import { eventNames } from 'process';


const eventStore = useEventStore();
const { incomingEvents } = storeToRefs(eventStore);
const { getEvents } = eventStore;
const router = useRouter();
const userStore = useUserStore();
const { isAdmin } = storeToRefs(userStore);
const carouselModel = ref(null);
const categoryCarouselModel = ref(null);
const imageUrl = ref('');

const eventRandomLandscape = async () => {
    return fetch('https://api.unsplash.com/photos/random?query=landscape&count=1&client_id=h-auINRIAez3dVEu2eNqxOUBVmLfiTKfLIw_dLN38io')
        .then(res => res.json())
        .then(data => imageUrl.value = data[0].urls.full);
}

onMounted(async () => {
    try {
        await getEvents();
        // eventRandomLandscape();
    } catch (error) {

    }
})

</script>

<template>
    <div class="bg-background text-lightgray">
        <div class="pb-20">
            <div class="flex-column px-5 py-4 sm:px-20 md:px-40 lg:px-60 xl:px-80 xl:py-14">
                <p class="text-3xl sm:text-3xl md:text-4xl lg:text-7xl font-extrabold text-center my-6">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-red-600">Enjoy</span>
                    Your Favorite Fight at The Best <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-red-600">Event</span>
                </p>
                <p class="text-xl mx-auto text-center w-2/3">Thunderous Knockout is a platform that provides fight event
                    tickets from world-famous fighters for you. Come on, get your tickets now before they run out.</p>
                <div class="flex justify-center py-10">
                    <v-btn @click="router.push({ name: 'events' })" variant="tonal">events</v-btn>
                </div>
            </div>

            <div class="text-5xl py-5 text-center">
                Incoming events
            </div>
            <v-sheet class="w-full bg-transparent">
                <v-slide-group v-model="carouselModel" class="pa-4" center-active show-arrows>
                    <v-slide-group-item v-for="event in incomingEvents" :key="event.id" v-slot="{ isSelected, toggle }">
                        <v-card :style="`background-image: url('${event.locationLink}')`"
                            class="relative ma-4 bg-cover bg-center" height="200" width="400"
                            @click="router.push({ name: 'event-details', params: { id: event.id } })">
                            <div class="h-full w-full bg-gradient-to-t from-neutral-800 to-transparent"></div>
                            <div class="absolute bg-white z-10 m-5 rounded-lg h-16 w-16 flex items-center top-0">
                                <div class="flex flex-col text-center mx-auto font-bold">
                                    <p>
                                        {{ new Date(event.timeStart).toLocaleDateString('en-GB', { day: '2-digit' }) }}
                                    </p>
                                    <p>
                                        {{ new Date(event.timeStart).toLocaleDateString('en-GB', { month: 'long' }) }}
                                    </p>
                                </div>
                            </div>
                            <div class="pa-5 flex flex-column absolute overflow-auto bottom-0">
                                <p class="text-2xl font-bold text-white">{{ event.name }}</p>
                            </div>
                            <div class="d-flex fill-height align-center justify-center">
                                <v-scale-transition>
                                    <v-icon v-if="isSelected" color="white" size="48"
                                        icon="mdi-close-circle-outline"></v-icon>
                                </v-scale-transition>
                            </div>
                        </v-card>
                    </v-slide-group-item>
                </v-slide-group>
            </v-sheet>
        </div>

        <home-footer />
    </div>
</template>

<style scoped>
.custom-h1 {
    font-size: 42px;
    text-transform: uppercase;
    color: white;
    margin-bottom: 24px;
}

.custom-h2 {
    font-size: 24px;
    color: white;
    margin-bottom: 24px;
}
</style>
