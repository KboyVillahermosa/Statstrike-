<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-lg border px-4 py-2.5 w-full transition-all duration-200 focus:outline-none focus:ring-2 disabled:opacity-50"
        :class="$attrs.class"
        v-model="model"
        ref="input"
    />
</template>
