<template>
    <div class="flex flex-column gap-4">
        <div v-for="event of events" :key="event.id" @click="router.push({ name: 'event-details', params: { id: event.id } })" class="cursor-pointer">
            <v-card class="grid grid-cols-5">
                <div class="col-span-1 pa-5 text-center text-lg m-auto">
                    {{
                        new Date(event.endTimestamp).toLocaleString('en-GB', {
                            year: 'numeric',
                            month: 'long',
                            day: '2-digit',
                        })
                    }}
                </div>
                <div class="col-span-1">
                    <v-img :src="event.locationLink ? event.locationLink : 'https://placeholder.com/150?text=UpdateImageLink'" height="150" />
                </div>
                <div class="col-span-3 pa-5 flex flex-column relative">
                    <div v-if="admin" class="absolute right-[20px]">
                        <update-event />
                    </div>
                    <p class="text-lg">{{ event.name }}</p>
                    <p>
                        <span class="italic">{{ event.description }}</span> @{{ event.location }}
                    </p>
                    <div class="mt-auto flex">
                        <div class="flex align-center gap-2">
                            <v-icon icon="mdi-account-multiple"></v-icon>
                            <p class="text-primary text-lg font-bold">{{ event.capacity }}</p>
                        </div>
                        <div v-if="event.vip" class="ml-auto">
                            <span class="text-xl text-secondary">VIP event</span>
                        </div>
                    </div>
                </div>
            </v-card>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, PropType, toRefs } from 'vue';
import { useRouter } from 'vue-router';
import { EventI } from '@/interfaces/payload';
import UpdateEvent from '@/components/dialogs/UpdateEvent.vue';

export default defineComponent({
    components: { UpdateEvent },
    props: {
        events: {
            type: Array as PropType<EventI[]>,
            default: () => [],
        },
        admin: {
            type: Boolean as PropType<boolean>,
            default: false,
        },
    },
    setup(props) {
        const router = useRouter();
        const { events, admin } = toRefs(props);

        return { router, events, admin };
    },
});
</script>
