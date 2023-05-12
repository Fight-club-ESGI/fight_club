<template>
    <div class="px-10">
        <v-card>
            <v-container>
                <v-row justify="center" class="pt-4">
                    <v-col cols="10">
                        <v-form v-model="valid" ref="form">
                            <v-text-field autofocus v-model="email" @keydown.enter.prevent="submit"
                                hint="Press enter to send the invitation"
                                placeholder="Enter the mail that you want to be sponsored" type="email" :rules="rules.email"
                                append-icon="mdi-send" @click:append="submit" lazy-validation>
                            </v-text-field>
                        </v-form>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
        <div class="flex gap-x-4 pt-3">
            <pending-request class="flex-1" />
            <accepted-request class="flex-1" />
        </div>
    </div>
</template>
<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useUserStore } from '../stores/user';
import { useSponsorshipStore } from '../stores/sponsorship';
import { createToast } from 'mosha-vue-toastify';
import PendingRequest from '@/components/PendingRequest.vue';
import AcceptedRequest from '@/components/AcceptedRequest.vue';

const userStore = useUserStore();
const { getUsers } = userStore;
const { user } = storeToRefs(userStore);

const sponsorshipStore = useSponsorshipStore();
const { sendSponsoLink } = sponsorshipStore;

const email = ref<string>('');
const valid = ref<boolean>();
const form = ref();

const rules = {
    email: [(v: string) => !!v || 'E-mail is required', (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'],
};

onMounted(async () => {
    try {
        await getUsers();
    } catch (error) { }
});

const submit = async (event: any) => {
    const { valid } = await form.value.validate();
    if (valid) {
        try {
            const payload = {
                sponsorId: user.value.id,
                sponsoredEmail: email.value,
            };
            await sendSponsoLink(payload);
        } catch {
            createToast('Error while sending sponsorship link', { type: 'danger', position: 'bottom-right' });
        }
        email.value = '';
        event.target.blur();
        form.value.reset();
    }
};
</script>
