<script lang="ts" setup>
import { defineComponent, ref } from 'vue';
import { useUserStore } from '@/stores/user';
import { storeToRefs } from 'pinia';
import AdminView from '@/views/admin/AdminView.vue';
import imageUrl from '@/assets/boxing.jpg';
import EventSlider from "@/components/home/EventSlider.vue";
import HomeFooter from "@/components/HomeFooter.vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const userStore = useUserStore();
const { isAdmin } = storeToRefs(userStore);
const carouselModel = ref(null);
const categoryCarouselModel = ref(null);


</script>

<template>
    <div class="bg-background text-lightgray">
        <div class="h-screen pb-20">
            <div class="flex-column px-80 py-14">
                <p class="text-7xl font-extrabold text-center my-6">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-red-600">Enjoy</span>
                    Your Favorite Fight at The Best <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-red-600">Event</span>
                </p>
                <p class="text-xl mx-auto text-center w-2/3">Thunderous Knockout is a platform that provides fight event
                    tickets from world-famous fighters for you. Come on, get your tickets now before they run out.</p>
                <div class="mx-auto w-1/6 py-10">
                    <v-btn @click="router.push({ name: 'events' })" variant="tonal" block>events</v-btn>
                </div>
            </div>

            <v-sheet class="w-full bg-transparent">
                <v-slide-group v-model="carouselModel" class="pa-4" center-active show-arrows>
                    <v-slide-group-item v-for="n in 15" :key="n" v-slot="{ isSelected, toggle }">
                        <v-card :color="isSelected ? 'primary' : 'grey-lighten-1'" class="ma-4" height="200" width="400"
                            @click="toggle">
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
        <div class="px-24">
            <div class="my-10">
                <p class="text-5xl font-bold mb-10">
                    Popular Martial Arts
                </p>
                <v-sheet class="w-full" elevation="8">
                    <v-slide-group v-model="categoryCarouselModel" selected-class="bg-success" show-arrows>
                        <v-slide-group-item v-for="n in 15" :key="n" v-slot="{ isSelected, toggle, selectedClass }"
                            class="skew-x-12">
                            <v-card :class="[selectedClass, n % 2 ? 'bg-red-100' : null]"
                                class="h-[400px] w-[300px] rounded-none skew-x-12" @click="toggle">
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
            <div class="my-10">
                <p class="text-5xl font-bold mb-10">
                    Next Popular Events
                </p>
                <div class="flex">
                    <div>
                        ezaeaz
                    </div>
                    <div>
                        ezaeaz
                    </div>
                </div>
                <div class="flex">
                    <div>
                        ezaeaz
                    </div>
                    <div>
                        ezaeaz
                    </div>
                </div>
            </div>
            <div class="my-10">
                <p class="text-5xl font-bold mb-10">
                    Coming Schedule
                </p>
                <div class="flex space-x-4">
                    <v-card v-for="n in 3" class="w-1/3 rounded-none bg-transparent" :key="n">
                        <v-img class="h-[300px] bg-red-100" />
                        <v-card-title class="font-bold text-2xl">
                            # Title 1
                        </v-card-title>
                        <v-card-item>
                            Description 1
                        </v-card-item>
                        <v-card-actions>
                            <v-btn class="border-white border-2 text-white bg-transparent hover:bg-red-400">
                                <span class="text-white">See more -></span>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </div>
            <div class="border py-10 px-46 h-72 bg-gradient-to-r from-purple-600/40 to-blue-600/40 backdrop-blur-sm">
                <p class="text-5xl font-bold mb-10 text-center my-6">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-red-600">Don't
                        Miss</span> Updates From <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-red-600">Thunderous
                        Knockout</span>
                </p>
                <p class="text-center">We will send you our updates, news, announcement, and more to your email. Let us know
                    your email address by fill the form below so that you will receive email from us.</p>
                <v-input value="s" />
            </div>
            <home-footer />
        </div>
    </div>

    <!--
            <v-card :rounded="false">
            <v-img max-height="100vh" class="align-center text-center" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)" cover :src="imageUrl">
                    <h1 class="custom-h1">Thunderous Knockout Fighting</h1>
                    <h2 class="custom-h2">Watch, bet or book your tickets online.</h2>
                    <v-card-action>
                        <router-link to="/events"><v-btn class="text-white mx-3">Upcoming events</v-btn></router-link>
                        <router-link to="/fighters"><v-btn class="text-white mx-3">Our fighters</v-btn></router-link>
                    </v-card-action>
                </v-img>
        </v-card>
        -->
    <!--<admin-view v-if="isAdmin"></admin-view>-->
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
