<template>
    <div>
        <v-dialog class="w-2/3" v-model="dialog">
            <template v-slot:activator="{ props }">
                <div class="flex w-full">
                    <v-card v-bind="props"
                        class="flex cursor-pointer w-30 relative bg-neutral-600 text-white items-center mx-auto rounded-sm">
                        <p class="text-center w-full font-weight-bold">
                            Bets
                        </p>
                    </v-card>
                </div>
            </template>
            <v-card class="text-center p-10">
                <v-card-title class="font-bold">
                    Bet on the fight
                </v-card-title>
                <div class="w-full flex">
                    <v-card @click="() => {
                        bet.betOn = '/fighters/' + fight.fighterA.id
                    }" class="w-full h-92 w-92 flex-col bg-neutral-800 text-white"
                        :class="bet.betOn === '/fighters/' + fight.fighterA.id ? 'border-4' : ''">
                        <div :style="fight.fighterA.imageName ? `background-image: url('${fight.fighterA.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`"
                            class="h-1/2 bg-cover bg-center">
                            <div class="h-full w-full bg-gradient-to-t from-neutral-800 to-transparent" />
                        </div>
                        <div class="flex items-center pa-5">
                            <div class="text-2xl font-bold truncate">{{ fight.fighterA.firstname }}
                                {{ fight.fighterA.lastname }}</div>
                            <Icon class="text-2xl ml-2"
                                :icon="fight.fighterA.gender === 'male' ? 'mdi:gender-male' : 'mdi:gender-female'" />
                        </div>
                        <div class="text-2xl bg-neutral-800">
                            {{ fight.odds.fighterAOdds }}
                        </div>
                        <div class="mt-auto flex gap-x-1 pa-5 text-black">
                            <div class="flex align-center gap-2 bg-neutral-200 p-2 rounded-lg">
                                <Icon icon="material-symbols:flag" />
                                <p class="text-sm font-bold">{{ fight.fighterA.nationality }}</p>
                            </div>
                            <div class="flex align-center gap-2 bg-neutral-200 p-2 rounded-lg">
                                <Icon icon="material-symbols:weight" />
                                <p class="text-sm font-bold">{{ fight.fighterA.weight }} kg</p>
                            </div>
                            <div class="flex align-center gap-2 bg-neutral-200 p-2 rounded-lg">
                                <Icon icon="mdi:human-male-height-variant" />
                                <p class="text-sm font-bold">{{ fight.fighterA.height }} cm</p>
                            </div>
                        </div>
                    </v-card>
                    <div class="flex-grow-1 flex-col p-4 text-left">
                        <div>
                            <p class="font-bold">Bet on :</p>
                            <p class="truncate text-center font-bold my-6">
                                {{
                                    bet.betOn === '/fighters/' + fight.fighterA.id ? fight.fighterA.firstname + ' ' + fight.fighterA.lastname :
                                    bet.betOn === '/fighters/' + fight.fighterB.id ? fight.fighterB.firstname + ' ' + fight.fighterB.lastname :
                                        'Select a fighter'
                                }}
                            </p>
                        </div>
                        <div class="">
                            <p class="font-bold">Estimation gain :</p>
                            <p class="text-center text-2xl font-bold my-6">
                                {{
                                    bet.betOn === '/fighters/' + fight.fighterA.id ? (fight.odds.fighterAOdds * bet.amount - bet.amount).toFixed(2) :
                                    bet.betOn === '/fighters/' + fight.fighterB.id ? (fight.odds.fighterBOdds * bet.amount - bet.amount).toFixed(2) :
                                        0
                                }} €
                            </p>
                        </div>
                        <v-text-field v-model.number="bet.amount" :rules="[rules.required]" type="number" min="1"
                            placeholder="1" label="Amount" />
                        <div class="flex">
                            <v-btn class="normal-case rounded-none" color="primary" @click="payWallet()">Pay with
                                wallet</v-btn>
                            <v-btn class="normal-case rounded-none" color="secondary" @click="payDirect()">Pay with
                                Stripe</v-btn>
                        </div>
                    </div>
                    <v-card @click="() => bet.betOn = '/fighters/' + fight.fighterB.id"
                        class="w-full h-92 w-92 flex-col bg-neutral-800 text-white"
                        :class="bet.betOn === '/fighters/' + fight.fighterB.id ? 'border-4' : ''">
                        <div :style="fight.fighterB.imageName ? `background-image: url('${fight.fighterB.imageName}')` : `background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')`"
                            class="h-1/2 bg-cover bg-center">
                            <div class="h-full w-full bg-gradient-to-t from-neutral-800 to-transparent" />
                        </div>
                        <div class="flex items-center pa-5">
                            <div class="text-2xl font-bold truncate">{{ fight.fighterB.firstname }}
                                {{ fight.fighterB.lastname }}</div>
                            <Icon class="text-2xl ml-2"
                                :icon="fight.fighterB.gender === 'male' ? 'mdi:gender-male' : 'mdi:gender-female'" />
                        </div>
                        <div class="text-2xl bg-neutral-800">
                            {{ fight.odds.fighterBOdds }}
                        </div>
                        <div class="mt-auto flex gap-x-1 pa-5 text-black">
                            <div class="flex align-center gap-2 bg-neutral-200 p-2 rounded-lg">
                                <Icon icon="material-symbols:flag" />
                                <p class="text-sm font-bold">{{ fight.fighterB.nationality }}</p>
                            </div>
                            <div class="flex align-center gap-2 bg-neutral-200 p-2 rounded-lg">
                                <Icon icon="material-symbols:weight" />
                                <p class="text-sm font-bold">{{ fight.fighterB.weight }} kg</p>
                            </div>
                            <div class="flex align-center gap-2 bg-neutral-200 p-2 rounded-lg">
                                <Icon icon="mdi:human-male-height-variant" />
                                <p class="text-sm font-bold">{{ fight.fighterB.height }} cm</p>
                            </div>
                        </div>
                    </v-card>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts" setup>
import { ref, reactive, defineProps, watch, PropType } from 'vue';
import { createToast } from 'mosha-vue-toastify';
import { useBetStore } from "@/stores/bet";
import { IFight } from "@/interfaces/fight";
import { CreateBetI } from "@/interfaces/bet";
import { Icon } from '@iconify/vue';
import { useRouter } from "vue-router";

const props = defineProps({
    fight: { type: Object as PropType<IFight>, required: true },
});

const { createBetWallet, createBetDirect } = useBetStore();

const dialog = ref<boolean>(false);
const valid = ref<boolean>(false);

const file = ref();
const image = ref();

const error = ref(false);

const router = useRouter();

let bet = reactive<CreateBetI>({
    fight: '/fights/' + props.fight.id,
    betOn: '',
    amount: 0,
});

const rules = {
    required: (value: any) => !!value || 'Required.'
};

const payWallet = async () => {
    if (checkForm()) {
        const res = await createBetWallet(bet)
        await router.push({ name: 'checkout-confirmation', query: { transaction_id: res.id } })
        dialog.value = false;
        resetBet()
    }
}

const payDirect = async () => {
    try {
        if (checkForm()) {
            window.location.href = await createBetDirect(bet)
            //createToast('Deposit success', { position: 'bottom-right', type: 'success' });
            //dialog.value = false;
            //resetBet()
        }
    } catch (e) {
        createToast('Error during deposit', { position: 'bottom-right', type: 'danger' });
    }
}

const resetBet = () => {
    bet.betOn = ''
    bet.amount = 0
    dialog.value = false;
}

const checkForm = () => {
    if (!bet.amount) {
        createToast('You must enter an amount', { position: 'bottom-right', type: 'danger' });

        return false
    }

    if (bet.betOn === '') {
        createToast('You must select a fighter', { position: 'bottom-right', type: 'danger' });

        return false
    }

    return true
}

</script>