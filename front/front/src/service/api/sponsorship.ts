import { SponsorshipI } from "../../interfaces/payload";
import { SponsorshipResponseI } from "../../interfaces/responseAPI";
import { client, clientWithoutAuth } from "../index";

const namespace = '/sponsorships'
class Sponsorship {

    async _sendSponsoLink(payload: { sponsorId: string, sponsored: string }): Promise<void> {
        try {
            const res = await client.post('/sponsor-link', payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _acceptRequest(sponsorshipId: string): Promise<void> {
        try {
            const uri = `${namespace}/${sponsorshipId}/accept-request`;
            const res = await client.post(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _removeSponsorship(sponsorshipId: string): Promise<void> {
        try {
            const uri = `${namespace}/requests/remove/${sponsorshipId}`;
            const res = await client.post(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
   
    async _validateEmail(sponsoredId: string): Promise<void> {
        try {
            const uri = `${namespace}/${sponsoredId}/validate-email`;
            console.log(uri)
            const res = await clientWithoutAuth.post(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getPendingSponsorships(): Promise<SponsorshipResponseI[]> {
        try {
            const uri = `${namespace}/requests/pending`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    } 

    async _getAcceptedSponsorships(): Promise<SponsorshipResponseI[]> {
        try {
            const uri = `${namespace}/requests/accepted`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const sponsorshipService = new Sponsorship();

export default sponsorshipService;