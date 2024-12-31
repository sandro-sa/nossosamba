<template>
    <div v-if="isLoading">
        <loading-full-component></loading-full-component>
    </div>
    <div v-if="msg">
        <alert-card :message="msg" :typeAlert="alert"></alert-card>
    </div>
 
</template>
<script setup>

const props = defineProps(['isLoading','msg', 'alert'])

</script>