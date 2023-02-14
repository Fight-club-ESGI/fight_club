import { SigninI, SignupI, TokenI, UserI } from "../../interfaces/payload";
import { userInterface } from "../../interfaces/responseAPI";
import { client, clientWithoutAuth, token } from "../index";
class User {

    async _signin(payload: SigninI): Promise<TokenI> {
        try {
            
            const uri = '/authentication_token'
            const res = await clientWithoutAuth.post(uri, payload);
            token.value = res.data.token;
            return res.data;
        } catch (error) {
            throw error;
        }
    } 

    async _signinWithToken(token: string): Promise<TokenI> {
        try {
            const uri = '/tokenSignin'
            const res = await clientWithoutAuth.post(uri, { token: token });
            return res.data;
        } catch (error) {
            throw error;
        }
    } 

    async _changePassword(payload: { password: string, newPassword: string }): Promise<void> {
        try {
            const uri = '/change-password'
            await client.put(uri, payload);
        } catch (error) {
            throw error;
        }
    } 

    async _signup(payload: SignupI): Promise<void> {
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

    
    async _updateUser(payload: {id: string }): Promise<userInterface> {
        try {
            const uri = `/users/${payload.id}`;
            const res = await client.put(uri, payload);
            return res.data;
        } catch (error) {
            throw error;
        }
    } 
}

const userService = new User();

export default userService;