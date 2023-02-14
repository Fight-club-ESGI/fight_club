<template>
    <v-row no-gutters justify="center">
        <v-col cols="10" md="6" lg="4">
            <v-card class="pa-5 mt-12">
                <div class="text-center"><v-icon size="40">mdi-account-circle</v-icon></div>
                <p class="text-center font-weight-bold">Login</p>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-text-field v-model="email" :rules="emailRules" autocomplete="email" label="E-mail" required class="my-4"></v-text-field>

                    <v-text-field v-model="password" label="Password" autocomplete="current-password" type="password" required class="mt-4"></v-text-field>
                    <div class="text-caption mb-4"><router-link to="/resetpassword" class="custom-link">Forgot your password?</router-link></div>

                    <v-btn block color="primary" @click="validate"> Login </v-btn>
                    <v-divider class="my-3"></v-divider>
                    <div class="text-center"><router-link to="/signup" class="custom-link">Not registered yet?</router-link></div>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script lang="ts" setup>
import { createToast } from 'mosha-vue-toastify';
import { ref } from 'vue';
import router from '../router';
import { useUserStore } from '../stores/user';
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
                password: password.value
            }
            await signin(payload);
            router.push({ name: 'home' });
        } catch (error: any) {
            createToast(error, { type: 'danger'})
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
