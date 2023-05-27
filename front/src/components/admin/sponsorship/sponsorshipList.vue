<script setup lang=ts>
import { useSponsorshipStore } from '@/stores/sponsorship';
import { storeToRefs } from 'pinia';
import { onMounted, ref, computed } from 'vue';

const sponsorshipStore = useSponsorshipStore();
const { getPendingSponsorships, removeSponsorship, getAcceptedSponsorships, acceptRequest } = sponsorshipStore;
const { everySponso } = storeToRefs(sponsorshipStore);

const isPending = ref<boolean>(false);

onMounted(async () => {
    try {
        await getPendingSponsorships();
        await getAcceptedSponsorships();
    } catch (error) {

    }
});

const filteredSponsorship = computed(() => {
    if (isPending.value) {
        return everySponso.value.filter(sponso => !sponso.sponsorValidation)

    } else {
        return everySponso.value;
    }
});

const removeVIP = async (id: string) => {
    try {
        await removeSponsorship(id);
        await getPendingSponsorships();
        await getAcceptedSponsorships();
    } catch (e) { }
};

const setVIP = async (id: string) => {
    try {
        await acceptRequest(id);
        await getPendingSponsorships();
        await getAcceptedSponsorships();
    } catch (e) { }
};
</script>

<template>
    <div>
        <div class="flex gap-x-4">
            <v-switch v-model="isPending" class="contents" color="amber-500" label="Pending" hide-details />
        </div>
        <div v-if="Object.keys(everySponso).length > 0" class="flex gap-x-4 pt-3 flex-1">
            <table class="min-w-full">
                <thead class="bg-white border-b">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            #
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Email
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Role
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Status
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(sponso, index) of filteredSponsorship" class="bg-gray-100 border-b">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 }}</td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{ sponso.sponsored.email }}
                        </td>
                        <td class="text-sm text-gray-900 font-medium px-6 py-4 whitespace-nowrap">
                            {{ sponso.sponsored.roles.includes('ROLE_VVIP') ? 'VIP' : 'USER' }}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <v-chip
                                :color="sponso.sponsored.status === 'ACCEPTED' ? '#059669' : 'amber-500'">{{ sponso.sponsored.status }}</v-chip>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <template v-if="sponso.sponsored.roles.includes('ROLE_VVIP')">
                                <v-btn variant="tonal" size="small" @click="removeVIP(sponso.id)">remove</v-btn>
                            </template>
                            <div v-else class="flex gap-x-3">
                                <v-btn variant="tonal" size="small" @click="removeVIP(sponso.id)">remove</v-btn>
                                <v-btn size="small" variant="tonal" @click="setVIP(sponso.id)">set vip</v-btn>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-lightgray font-bold">
            <i>No sponsor added !</i>
        </div>
    </div>
</template>