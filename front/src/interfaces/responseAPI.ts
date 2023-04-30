export interface userInterface {
  id: string | null
  username: string | null
  roles: Array<string> | null
  email: string | null
  sponsorshipAsSponsor: Array<string>
  createdAt: string | null
  updatedAt: string | null
  wallet: WalletInterface
}

export interface WalletInterface {
  id: string | null
  amount: number | null
  createdAt: string | null
  updatedAt: string | null
}

export interface SponsorshipResponseI {
  sponsor: string | null
  sponsored: string | null
  email_validation: boolean
  sponsor_validation: boolean
  id: string | null
  emailValidation: boolean
  sponsorValidation: boolean
}

export interface UserTokenInterface{

}

export interface WalletTransactionInterface{
  id: string | null
  amount: number | null
  status: string | null
  wallet: string | null
  type: string | null
  stripe_ref: string | null
  createdAt: string | null
  updatedAt: string | null
}
