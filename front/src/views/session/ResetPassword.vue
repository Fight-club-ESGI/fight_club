<template>
    <v-row no-gutters justify="center">
        <v-col cols="12" md="6" lg="3">
            <v-card class="pa-3 mt-12">
                <div class="text-center"><v-icon size="40">mdi-lock</v-icon></div>
                <p class="text-center font-weight-bold">Enter your email address</p>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-text-field v-model="email" :rules="emailRules" label="E-mail" required class="my-4"></v-text-field>
                    <v-btn block color="primary" @click="validate"> Change your password </v-btn>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script lang="ts" setup>
import { ref } from 'vue';

const form = ref();
const valid = ref(true);
const email = ref<string>('');
const emailRules = [(v: string) => !!v || 'E-mail is required', (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'];

async function validate() {
    const { valid } = await form.value.validate();

    if (valid) alert('Form is valid');
}
</script>

<style scoped>
.custom-link:link,
.custom-link:visited {
    color: #424242;
    text-decoration: none;
}
</style>
