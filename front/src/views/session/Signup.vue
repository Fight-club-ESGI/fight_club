<template>
    <v-form class="w-full px-12 xl:w-2/3 mx-auto text-white" ref="form" v-model="valid" lazy-validation>
        <p class="text-center font-weight-bold">Register</p>

        <v-text-field v-model="email" :rules="emailRules" label="E-mail" required class="my-4"></v-text-field>

        <v-text-field v-model="password" label="Password" type="password" autocomplete="current-password" required
            class="my-4"></v-text-field>

        <v-switch v-model="checkbox" :rules="[(v) => !!v || 'You must agree to continue!']"
            label="I certify that I am over 18 years old. I have read and accept the Terms and Conditions and the Privacy Policy."
            color="primary" required class="my-4"></v-switch>

        <v-btn block color="primary" @click="validate"> Validate </v-btn>
        <v-divider class="my-3"></v-divider>
        <div class="flex justify-center items-center gap-x-2"><router-link :to="{ name: 'login' }"
                class="custom-link">Already registered?</router-link>
            <v-btn color="primary" variant="text" :to="{ name: 'home' }">back home </v-btn>
        </div>


    </v-form>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { ISignup } from '@/interfaces/security';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();
const { signup } = userStore;

const router = useRouter();
const form = ref();
const valid = ref<boolean>(false);
const username = ref<string>('');
const usernameRules = [
    (v: string) => !!v || 'UserName is required',
    (v: string) => (v && v.length <= 10) || 'UserName must be less than 10 characters',
];
const email = ref<string>('');
const emailRules = [(v: string) => !!v || 'E-mail is required', (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'];
const password = ref<string>('');
const checkbox = ref<boolean>(false);

async function validate() {
    const { valid } = await form.value.validate();

    if (valid) {
        try {
            const payload: ISignup = {
                username: username.value,
                password: password.value,
                email: email.value,
            };
            await signup(payload);
            router.push({ name: 'login' });
        } catch (error) {
            console.log(error);
        }
    }
}
</script>

<style scoped></style>
