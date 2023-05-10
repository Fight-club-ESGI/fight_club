export interface SponsorshipI {
    sponsor: string;
    sponsored: string;
    emailValidation: boolean;
    sponsorValidation: boolean;
}

export interface CreateWeightCategory {
    minWeight: number;
    maxWeight: number;
    name: string;
}

export interface IWeightCategory {
    minWeight: number;
    maxWeight: number;
    name: string;
    id: string;
    updatedAt: string;
    createdAt: string;
}
