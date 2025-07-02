<script setup lang="ts">
import { imagePaths } from '@/constants/imagesPath';
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img :src="imagePaths.logo.src" :alt="imagePaths.logo.alt" />
</template>
