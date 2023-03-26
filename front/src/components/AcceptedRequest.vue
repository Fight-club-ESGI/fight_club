<template>
    <v-card class="pa-5">
        <v-card-title>Accepted requests</v-card-title>
        <v-card-text v-if="acceptedSponsorships.sponsorship && acceptedSponsorships.sponsorship.length > 0" class="pt-5">
            <table class="w-full">
                <th v-for="tableHeader in tableHeaders" class="w-100 text-left pl-2 pb-2">
                    {{ tableHeader }}
                </th>
                <tbody>
                    <tr v-for="(request, i) of acceptedSponsorships.sponsorship" :class="[i % 2 !== 1 ? 'bg-white' : ''] + ' h-10'">
                        <td class="pl-2">{{ request.sponsored.username }}</td>
                        <td class="pl-2">{{ request.sponsored.email }}</td>
                        <td class="pl-2 font-bold">VIP</td>
                        <td>
                            <v-btn size="small" @click="removeVIP(request.id)">remove</v-btn>
                        </td>
                    </tr>
                </tbody>
            </table>
        </v-card-text>
        <v-card-text v-else> No requests </v-card-text>
    </v-card>
</template>

<script lang="ts">
import { storeToRefs } from 'pinia';
import { useSponsorshipStore } from '../stores/sponsorship';
import { onMounted } from 'vue';
export default {
    setup() {
        const tableHeaders = ['Username', 'Email', 'Status'];

        const sponsorshipStore = useSponsorshipStore();
        const { getAcceptedSponsorships, acceptRequest, removeSponsorship, getPendingSponsorships } = sponsorshipStore;
        const { acceptedSponsorships } = storeToRefs(sponsorshipStore);

        const removeVIP = async (id: string) => {
            try {
                await removeSponsorship(id);
                await getPendingSponsorships();
                await getAcceptedSponsorships();
            } catch (e) {}
        };

        onMounted(async () => {
            try {
                await getAcceptedSponsorships();
            } catch (e) {}
        });

        return { acceptedSponsorships, tableHeaders, removeVIP };
    },
};
</script>
