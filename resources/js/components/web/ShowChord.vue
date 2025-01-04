<template>
	
    <template v-if="chords">
        <div class="container" style="background-color: white;">
            <h2 class="text-center"><i>lista de cifras</i></h2>
            <chords-list-wrap :token_crsf="token_crsf" :chords="chords" :delete="false" ></chords-list-wrap>
        </div>
        
    </template>
    <template>
        <h2 class="text-center">Não há cifras cadastradas.</h2>
    </template>

</template>
  
<script setup>
const props = defineProps(['token_crsf', 'chords']);

</script>
<style scoped>

.container{
	background-color: white;
	padding: 20px;
}


.chord-content {
	padding: 10px;
    font-family: monospace;
}

.line {
    display: flex;
}
.title{
    color: #0a0a0a;
    font-size: 0.9em;
    font-weight: 800;
}
.box{
    border: 2px solid rgb(110, 107, 107);
    padding: 10px 13px 10px 0px;
    
}
.field {
	width: 1.3em;
    height: 2em;
    font-weight: normal;
    font-family: sans-serif;
    font-size: .8em;
    border-top: #f80303 1px solid; /* Mantém a borda superior da célula */
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    position: relative; /* Necessário para o posicionamento da line */
    overflow: hidden; 
  
}
.field::before {
	content: "";
    position: absolute;
    top: 0; /* Começa a line no topo da célula */
    bottom: 0; /* Faz a line cobrir a célula sem ultrapassar */
    left: 55%; /* Centraliza a line na horizontal */
    width: 1px; /* Largura da line */
    background-color: #289c0b; /* Cor da line */
    transform: translateX(-50%);
}
.field2 {
	width: 1em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    margin: 0px;
    padding: 0px;
   
}
@media only screen and (max-width: 600px) {
    .all-chords{
		display: none;
	}
}

</style>