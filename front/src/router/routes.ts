import Home from "@/views/Home.vue"
import Fighters from "@/views/fight/Fighters.vue"
import FighterDetails from "@/views/fight/FighterDetails.vue"
import Event from "@/views/event/Event.vue"
import EventDetails from "@/views/event/EventDetails.vue"
import Login from "@/views/session/Login.vue"
import Signup from "@/views/session/Signup.vue"
import Deposit from "@/views/user/Deposit.vue"
import Sponsorship from "@/views/admin/Sponsorship.vue"
import FightersAdmin from "@/views/admin/Fighters.vue"
import AdminView from "@/views/admin/AdminView.vue"
import ProfileView from "@/views/user/Profile.vue";
import EventDetailsAdmin from "@/views/admin/EventDetails.vue";
import UserBetsHistory from "@/views/bet/UserBetsHistory.vue";
import UserTicketsHistory from "@/views/ticketing/UserTicketsHistory.vue";
import Wallet from "@/components/profile/Wallet.vue";
import Ticketing from '@/views/ticketing/Ticketing.vue';
import FailedPayment from '@/views/checkout/FailedPayment.vue';
import SuccessfulPayment from '@/views/checkout/SuccessfulPayment.vue';
import ActivateStatus from "@/views/user/ActivateStatus.vue";
import ResetPassword from "@/views/session/ResetPassword.vue";
import ValidateResetPassword from "@/views/session/ValidateResetPassword.vue";
import Session from "@/views/session/Session.vue";
import InvalidToken from "@/views/InvalidToken.vue";
import CheckoutConfirmation from "@/views/checkout/Confirmation.vue";
import BecomeVIP from "@/views/sponsor/BecomeVIP.vue";

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
        meta: { requiresAuth: false, requiresAdmin: false, hideHeader: true }
    },
    {
        path: '/resetpassword',
        component: ResetPassword,
        name: 'reset-password',
        meta: { requiresAuth: true, requiresAdmin: false }
    },
    {
        path: '/users/validate/password',
        component: ValidateResetPassword,
        name: 'validate-password',
        meta: { requiresAuth: false, requiresAdmin: false, hideHeader: true }
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
                path: 'events/:id',
                component: EventDetailsAdmin,
                name: 'event-details-admin',
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
        meta: { requiresAuth: true, requiresAdmin: false }
    },
    {
        path: '/bets',
        component: UserBetsHistory,
        name: 'user-bets-history',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/tickets',
        component: UserTicketsHistory,
        name: 'user-tickets-history',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/wallet',
        component: Wallet,
        name: 'user-wallet',
        meta: { requiresAuth: false, requiresAdmin: false }
    },
    {
        path: '/checkout/confirmation',
        name: 'checkout-confirmation',
        component: CheckoutConfirmation,
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
        path: '/session',
        component: Session,
        name: 'session',
        redirect: '/session/login',
        children: [
            {
                path: 'login',
                component: Login,
                name: 'login',
                meta: { requiresAuth: false, requiresAdmin: false, hideHeader: true }
            },
            {
                path: 'register',
                component: Signup,
                name: 'register',
                meta: { requiresAuth: false, requiresAdmin: false, hideHeader: true }
            },
            {
                path: 'forgot-password',
                component: ResetPassword,
                name: 'forgot-password',
                meta: { requiresAuth: false, requiresAdmin: false, hideHeader: true }
            }
        ]
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
    {
        path: '/invalid-token',
        component: InvalidToken,
        name: 'invalid-token',
        meta: { requiresAuth: false, requiresAdmin: false, hideHeader: true }
    },
    {
        path: '/become-vip',
        component: BecomeVIP,
        name: 'become-vip',
        meta: { requiresAuth: false, requiresAdmin: false, hideHeader: true }
    }
]
