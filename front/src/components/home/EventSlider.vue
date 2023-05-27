<template>
    <div class="h-full bg-green-100">
        <v-sheet class="mx-auto w-full h-[550px]" elevation="2">
            <v-slide-group v-model="carouselModel" class="h-full py-4" center-active show-arrows>
                <v-slide-group-item class="h-full p-4" v-for="n in 5" :key="n" v-slot="{ isSelected, toggle }">
                    <v-card :color="isSelected ? 'primary' : 'grey-lighten-1'" :class="isSelected
                        ? 'h-full w-[800px]'
                        : 'h-1/2 w-[400px]'" class="mx-4" @click="isSelected ? null : toggle()">
                        <div class="d-flex fill-height align-center justify-center">
                            <v-scale-transition>
                                <v-icon v-if="isSelected" color="white" size="48" icon="mdi-close-circle-outline"></v-icon>
                            </v-scale-transition>
                        </div>
                    </v-card>
                </v-slide-group-item>
            </v-slide-group>
        </v-sheet>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const carouselModel = ref(null);


</script>

<style scoped></style>
