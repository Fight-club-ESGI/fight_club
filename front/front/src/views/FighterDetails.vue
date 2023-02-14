<template>
  <v-container>
    <fighter-details />
  </v-container>
</template>
  
<script lang="ts">
import { defineComponent, ref } from 'vue';
import FighterDetails from "@/components/Fighter/FighterDetails.vue";

export default defineComponent({
  components: { FighterDetails },
  setup() {
  }
});
  </script>