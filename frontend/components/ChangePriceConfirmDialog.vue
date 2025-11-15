<template>
  <v-dialog v-model="dialog" max-width="400">
    <v-card class="pt-0 px-5 pb-5 fill-height">
      <v-card-title class="text-h4 font-weight-bold mb-3">
        Change Price
      </v-card-title>

      <v-card-text class="py-4">
        The data will be lost when changing.
      </v-card-text>

      <v-card-actions class="px-4 pb-4">
        <v-spacer></v-spacer>
        <v-btn
            variant="text"
            @click="cancel"
            class="text-none"
        >
          Cancel
        </v-btn>
        <v-btn
            color="error"
            variant="flat"
            @click="confirm"
            class="text-none"
        >
          Confirm
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Change Price'
  },
  message: {
    type: String,
    default: ' The data will be lost when changing..'
  }
})

const emit = defineEmits(['update:modelValue', 'confirm', 'cancel'])

const dialog = ref(props.modelValue)

watch(() => props.modelValue, (val) => {
  dialog.value = val
})

watch(dialog, (val) => {
  emit('update:modelValue', val)
})

const confirm = () => {
  emit('confirm')
  dialog.value = false
}

const cancel = () => {
  emit('cancel')
  dialog.value = false
}
</script>

<style scoped>
i.bx {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
</style>
