<template>
  <div @click="goToFighterDetails()" class="border rounded-md cursor-pointer">
    <v-img
      :src="`https://picsum.photos/id/${Math.round(Math.random() * 300)}/200/150`"
      height="200"
      :contain="false"
      class="rounded-t-md"
    />
    <div class="pa-3">
      <h3>Name: {{ fighterName }}</h3>
      <div>Nationality: {{ fighter.nationality }}</div>
      <div>Weight: {{ fighter.weight }}kg</div>
      <div>Height: {{ fighter.height }}cm</div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, toRefs, PropType, computed  } from 'vue';
import CreateFighter from '@/components/dialogs/CreateFighter.vue';
import { FighterI } from '@/interfaces/payload';
import { useRouter } from 'vue-router';

export default defineComponent({
  components: { CreateFighter },
  props: {
    fighter: {
      type: Object as PropType<FighterI>,
      default: () => {}
    }
  },
  setup(props) {
    const { fighter } = toRefs(props);
    const router = useRouter();

    const fighterName = computed(() => {
      return `${fighter.value.firstname} ${fighter.value.lastname}`;
    });

    const goToFighterDetails = () => {
      router.push({ name: 'fighter-details', params: { id:  fighter.value.id }});
    }

    return { fighter, fighterName, goToFighterDetails }
  }
});
</script>
