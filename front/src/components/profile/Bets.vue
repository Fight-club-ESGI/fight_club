<template>
	<v-container>
		<v-tabs v-model="tab" color="primary" align-tabs="center">
			<v-tab value="pending"> Pending </v-tab>
			<v-tab value="finished"> Finished </v-tab>
			<v-tab value="won"> Won </v-tab>
		</v-tabs>
		<v-window v-model="tab">
			<v-window-item value="pending" class="pt-10">
				<v-row style="height: 600px" align="center" justify="center">
          <v-col cols="12" lg="4">
						<BetCard v-for="bet in pendingBets" :bet="bet" />
					</v-col>
				</v-row>
				<v-row style="height: 600px" align="center">
					<v-col cols="12">
						<div class="text-center">
							<v-icon color="orange" size="40" class="my-2">mdi-clover</v-icon>
							<p class="my-2 font-weight-bold">Add your first bet!</p>
							<p class="my-2">
								You don't have any current bets. Try your luck and bet on a fight!
							</p>
							<v-btn class="my-2">See the fights</v-btn>
						</div>
					</v-col>
				</v-row>
			</v-window-item>
			<v-window-item value="finished" class="pt-10"> </v-window-item>
			<v-window-item value="won" class="pt-10"> </v-window-item>
		</v-window>
	</v-container>
</template>

<script lang="ts" setup>
import { ref, reactive, computed } from 'vue';
import BetCard from '@/components/BetCard.vue';
const bets = reactive([
	{
		id: 'bet1',
		figher1: 'Mike Tyson',
		figher2: 'Evander Holyfield',
		selectedWinner: 'Mike Tyson',
		betAmount: '10,00',
		rating: '1,31',
		possibleGain: '3,10',
		status: 'pending',
		date: "Aujourd'hui 21h00",
	},
	{
		id: 'bet2',
		status: 'finished',
	},
	{
		id: 'bet3',
		status: 'won',
	},
]);
const tab = ref();
const pendingBets = computed(() => {
	return bets.filter((e) => e.status === 'pending');
});
</script>