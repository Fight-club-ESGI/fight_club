<template>
    <v-container>
      <EventDetails></EventDetails>
    </v-container>
  </template>
  <script lang="ts">
  import { defineComponent } from 'vue';
  import EventDetails from "../components/EventDetails.vue";
  export default defineComponent({
    components: { EventDetails }
  });
  </script>