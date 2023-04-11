<script lang="ts">
import { defineComponent } from 'vue';
import { useUserStore } from '@/stores/user';
import { storeToRefs } from 'pinia';
import AdminView from '@/views/admin/AdminView.vue';
import imageUrl from '@/assets/boxing.jpg';

export default defineComponent({
    components: { AdminView },
    setup() {
        const userStore = useUserStore();
        const { isAdmin } = storeToRefs(userStore);

        return { isAdmin, imageUrl };
    },
});
</script>

<template>
    <v-container fluid class="pa-0">
        <v-card :rounded="false">
            <!-- <v-img max-height="100vh" class="align-center text-center" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)" cover :src="imageUrl">
                <h1 class="custom-h1">Thunderous Knockout Fighting</h1>
                <h2 class="custom-h2">Watch, bet or book your tickets online.</h2>
                <v-card-action>
                    <router-link to="/events"><v-btn class="text-white mx-3">Upcoming events</v-btn></router-link>
                    <router-link to="/fighters"><v-btn class="text-white mx-3">Our fighters</v-btn></router-link>
                </v-card-action>
            </v-img> -->
        </v-card>
        <!--<admin-view v-if="isAdmin"></admin-view>-->
    </v-container>
</template>

<style scoped>
.custom-h1 {
    font-size: 42px;
    text-transform: uppercase;
    color: white;
    margin-bottom: 24px;
}
.custom-h2 {
    font-size: 24px;
    color: white;
    margin-bottom: 24px;
}
</style>
