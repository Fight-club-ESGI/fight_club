export interface ISignin {
    email: string;
    password: string;
}

export interface ISignup {
    username: string;
    email: string;
    password: string;
}

export interface IToken {
    token: string;
    refresh_token: string;
}