import { defineStore } from "pinia";
import { SponsorshipI } from "../interfaces/payload";
import { ref, computed } from "vue";
import { sponsorshipService } from "../service/api";
import { SponsorshipResponseI } from "../interfaces/responseAPI";

export const useSponsorshipStore = defineStore('sponsorship', () => {

    const sponsorship = ref<SponsorshipResponseI>();
    const sponsorships = ref<SponsorshipResponseI[]>([]);
    const pendingSponsorships = ref<SponsorshipResponseI[]>([]);
    const acceptedSponsorships = ref<SponsorshipResponseI[]>([]);

    const everySponso = computed(() => {
        return pendingSponsorships.value.concat(acceptedSponsorships.value);
    });

    async function sendSponsoLink(payload: { sponsorId: string, sponsored: string }) {
        try {
            const res = await sponsorshipService._sendSponsoLink(payload);
            return res;
        } catch (error) {
            throw error;
        }
    }

    async function validateEmail(token: string) {
        try {
            await sponsorshipService._validateEmail(token);
        } catch (error) {
            throw error;
        }
    }

    async function acceptRequest(sponsorshipId: string) {
        try {
            await sponsorshipService._acceptRequest(sponsorshipId);
        } catch (error) {
            throw error;
        }
    }

    async function removeSponsorship(sponsorshipId: string) {
        try {
            await sponsorshipService._removeSponsorship(sponsorshipId);
        } catch (error) {
            throw error;
        }
    }

    async function getPendingSponsorships() {
        try {
            const res = await sponsorshipService._getPendingSponsorships();
            pendingSponsorships.value = res;
            pendingSponsorships.value.map(aS => {
                aS.sponsored.status = "PENDING";
            })
        } catch (error) {
            throw error;
        }
    }

    async function getAcceptedSponsorships() {
        try {
            const res = await sponsorshipService._getAcceptedSponsorships();
            acceptedSponsorships.value = res;
            acceptedSponsorships.value.map(aS => {
                aS.sponsored.status = "ACCEPTED";
            })
        } catch (error) {
            throw error;
        }
    }

    return { acceptRequest, getAcceptedSponsorships, getPendingSponsorships, removeSponsorship, sendSponsoLink, validateEmail, sponsorships, sponsorship, pendingSponsorships, acceptedSponsorships, everySponso }
});