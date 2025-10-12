<template>
  <v-dialog v-model="dialog" max-width="400">
    <v-card>
      <v-card-title class="text-h6 d-flex align-center">
        <i class='bx bx-trash text-error' style="font-size: 24px; margin-right: 12px;"></i>
        Delete Confirmation
      </v-card-title>

      <v-card-text class="py-4">
        Are you sure you want to delete this item? This action cannot be undone.
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
          Delete
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
    default: 'Delete Confirmation'
  },
  message: {
    type: String,
    default: 'Are you sure you want to delete this item? This action cannot be undone.'
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
