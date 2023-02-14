export interface userInterface {
  id: string
  username: string
  roles: Array<string>
  email: string
}

export interface SponsorshipResponseI {
  sponsor: string
  sponsored: string
  email_validation: boolean
  sponsor_validation: boolean
  id: string
  emailValidation: boolean
  sponsorValidation: boolean
}