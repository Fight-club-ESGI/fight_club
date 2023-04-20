<template>
    <v-card class="py-2 px-4">
        <v-row no-gutters justify="space-between">
            <v-col cols="auto">
                <div class="my-1 font-weight-bold">
                    {{ event.fight.fighterA.firstname }} {{ event.fight.fighterA.lastname }} vs {{ event.fight.fighterB.firstname }}
                    {{ event.fight.fighterB.lastname }}
                </div>
            </v-col>
            <v-col cols="12">
                <p class="text-right text-grey-darken-1 mb-6">
                    {{ DateTime.fromISO(event.endTimestamp, { locale: 'en' }).toFormat('ff') }}
                </p>
                <div class="text-center">
                    <v-btn class="mx-2" color="secondary" :min-height="50" :min-width="140" rounded="false">
                        <div>
                            <p>{{ event.fight.fighterA.firstname }} {{ event.fight.fighterA.lastname }}</p>
                            <p>{{ formatMoneyOrRating(event.fight.ratingFighterA) }}</p>
                        </div>
                    </v-btn>
                    <v-btn class="mx-2" color="secondary" :min-height="50" :min-width="140" rounded="false">
                        <div>
                            <p>Null</p>
                            <p>{{ formatMoneyOrRating(event.fight.ratingNullMatch) }}</p>
                        </div>
                    </v-btn>
                    <v-btn
                        @click="
                            setCurrentBet([
                                {
                                    event: event,
                                    expectedWinner: event.fight.fighterB.firstname + ' ' + event.fight.fighterB.lastname,
                                    amount: 100,
                                },
                            ])
                        "
                        class="mx-2"
                        color="secondary"
                        :min-height="50"
                        :min-width="140"
                        rounded="false"
                    >
                        <div>
                            <p>{{ event.fight.fighterB.firstname }} {{ event.fight.fighterB.lastname }}</p>
                            <p>{{ formatMoneyOrRating(event.fight.ratingFighterB) }}</p>
                        </div>
                    </v-btn>
                </div>
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon';
import { formatMoneyOrRating } from '@/service/helpers';
import { useBetStore } from '../../stores/bet';
import { storeToRefs } from 'pinia';

defineProps({
    event: {
        type: Object,
        default(rawProps) {
            return { message: 'hello' };
        },
    },
});
const betStore = useBetStore();
const { bet } = storeToRefs(betStore);
const { setCurrentBet } = betStore;
</script>

<style scoped>
.v-btn {
    text-transform: none;
}
</style>
