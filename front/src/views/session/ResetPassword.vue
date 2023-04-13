<template>
    <v-form
        class="w-full px-12 xl:w-2/3 mx-auto"
        ref="form" v-model="valid"
        lazy-validation
    >
        <p class="text-center font-weight-bold">Reset password</p>
        <v-text-field v-model="email" :rules="emailRules" label="E-mail" required class="my-4"></v-text-field>
        <v-btn block color="primary" @click="validate"> Change your password </v-btn>
        <v-divider class="my-3"></v-divider>
        <div class="flex text-caption mb-4">
            <div class=""><router-link :to="{ name: 'login' }" >Already got an account?</router-link></div>
            <v-spacer/>
            <div class="text-center"><router-link :to="{ name: 'register' }" >Not registered yet?</router-link></div>
        </div>    
    </v-form>

</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { useUserStore } from '@/stores/user';

const form = ref();
const valid = ref(true);
const email = ref<string>('');
const emailRules = [(v: string) => !!v || 'E-mail is required', (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'];

const userStore = useUserStore();
const { resetPassword } = userStore;

async function validate() {
    const { valid } = await form.value.validate();

    if (valid) {
        try {
            resetPassword({ email: email.value });
        } catch (err) {}
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
