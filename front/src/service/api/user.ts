import { ISignin, ISignup, IToken } from "@/interfaces/security";
import { userInterface } from "@/interfaces/responseAPI";
import { client, clientWithoutAuth, token } from "../index";
import { UpdateUser } from "@/interfaces/user";
class User {

    async _signin(payload: ISignin): Promise<IToken> {
        try {

            const uri = '/authentication_token'
            const res = await clientWithoutAuth.post(uri, payload);
            token.value = res.data.token;
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _signinWithToken(refreshToken: string): Promise<IToken> {
        try {
            const uri = '/token/refresh'
            const res = await clientWithoutAuth.post(uri, { refresh_token: refreshToken });
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _changePassword(payload: { password: string, newPassword: string }): Promise<void> {
        try {
            const uri = '/change_password'
            await client.post(uri, payload);
        } catch (error) {
            throw error;
        }
    }

    async _resetPassword(payload: { email: string }): Promise<void> {
        try {
            const uri = '/reset_password';
            await clientWithoutAuth.post(uri, payload);
        } catch (error) {
            throw error;
        }
    }

    async _validateResetPassword(payload: { token: string, password: string }) {
        try {
            const uri = '/validate_reset_password';
            await clientWithoutAuth.post(uri, payload);
        } catch (error) {
            throw error;
        }
    }

    async _checkTokenValidity(payload: { token: string }) {
        try {
            const uri = `/check_token_validity/${payload.token}`;
            return clientWithoutAuth.get(uri);
        } catch (error) {
            throw error;
        }
    }

    async _signup(payload: ISignup): Promise<void> {
        try {
            const uri = '/users'
            const res = await clientWithoutAuth.post(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getSelfUser(): Promise<userInterface> {
        try {
            const uri = '/me'
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _getUsers(): Promise<userInterface[]> {
        try {
            const uri = '/users'
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }


    async _updateUser(payload: UpdateUser): Promise<userInterface> {
        try {
            const uri = `/users/${payload.id}`;
            const res = await client.patch(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const userService = new User();

export default userService;