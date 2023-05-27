<template>
    <v-card class="py-2 px-4">
        <div class="flex justify-between items-start pb-5">
            <h2 class="text-4xl font-bold">{{ ticket.event.name }}
                <v-chip :color="ticketCategoryColor(ticket.category)">{{ ticket.category }}</v-chip>
            </h2>
        </div>

        <div class="flex items-center justify-between mb-5">
            <div class="flex gap-x-3">
                <span class="flex gap-2 items-center bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                    <Icon height="20" icon="material-symbols:location-on" />
                    {{ ticket.event.location }}
                </span>
                <span class="flex gap-2 items-center bg-neutral-200 text-neutral-700 p-2 rounded font-bold">
                    <Icon height="20" icon="mdi:user" />
                    {{ ticket.order.customer }}
                </span>
            </div>
        </div>
        <div class="flex">
            <div>
                <div class="flex flex-col max-h-96 overflow-auto">
                    <ol class="relative border-l border-gray-200 dark:border-gray-700 ml-6">
                        <li class="mb-4 ml-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 bg-gray-600 rounded-full -left-3 ring-8 ring-[#f2f2f2]">
                                <Icon height="16" color="white" icon="material-symbols:calendar-month-rounded" />
                            </span>
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                                {{ new Date(ticket.event.timeStart).toLocaleString('en-GB', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                                }}
                            </time>
                            <span class="flex gap-2 items-center text-neutral-700 p-2 rounded font-bold">
                                Event starts
                            </span>
                        </li>
                        <li v-for="fight in orderedFights" :key="fight.id" class="mb-4 ml-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 bg-gray-600 rounded-full -left-3 ring-8 ring-[#f2f2f2]">
                                <Icon height="16" color="white" icon="material-symbols:sports-mma" />
                            </span>
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                                {{ new Date(fight.fightDate).toLocaleString('en-GB', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })
                                }}
                            </time>
                            <span class="flex gap-2 items-center text-neutral-700 p-2 rounded font-bold">

                                {{ fight.fighterA.firstname }} {{ fight.fighterA.lastname }} vs
                                {{ fight.fighterB.firstname }} {{ fight.fighterB.lastname }}
                            </span>
                        </li>
                        <li class="mb-4 ml-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 bg-gray-600 rounded-full -left-3 ring-8 ring-[#f2f2f2]">
                                <Icon height="16" color="white" icon="material-symbols:calendar-month-rounded" />
                            </span>
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                                {{ new Date(ticket.event.timeEnd).toLocaleString('en-GB', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                                }}
                            </time>
                            <span class="flex gap-2 items-center text-neutral-700 p-2 rounded font-bold">
                                Event ends
                            </span>
                        </li>
                    </ol>
                </div>
                <v-divider class="my-2 border-black"></v-divider>

                <p class="text-grey text-sm py-2">REF : {{ ticket.reference }}</p>
            </div>
            <div class="flex flex-col justify-between ps-10">
                <qrcode-vue :value="'/tickets/' + ticket.id" :size="100" level="H" />
                <div class="my-1 text-right font-weight-bold text-2xl self-end">
                    {{ getPriceUnit }}<span class="text-grey-darken-1 text-lg">,{{ getPriceDecimal }}</span>
                    €
                </div>
            </div>

        </div>

    </v-card>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon';
import { formatNumber } from '@/service/helpers';
import { IMyTicket } from '@/interfaces/ticket';
import { Icon } from "@iconify/vue";
import { PropType, onMounted } from 'vue';
import { computed } from 'vue';
import QrcodeVue from 'qrcode.vue';

const ticketCategoryColor = (name: string) => {
    const colors: { [key: string]: string } = {
        "GOLD": "amber-darken-3",
        "SILVER": "blue-grey-lighten-1",
        "VIP": "light-blue-darken-2",
        "V_VIP": "light-blue-darken-4",
        "PEUPLE": "grey-darken-1"
    }

    return colors[name];
}

const props = defineProps({
    ticket: {
        type: Object as PropType<IMyTicket>,
        required: true
    },
});

const getPriceUnit = computed(() => {
    return formatNumber(props.ticket.price).split(',')[0];
});

const getPriceDecimal = computed(() => {
    return formatNumber(props.ticket.price).split(',')[1];
});

const orderedFights = computed(() => {
    return props.ticket.event.fights.sort((a, b) => {
        return DateTime.fromISO(a.fightDate).toMillis() - DateTime.fromISO(b.fightDate).toMillis();
    });
});

onMounted(() => {
    console.log(props.ticket);
});

</script>

<style scoped>
.v-btn {
    text-transform: none;
}

.custom-rating {
    color: white;
    background: rgb(var(--v-theme-primary));
    border-radius: 5px;
}

.custom-result {
    background: rgb(var(--v-theme-lightgray));
    border-radius: 5px;
}
</style>
