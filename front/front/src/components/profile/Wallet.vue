<template>
    <v-container>
        <v-row no-gutters justify="center">
            <v-col cols="10" md="6" lg="5">
                <v-card class="pa-5 mt-12">
                    <div class="text-center">
                        <v-icon size="60" class="mb-3">mdi-cash-check</v-icon>
                        <div class="custom-wallet-amount mb-6">{{ wallet }} €</div>
                    </div>
                    <v-row no-gutters>
                        <v-col>
                            <div>Available for withdrawal</div>
                        </v-col>
                        <v-col><div class="text-right">{{ wallet }} €</div></v-col>
                    </v-row>
                    <v-divider class="my-4"></v-divider>
                    <div>
                        <v-text-field type="number" v-model.number="wallet_input_amount"/>
                    </div>
                    <v-row no-gutters>
                        <div :style="'width:50%'">
                            <v-btn block color="secondary" class="mr-2"
                                @click="wallet_withdraw()"
                            >
                                Withdraw
                            </v-btn>
                        </div>
                        <div :style="'width:50%'">
                            <v-btn block class="ml-2"
                                @click="wallet_deposit()"
                            >
                                Deposit
                            </v-btn>
                        </div>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col cols="10" md="6" lg="5">
                <v-card class="pa-5 mt-12"> Transaction history </v-card>
                <v-card class="pa-5 mt-12" v-for="transaction in walletHistoryData">
                    {{ transaction.type }}
                    {{ transaction.amount }}
                    {{ transaction.status }}
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script lang="ts">
import { storeToRefs } from 'pinia';
import { defineComponent, onMounted, ref } from 'vue';
import { useWalletStore } from '@/stores/wallet';
import { createToast } from 'mosha-vue-toastify';
import { useUserStore } from '@/stores/user';

export default defineComponent({
    name: 'Wallet',
    setup() {
        const wallet_amount = ref(0)
        const wallet_input_amount = ref("0");
        const walletStore = useWalletStore();
        const { walletHistory, deposit, withdraw, wallet } = walletStore;
        const { walletHistoryData } = storeToRefs(walletStore);

        const wallet_deposit = async () => {
            try {
                let url = await deposit(wallet_input_amount.value);
                window.location.href = url
                createToast('Deposit success', { position: 'bottom-right', type: 'success' })
            } catch(e) {
                createToast('Error during deposit', { position: 'bottom-right', type: 'danger' })
            }
        }

        const wallet_withdraw = async () => {
            try {
                let message = await withdraw(wallet_input_amount.value);
                createToast(message, { position: 'bottom-right', type: 'success' })
            } catch(e) {
                createToast('Error during withdraw ', { position: 'bottom-right', type: 'success' })
            }
        }

        onMounted(async () => {
            try {
                await walletHistory();
            } catch (e) {

            }
        });


        return {wallet, walletHistoryData, wallet_amount, wallet_input_amount, wallet_deposit, wallet_withdraw}
    },
})
</script>

<style scoped>
.custom-wallet-amount {
    font-size: 42px;
    font-style: italic;
    font-weight: bold;
}
</style>
