<template>
    <div class="px-10">
        <addSponsor />
        <sponsorship-list></sponsorship-list>
    </div>
</template>
<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useUserStore } from '../stores/user';
import { createToast } from 'mosha-vue-toastify';
import { useSponsorshipStore } from '../stores/sponsorship';
import sponsorshipList from './admin/sponsorship/sponsorshipList.vue';
import addSponsor from './dialogs/addSponsor.vue';

const userStore = useUserStore();
const { getUsers } = userStore;
const { user } = storeToRefs(userStore);

const sponsorshipStore = useSponsorshipStore();

onMounted(async () => {
    try {
        await getUsers();
    } catch (error) { }
});

</script>
