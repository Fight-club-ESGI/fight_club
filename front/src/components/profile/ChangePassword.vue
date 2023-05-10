<template>
    <div class="p-5">
        <v-form v-model="valid" ref="form">
            <v-text-field v-model="oldPassword" :rules="[rules.required]" type="password" placeholder="Old Password"
                label="Old Password" />
            <v-text-field v-model="newPassword" :rules="[rules.required, rules.minLength]" type="password"
                placeholder="New Password" label="New Password" />
            <v-text-field v-model="confirmPassword" :rules="[rules.required, rules.minLength, rules.samePassword]"
                type="password" placeholder="Confirm Password" label="Confirm Password" />
        </v-form>
        <div class="w-full">
            <v-btn class="w-1/3 mx-3 bg-red-100" color="primary" @click="validate()">Confirm</v-btn>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import { useUserStore } from '@/stores/user';
import { createToast } from 'mosha-vue-toastify';

export default defineComponent({
    setup() {
        const userStore = useUserStore();
        const { changePassword } = userStore;

        const form = ref();
        const valid = ref<boolean>(false);

        const oldPassword = ref<string>('');
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
                    await changePassword({ password: oldPassword.value, newPassword: newPassword.value });
                    createToast('Password changed', { type: 'success', position: 'bottom-right' });
                    oldPassword.value = "";
                    newPassword.value = "";
                    confirmPassword.value = "";
                }
            } catch (err) {
                createToast("Error while updating password, please try again", { type: 'danger', position: 'bottom-right' });
            }
        };

        return { valid, oldPassword, newPassword, confirmPassword, validate, rules, form };
    },
});
</script>
