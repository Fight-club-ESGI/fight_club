<template>
	<v-container>
		<p class="text-center font-weight bold">Your tickets</p>
		<v-tabs v-model="tab" color="primary" align-tabs="center">
			<v-tab value="upcoming"> Upcoming </v-tab>
			<v-tab value="passed"> Passed </v-tab>
		</v-tabs>
		<v-window v-model="tab">
			<v-window-item value="upcoming" class="pt-10"> </v-window-item>
			<v-window-item value="passed" class="pt-10"> </v-window-item>
		</v-window>
	</v-container>
</template>

<script lang="ts" setup>
import { ref, reactive, computed } from 'vue';

const tickets = reactive([
	{
		id: 'ticket 1',
		status: 'upcoming',
	},
	{
		id: 'bet2',
		status: 'passed',
	},
]);
const tab = ref();

const upcomingEvents = computed(() => {
	return tickets.filter((e) => e.status === 'upcoming');
});
</script>