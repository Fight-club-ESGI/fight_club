<template>
    <div>
        {{ event }}
    </div>
</template>
<script lang="ts">
import { defineComponent, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useEventStore } from '../stores/event';
export default defineComponent({
setup() {
    const route = useRoute();

    const eventStore = useEventStore();
    const { getEvent } = eventStore;
    const { event } = storeToRefs(eventStore);

    const eventId = computed(() => route.params.id);

    onMounted(async () => {
        try {
            await getEvent(eventId.value);
        } catch (error) {
            
        }
    });

    return { event }
}
});
</script>