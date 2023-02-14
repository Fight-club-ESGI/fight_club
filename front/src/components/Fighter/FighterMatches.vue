<template>
    <div>
        <v-card class="pa-5">
            <p class="text-2xl pb-5">Matches</p>
            <table>
                <th v-for="tableHeader in tableHeaders" class="w-100 text-left pl-2 pb-2">
                    {{ tableHeader }}
                </th>
                <tbody>
                    <tr v-for="(fight, i) of fights" :class="[ i % 2 !== 1 ? 'bg-white' : ''] + ' h-10'">
                        <td class="pl-2">{{ fight.opponent.firstname }}</td>
                        <td class="pl-2">{{ fight.reason }}</td>
                        <td class="pl-2">{{ fight.round }}</td>
                        <td :class="[ fight.status === 'win' ? 'text-green' : 'text-red'] + ' pl-2'">{{ fight.status.toUpperCase() }}</td>
                    </tr>
                </tbody>
            </table>
        </v-card>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import fights from "../../mocks/fights.json";

export default defineComponent({
    setup() {
        const tableHeaders = ['Opponents', 'Reason', 'Round', 'Status'];

        return { fights, tableHeaders }
    }
})
</script>