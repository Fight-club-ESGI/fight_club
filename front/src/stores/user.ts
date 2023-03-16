import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { userService } from '../service/api';
import type { SigninI, SignupI } from '../interfaces/payload';
import type { userInterface } from '../interfaces/responseAPI';
import { token } from '../service';
import { useRouter } from "vue-router"

export const useUserStore = defineStore('user', () => {
    const router = useRouter();

    const { _signin, _signup, _getSelfUser, _getUsers, _signinWithToken, _changePassword, _updateUser } = userService;

    const user = ref<userInterface>({
        id: '6162',
        username: 'admin',
        roles: ['ROLE_ADMIN'],
        email: 'admin@gmail.com',
    });

    const users = ref<userInterface[]>([]);

    const isAdmin = computed(() => {
        return user.value?.roles.includes('ROLE_ADMIN');
    });

    const isVIP = computed(() => {
        return user.value?.roles.includes('ROLE_VIP') || user.value?.roles.includes('ROLE_VVIP') || user.value?.roles.includes('ROLE_ADMIN');
    });

    const isConnected = computed(() => {
        return !!user.value?.email;
    });

    async function toggleAdmin() {
        if (user.value) {
            if (user.value.roles.includes('ROLE_ADMIN')) {
                user.value.roles = ['ROLE_USER']
            } else {
                user.value.roles = ['ROLE_ADMIN']
            }
        }
    }

    async function signin(payload: SigninI) {
        try {
            const res = await _signin(payload);
            token.value = res.token;
            const self = await _getSelfUser();
            user.value = self;
        } catch (error) {
            throw error;
        }
    }

    async function signup(payload: SignupI) {
        try {
            const res = await _signup(payload);
        } catch (error) {
            throw error;
        }
    }

    async function signinWithToken(LStoken: string) {
        try {
            const res = await _signinWithToken(LStoken);
            token.value = res.token;
            const self = await _getSelfUser();
            user.value = self;
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

    async function logout() {
        try {
            user.value = {
                id: '',
                username: '',
                roles: [''],
                email: '',
            };
            router.push({ name: 'login' });
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

    async function updateUser(payload: { id: string }) {
        try {
            const res = await _updateUser(payload);
            user.value = res;
        } catch (error) {
            throw error;
        }
    }

    return { signin, signup, isAdmin, isConnected, user, toggleAdmin, logout, getUsers, users, signinWithToken, changePassword, updateUser, isVIP }
});