<template>
    <v-card @click="router.push({ name: eventNavigation, params: { id: event.id } })"
        class="cursor-pointer w-92 h-92 relative bg-neutral-800 text-white">
        <div class="absolute bg-white z-10 m-5 rounded-lg h-16 w-16 flex items-center">
            <div v-if="timeStart > new Date()" class="flex flex-col text-center mx-auto font-bold">
                <p>
                    {{ timeStart?.toLocaleDateString('en-GB', { day: '2-digit' }) }}
                </p>
                <p>
                    {{ timeStart?.toLocaleDateString('en-GB', { month: 'long' }) }}
                </p>
            </div>
            <div v-else class="flex flex-col text-center mx-auto font-bold">
                Passed
            </div>
        </div>
        <div v-if="event.vip" class="absolute z-10 rounded-lg h-16 w-16 flex items-center right-0">
            <div class="flex flex-col text-center mx-auto font-bold">
                <Icon height="30" class="text-yellow-300" icon="game-icons:cut-diamond" />
            </div>
        </div>
        <div style="background-image: url('https://images.unsplash.com/photo-1561912847-95100ed8646c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80')"
            class="h-1/2 bg-cover bg-center">
            <div class="h-full w-full bg-gradient-to-t from-neutral-800 to-transparent" />
        </div>
        <div class="pa-5 h-1/2 flex flex-column relative overflow-auto">
            <v-menu v-if="admin && pathIncludeAdmin">
                <template v-slot:activator="{ props }">
                    <v-btn class="absolute right-[20px]" color="transparent" v-bind="props" icon="mdi-dots-horizontal"
                        size="x-medium" />
                </template>
                <v-list>
                    <v-list-item value="update-event"> <update-event :event="event" /></v-list-item>
                    <v-list-item value="delete-fighter" @click="deleteE(event.id)">Delete</v-list-item>
                </v-list>
            </v-menu>
            <p class="text-2xl font-bold pb-5">{{ event.name }}</p>
            <p class="truncate overflow-hidden">
                <span class="italic">{{ event.description }}</span>
            </p>
            <p class="text-sm font-bold">{{ event.location }}</p>
            <div class="mt-auto flex">
                <div class="flex align-center gap-2 bg-neutral-600 p-2 rounded-lg">
                    <v-icon icon="mdi-account-multiple"></v-icon>
                    <p class="text-sm font-bold">{{ event.capacity }}</p>
                </div>
            </div>
        </div>
    </v-card>
</template>

<script lang="ts" setup>
import { useRoute, useRouter } from "vue-router";
import { PropType, ref, watch } from "vue";
import { useEventStore } from "@/stores/event";
import { Icon } from "@iconify/vue";
import UpdateEvent from '@/components/dialogs/UpdateEvent.vue';
import { IEvent } from "@/interfaces/event"

const props = defineProps({
    event: { type: Object as PropType<IEvent>, required: true },
    admin: { type: Boolean, default: false },
})

const router = useRouter()
const route = useRoute()

const eventStore = useEventStore();
const { deleteEvent } = eventStore;

const eventNavigation = ref(route.path.includes('admin') ? 'event-details-admin' : 'event-details')
const pathIncludeAdmin = ref(route.path.includes('admin'))

const timeStart = ref(new Date(props.event.timeStart))

watch(props.event, async (newValue, oldValue) => {
    timeStart.value = new Date(props.event.timeStart)
})

const deleteE = async (eventId: string) => {
    try {
        await deleteEvent(eventId);
    } catch (error) {
        console.error(error)
    }
}
</script>
