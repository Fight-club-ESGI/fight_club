<template>
    <v-row no-gutters justify="center">
        <v-col cols="10" md="6" lg="5">
            <v-card class="pa-5 mt-12">
                <h1 class="text-2xl font-bold pb-5">Change Password</h1>
                <v-form v-model="valid" ref="form">
                    <v-text-field
                        v-model="newPassword"
                        :rules="[rules.required, rules.minLength]"
                        prepend-icon="mdi-lock-outline"
                        type="password"
                        placeholder="New Password"
                        label="New Password"
                    />
                    <v-text-field
                        v-model="confirmPassword"
                        :rules="[rules.required, rules.minLength, rules.samePassword]"
                        prepend-icon="mdi-lock-outline"
                        type="password"
                        placeholder="Confirm Password"
                        label="Confirm Password"
                    />
                    <v-btn block color="primary" @click="validate">Confirm</v-btn>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import { useUserStore } from '@/stores/user';
import { createToast } from 'mosha-vue-toastify';
import { useRoute } from 'vue-router';
export default defineComponent({
    setup() {
        const route = useRoute();
        const userToken = route.query.token;
        const userStore = useUserStore();
        const { validateResetPassword } = userStore;

        const form = ref();
        const valid = ref<boolean>(false);

        const newPassword = ref<string>('');
        const confirmPassword = ref<string>('');

        const rules = {
            required: (v: string) => !!v || 'Value required',
            samePassword: (v: string) => v === newPassword.value || 'Password does not match',
            minLength: (v: string) => v.length >= 7 || 'Password must be 7 characters minimum',
        };

        const validate = async () => {
            try {
                const { valid } = await form.value.validate();
                if (valid) {
                    await validateResetPassword({ token: userToken, password: newPassword.value });
                    createToast('Password changed', { type: 'success', position: 'bottom-right' });
                }
            } catch (error) {
                createToast('Passwords incorrect', { type: 'danger', position: 'bottom-right' });
            }
        };

        return { valid, newPassword, confirmPassword, validate, rules, form, userToken };
    },
});
</script>
