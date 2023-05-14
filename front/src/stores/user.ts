import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { userService } from '../service/api';
import type { ISignin, ISignup } from '@/interfaces/security';
import type { userInterface } from '@/interfaces/responseAPI';
import { token, refreshToken } from '@/service';
import { useRouter } from "vue-router"
import { useCartStore } from './cart';
import { UpdateUser } from '@/interfaces/user';

export const useUserStore = defineStore('user', () => {
    const router = useRouter();

    const cartStore = useCartStore();
    const { getCart } = cartStore;

    const { _signin, _signup, _getSelfUser, _getUsers, _signinWithToken, _checkTokenValidity, _changePassword, _resetPassword, _validateResetPassword, _updateUser } = userService;


    const user = ref<userInterface | undefined>()


    const users = ref<userInterface[]>([]);

    const isAdmin = computed(() => {
        return user.value?.roles?.includes('ROLE_ADMIN');
    });

    const isVIP = computed(() => {
        return user.value?.roles?.includes('ROLE_VIP') || user.value?.roles?.includes('ROLE_VVIP') || user.value?.roles?.includes('ROLE_ADMIN');
    });

    const isConnected = computed(() => {
        return !!user.value?.email;
    });

    async function toggleAdmin() {
        if (user.value) {
            if (user.value.roles?.includes('ROLE_ADMIN')) {
                user.value.roles = ['ROLE_USER']
            } else {
                user.value.roles = ['ROLE_ADMIN']
            }
        }
    }

    async function signin(payload: ISignin) {
        try {
            const res = await _signin(payload);
            token.value = res.token;
            refreshToken.value = res.refresh_token;
            const self = await _getSelfUser();
            user.value = self;
            await getCart();
        } catch (error) {
            throw error;
        }
    }

    async function signup(payload: ISignup) {
        try {
            const res = await _signup(payload);
        } catch (error) {
            throw error;
        }
    }

    async function signinWithToken(refreshToken: string) {
        try {
            const res = await _signinWithToken(refreshToken);
            token.value = res.token;
            const self = await _getSelfUser();
            user.value = self;
            await getCart();
        } catch (error) {
            throw error;
        }
    }

    async function changePassword(payload: { password: string, newPassword: string }): Promise<void> {
        try {
            await _changePassword(payload);
        } catch (error) {
            throw error;
        }
    }

    async function resetPassword(payload: { email: string }): Promise<void> {
        try {
            await _resetPassword(payload);
        } catch (error) {
            throw error;
        }
    }

    async function validateResetPassword(payload: { token: string, password: string }): Promise<void> {
        try {
            await _validateResetPassword(payload);
        } catch (error) {
            throw error;
        }
    }

    async function checkTokenValidity(payload: { token: string }) {
        try {
            await _checkTokenValidity(payload);
        } catch (error) {
            throw error;
        }
    }

    async function logout() {
        try {
            user.value = undefined;
            router.push({ name: 'login' });
            token.value = "";
            refreshToken.value = "";
        } catch (error) {
            throw error;
        }
    }

    async function getUsers() {
        try {
            users.value = await _getUsers();
        } catch (error) {
            throw error;
        }
    }

    async function updateUser(payload: UpdateUser) {
        try {
            const res = await _updateUser(payload);
            user.value = res;
        } catch (error) {
            throw error;
        }
    }

    return { signin, signup, isAdmin, isConnected, user, toggleAdmin, logout, getUsers, users, signinWithToken, changePassword, checkTokenValidity, resetPassword, updateUser, validateResetPassword, isVIP }
});
