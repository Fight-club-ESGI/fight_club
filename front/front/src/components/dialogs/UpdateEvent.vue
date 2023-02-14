<template>
    <div>
        <v-dialog
            v-model="dialog"
        >
            <template v-slot:activator="{ props }">
            <v-btn
                color="secondary"
                v-bind="props"
                icon="mdi-dots-horizontal"
                size="small"
            />
            </template>
    
            <v-card>
                <v-card-actions>
                    <v-row justify="end" class="px-4">
                        <v-btn color="primary" @click="dialog = false">Cancel</v-btn>
                        <v-btn color="secondary" @click="saveEvent()">Save</v-btn>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref, computed, reactive } from 'vue';
import { createToast } from 'mosha-vue-toastify' 

export default defineComponent({
    setup() {
        const dialog = ref<boolean>(false);
        const valid = ref<boolean>(false);

        const saveEvent = () => {

        }

        return { dialog, valid, saveEvent }
    }
});
</script>