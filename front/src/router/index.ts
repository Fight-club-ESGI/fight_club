import { createRouter, createWebHistory } from "vue-router"
import routes from './routes';
import { useUserStore } from "../stores/user";
import { storeToRefs } from "pinia";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

router.beforeResolve((to, from, next) => {
    const userStore = useUserStore();
    const { isAdmin, isConnected } = storeToRefs(userStore);

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