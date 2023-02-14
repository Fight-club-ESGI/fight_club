<template>
  <v-navigation-drawer
    :rail="display"
    permanent
  >
    <v-list-item
      :prepend-avatar="`https://xsgames.co/randomusers/assets/avatars/pixel/43.jpg`"
      :title="user.username"
      :subtitle="user.email"
      nav
      class="py-2"
    />

    <v-divider></v-divider>

    <v-list density="compact" nav>
      <v-list-item :to="{ name: 'event-admin' }" exact prepend-icon="mdi-calendar" title="Events" value="events" ></v-list-item>
      <v-list-item :to="{ name: 'fighter-admin' }" exact prepend-icon="mdi-karate" title="Fighters" value="fighters"></v-list-item>
      <v-list-item :to="{ name: 'sponsorship-admin' }" exact prepend-icon="mdi-handshake" title="Sponsorships" value="users"></v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script lang="ts">
import { storeToRefs } from 'pinia';
import { defineComponent, ref } from 'vue';
import { useUserStore } from '../stores/user';
export default defineComponent({
  props: {
    display: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    // const display = props
    const userStore = useUserStore();
    const { user } = storeToRefs(userStore);

    return { user }
  }
});
</script>