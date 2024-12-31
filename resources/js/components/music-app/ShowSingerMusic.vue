<template>
	
	<div class="container-music">
		<h2 style="font-size: 1em; font-weight: bold;">{{ music['music_name'] }}</h2>
		<span style="font-size: 0.8em; font-weight: bold;">Tom: {{ music['tone']['tone']}}</span>
		<br>
		<p class="m-0 p-0" style="font-size: 0.8em; font-weight: bold;">Intro: {{ music['introduction'] }}</p>
		<br>
		<div class="music m-0 p-0" v-html="music['music']"></div>
	</div>		
    
</template>
  
<script setup>
const props = defineProps(['token_crsf', 'music']);
</script>
<style scoped>

.container-music{
	background-color: white;
	padding: 20px;
}
</style>