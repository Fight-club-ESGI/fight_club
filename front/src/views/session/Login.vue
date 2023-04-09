<template>
    <div class="h-full w-full flex">
        <div class="h-full w-full hidden md:flex flex items-center">
            <div class="text-center w-full font-bold text-6xl px-20">
                Welcome to the Fight Club
            </div>
        </div>
        <div class="h-full w-full flex bg-neutral-300 items-center text-center">
            <v-form
                class="w-full px-12 xl:w-2/3 mx-auto"
                ref="form" v-model="valid"
                lazy-validation
            >
                <p class="text-center font-weight-bold">Login</p>
                <v-text-field
                    v-model="email"
                    :rules="emailRules"
                    autocomplete="email"
                    label="E-mail"
                    required
                    class="my-4"
                />
                <v-text-field
                    v-model="password"
                    label="Password"
                    autocomplete="current-password"
                    type="password"
                    required
                    class="mt-4"
                ></v-text-field>
                <v-btn block color="primary" @click="validate">Login</v-btn>
                <v-divider class="my-3"></v-divider>
                <div class="flex text-caption mb-4">
                    <div class=""><router-link to="/resetpassword" class="custom-link">Forgot your password?</router-link></div>
                    <v-spacer/>
                    <div class="text-center"><router-link to="/signup" class="custom-link">Not registered yet?</router-link></div>
                </div>
            </v-form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { createToast } from 'mosha-vue-toastify';
import { ref } from 'vue';
import router from '@/router';
import { useUserStore } from '@/stores/user';
const userStore = useUserStore();
const { signin } = userStore;

const form = ref();
const valid = ref(true);
const email = ref<string>('');
const emailRules = [(v: string) => !!v || 'E-mail is required', (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'];

const password = ref<string>('');

async function validate() {
    const { valid } = await form.value.validate();
    if (valid) {
        try {
            const payload = {
                email: email.value,
                password: password.value,
            };
            await signin(payload);
            router.push({ name: 'home' });
        } catch (error: any) {
            console.log(error)
            createToast(error.response.data.message, { type: 'danger'});
        }
    }
}
</script>

<style scoped>
.custom-link:link,
.custom-link:visited {
    color: #424242;
    text-decoration: none;
}
</style>
