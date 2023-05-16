import axios, { AxiosInstance } from "axios";
import { useStorage } from "@vueuse/core";
import { computed } from "vue";
export const token = useStorage('token', '');
export const refreshToken = useStorage('refreshToken', '');

const config = computed(() => {
    console.log(import.meta.env.VITE_BACKEND_URL)
    console.log(import.meta.env.VITE_PROD)
    return {
        baseURL: import.meta.env.VITE_BACKEND_URL,
        headers: {
            "Content-Type": "application/json",
        }
    }
});

const configFormData = computed(() => {
    return {
        baseURL: import.meta.env.VITE_BACKEND_URL,
        headers: {
            "Content-Type": "multipart/form-data",
        }
    }
});

const client: AxiosInstance = axios.create(config.value);

const clientFormData: AxiosInstance = axios.create(configFormData.value);

const clientWithoutAuth: AxiosInstance = axios.create(config.value);

client.interceptors.request.use(function (config: any) {
    config.headers.Authorization = `Bearer ${token.value ? token.value : ''}`;
    return config;
});

clientFormData.interceptors.request.use(function (config: any) {
    config.headers.Authorization = `Bearer ${token.value ? token.value : ''}`;
    return config;
});

export { client, clientFormData, clientWithoutAuth };