<template>
    <div>
        <v-dialog v-model="dialog" class="w-2/3">
            <template v-slot:activator="{ props }">
                <v-btn color="primary" v-bind="props" variant="tonal"> Add a sponsor </v-btn>
            </template>

            <v-card class="text-center">
                <v-card-title class="font-bold p-10"> Add a sponsor </v-card-title>
                <div class="w-full flex px-10">
                    <v-form v-model="valid" ref="form" class="flex flex-col w-full">
                        <v-text-field autofocus v-model="email" @keydown.enter.prevent="submit"
                            hint="Press enter to send the invitation"
                            placeholder="Enter the mail that you want to be sponsored" type="email" :rules="rules.email"
                            append-icon="mdi-send" @click:append="submit" lazy-validation>
                        </v-text-field>
                    </v-form>

                </div>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                        <v-btn color="secondary" @click="submit($event)" :disabled="loading">Send</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts" setup>
import { useUserStore } from '@/stores/user';
import { storeToRefs } from 'pinia';
import { ref } from 'vue';
import { useSponsorshipStore } from '@/stores/sponsorship';
import { createToast } from 'mosha-vue-toastify';
const userStore = useUserStore()
const { user } = storeToRefs(userStore)

const sponsorshipStore = useSponsorshipStore();
const { sendSponsoLink } = sponsorshipStore;

const dialog = ref<boolean>(false)
const valid = ref<boolean>(false)
const loading = ref<boolean>(false)
const email = ref<string>('')
const form = ref()

const rules = {
    email: [(v: string) => !!v || 'E-mail is required', (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'],
};

const submit = async (event: any) => {
    const { valid } = await form.value.validate();
    if (valid) {
        try {
            const payload = {
                sponsorId: user.value.id,
                sponsoredEmail: email.value,
            };
            loading.value = true;
            await sendSponsoLink(payload);
            loading.value = false;
            createToast('Sponsorship link sent', { type: 'success', position: 'bottom-right' });
        } catch {
            createToast('Error while sending sponsorship link', { type: 'danger', position: 'bottom-right' });
        }
        loading.value = false;

        email.value = '';
        event.target.blur()
        form.value.reset();
    }
};
</script>
