<template>
    <v-container>
        <v-tabs v-model="tab" bg-color="" align-tabs="center">
            <v-tab value="one">Personal Information</v-tab>
            <v-tab value="two">
                <v-icon>mdi-lock-outline</v-icon>
                Password edit
            </v-tab>
        </v-tabs>

        <v-window v-model="tab">
            <v-window-item value="one">
                <div class="bg-white rounded-lg m-0 md:mx-20 lg:mx-40 my-20">
                    <user-informations />
                </div>
            </v-window-item>

            <v-window-item value="two">
                <div class="bg-white rounded m-0 md:mx-20 lg:mx-40 my-20">
                    <change-password />
                </div>
            </v-window-item>
        </v-window>
    </v-container>
</template>

<script lang="ts" setup>
import { defineComponent, ref } from 'vue';
import ChangePassword from '@/components/profile/ChangePassword.vue';
import UserInformations from '@/components/profile/UserInformations.vue';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();
const { logout } = userStore;
const tab = ref<String>();

</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
