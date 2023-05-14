export interface IUser {
    id: string
    email: string
    username: string
    roles: string[]
    emailValidated: boolean
}

export interface CreateUser {
    email: string
    username: string
    roles: string[]
}

export interface UpdateUser {
    id: string
    username: string
}