<template>
    <div class="p-5">
        <v-form v-model="valid" ref="form">
            <v-text-field v-model="user.username" :rules="[rules.required]" placeholder="username" label="username" />
            <v-text-field v-model="user.email" disabled label="email" />
            <div class="w-full">
                <v-btn class="w-1/3 mx-auto" color="primary" @click="validate()">Confirm</v-btn>
            </div>
        </v-form>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import { useUserStore } from '../../stores/user';
import { storeToRefs } from 'pinia';
import { createToast } from 'mosha-vue-toastify';
export default defineComponent({
    setup() {
        const userStore = useUserStore();
        const { user } = userStore;
        const { updateUser } = userStore;

        const form = ref();
        const valid = ref<boolean>(false);

        const rules = {
            required: (v: string) => !!v || 'Value required',
        };

        const validate = async () => {
            try {
                const { valid } = await form.value.validate();
                if (valid) {
                    await updateUser({ username: user.username, id: user.id });
                    createToast('User updated', { type: 'success', position: 'bottom-right' });
                }
            } catch (error) {
                createToast('Passwords incorrect', { type: 'danger', position: 'bottom-right' });
            }
        };

        return { user, validate, form, valid, rules };
    },
});
</script>
