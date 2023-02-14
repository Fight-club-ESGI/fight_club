<template>
    <v-row no-gutters justify="center">
        <v-col cols="10" md="6" lg="4">
            <v-card class="pa-5 mt-12">
                <div class="text-center"><v-icon size="40">mdi-account-circle</v-icon></div>
                <p class="text-center font-weight-bold">Create an account</p>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-text-field v-model="username" :rules="usernameRules" autocomplete="username" :counter="10" label="Username" required class="my-4"></v-text-field>

                    <v-text-field v-model="email" :rules="emailRules" label="E-mail" required class="my-4"></v-text-field>

                    <v-text-field v-model="password" label="Password" type="password" autocomplete="current-password" required class="my-4"></v-text-field>

                    <v-switch
                        v-model="checkbox"
                        :rules="[(v) => !!v || 'You must agree to continue!']"
                        label="I certify that I am over 18 years old. I have read and accept the Terms and Conditions and the Privacy Policy."
                        color="primary"
                        required
                        class="my-4"
                    ></v-switch>

                    <v-btn block color="primary" @click="validate"> Validate </v-btn>
                    <v-divider class="my-3"></v-divider>
                    <div class="text-center"><router-link to="/login" class="custom-link">Already registered?</router-link></div>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { SignupI } from '../interfaces/payload';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/user';

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
            const payload: SignupI = {
                username: username.value,
                password: password.value,
                email: email.value
            }
            await signup(payload);
            router.push({ name: 'login' });
        } catch (error) {
            console.log(error)
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
