import { createRouter, createWebHistory, useRoute } from "vue-router"
import routes from './routes';
import { useUserStore } from "@/stores/user";
import { storeToRefs } from "pinia";
import { refreshToken } from "@/service";
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

router.beforeResolve(async (to, from, next) => {
    const userStore = useUserStore();
    const { isAdmin, isConnected } = storeToRefs(userStore);
    const { signinWithToken } = userStore;
    const route = useRoute();

    if (!isConnected.value) {
        if (refreshToken.value) {
            try {
                await signinWithToken(refreshToken.value);
            } catch (error) {
                console.error(error)
            }
        }
    }

    if (to?.meta?.requiresAuth) {
        if (!isConnected.value) next({ name: 'login' })
        else if (to?.meta?.requiresAdmin) {
            if (!isAdmin.value) next({ name: 'login' });
            else next();
        } else next();
    } else {
        next();
    }
})

export default router;
