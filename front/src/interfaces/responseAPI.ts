export interface userInterface {
  id: string | null
  username: string | null
  roles: Array<string> | null
  email: string | null
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
