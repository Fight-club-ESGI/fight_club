import Home from "@/views/Home.vue"
import Fighters from "@/views/Fighters.vue"
import FighterDetails from "@/views/FighterDetails.vue"
import Event from "@/views/Event.vue"
import EventDetails from "@/views/EventDetails.vue"
import Login from "@/views/Login.vue"
import Signup from "@/views/Signup.vue"
import Deposit from "@/views/Deposit.vue"
import Sponsorship from "@/views/admin/Sponsorship.vue"
import FightersAdmin from "@/views/admin/Fighters.vue"
import AdminView from "@/views/admin/AdminView.vue"
import ProfileView from "@/views/Profile.vue";
import UserBets from "@/components/profile/Bets.vue";
import UserTickets from "@/components/profile/Tickets.vue";
import Wallet from "@/components/profile/Wallet.vue";
import Ticketing from '@/views/Ticketing.vue';
import FailedPayment from '@/views/FailedPayment.vue';
import SuccessfulPayment from '@/views/SuccessfulPayment.vue';
import ActivateStatus from "@/views/ActivateStatus.vue";

export default [
    {
        path: '/',
        component: Home,
        name: 'home',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/activate-status/:id',
        component: ActivateStatus,
        name: 'activate-status',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/admin',
        component: AdminView,
        children: [
            {
                path: 'fighters',
                component: FightersAdmin,
                name: 'fighter-admin',
                meta: { requiresAuth: true, requiresAdmin: true }
            },
            {
                path: 'events',
                component: Event,
                name: 'event-admin',
                meta: { requiresAuth: true, requiresAdmin: true }
            },
            {
                path: 'sponsorship',
                component: Sponsorship,
                name: 'sponsorship-admin',
                meta: { requiresAuth: true, requiresAdmin: true }
            },
        ]
    },
    {
        path: '/profile',
        component: ProfileView,
        name: 'user-profile',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/bets',
        component: UserBets,
        name: 'user-bet',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/tickets',
        component: UserTickets,
        name: 'user-tickets',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/wallet',
        component: Wallet,
        name: 'user-wallet',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/fighters',
        component: Fighters,
        name: 'fighters',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/fighters/:id',
        component: FighterDetails,
        name: 'fighter-details',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/events',
        component: Event,
        name: 'events',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/events/:id',
        component: EventDetails,
        name: 'event-details',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/signup',
        component: Signup,
        name: 'signup',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/deposit',
        component: Deposit,
        name: 'deposit',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/ticketing',
        component: Ticketing,
        name: 'ticketing',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/failed-payment',
        component: FailedPayment,
        name: 'failed-payment',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/successful-payment',
        component: SuccessfulPayment,
        name: 'successful-payment',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
]