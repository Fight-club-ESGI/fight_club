<template>
    <v-card class="pa-5">
        <v-card-title>Pending requests</v-card-title>
        <v-card-text v-if="pendingSponsorships.sponsorship && pendingSponsorships.sponsorship.length > 0" class="pt-5">
            <table class="w-full">
                <th v-for="tableHeader in tableHeaders" class="w-100 text-left pl-2 pb-2">
                    {{ tableHeader }}
                </th>
                <tbody>
                    <tr v-for="(request, i) of pendingSponsorships.sponsorship" :class="[i % 2 !== 1 ? 'bg-white' : ''] + ' h-10'">
                        <td class="pl-2">{{ request.sponsored.username }}</td>
                        <td class="pl-2">{{ request.sponsored.email }}</td>
                        <td class="pl-2 font-bold">PENDING</td>
                        <td>
                            <v-btn size="small" @click="setVIP(request.id)">set vip</v-btn>
                        </td>
                    </tr>
                </tbody>
            </table>
        </v-card-text>
        <v-card-text v-else> No pending requests </v-card-text>
    </v-card>
</template>

<script>
import { storeToRefs } from 'pinia';
import { useSponsorshipStore } from '../stores/sponsorship';
import { onMounted } from 'vue';
export default {
    setup() {
        const tableHeaders = ['Username', 'Email', 'Status'];

        const sponsorshipStore = useSponsorshipStore();
        const { getPendingSponsorships, acceptRequest, getAcceptedSponsorships } = sponsorshipStore;
        const { pendingSponsorships } = storeToRefs(sponsorshipStore);

        const setVIP = async (id) => {
            try {
                await acceptRequest(id);
                await getPendingSponsorships();
                await getAcceptedSponsorships();
            } catch (e) {}
        };

        onMounted(async () => {
            try {
                await getPendingSponsorships();
            } catch (e) {}
        });

        return { pendingSponsorships, tableHeaders, setVIP };
    },
};
</script>
