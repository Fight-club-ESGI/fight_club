<template>
    <div class="flex flex-column gap-4">
        <div v-for="event of events" :key="event.id"
            @click="router.push({ name: eventNavigation, params: { id: event.id } })" class="cursor-pointer">
            <v-card class="grid grid-cols-5">
                <div class="col-span-1 pa-5 text-center text-lg m-auto">
                    {{
                        new Date(event.timeEnd).toLocaleString('en-GB', {
                            year: 'numeric',
                            month: 'long',
                            day: '2-digit',
                        })
                    }}
                </div>
                <div class="col-span-1">
                    <v-img
                        :src="event.locationLink ? event.locationLink : 'https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80'"
                        height="150" />
                </div>
                <div class="col-span-3 pa-5 flex flex-column relative">
                    <v-menu v-if="admin && pathIncludeAdmin">
                        <template v-slot:activator="{ props }">
                            <v-btn class="absolute right-[20px]" color="primary" v-bind="props" icon="mdi-dots-horizontal"
                                size="x-small" />
                        </template>
                        <v-list>
                            <v-list-item value="update-event"> <update-event :event="event" /></v-list-item>
                            <v-list-item value="delete-fighter" @click="deleteE(event.id)">Delete</v-list-item>
                        </v-list>
                    </v-menu>
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
import { defineComponent, PropType, toRefs, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { EventI } from '@/interfaces/payload';
import UpdateEvent from '@/components/dialogs/UpdateEvent.vue';
import { useEventStore } from '@/stores/event';

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
        const route = useRoute();
        const { events, admin } = toRefs(props);
        const eventStore = useEventStore();
        const { deleteEvent } = eventStore;
        const pathIncludeAdmin = computed(() => {
            return route.path.includes('admin');
        })
        const eventNavigation = computed(() => {
            return route.path.includes('admin') ? 'event-details-admin' : 'event-details';
        });

        const deleteE = async (eventId: string) => {
            try {
                await deleteEvent(eventId);
            } catch {
                console.error(error)
            }
        }
        return { router, events, admin, eventNavigation, pathIncludeAdmin, deleteE };
    },
});
</script>
