<template>
	

	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<h2 style="font-size: 1em; font-weight: bold;">{{music.singer.singer_name}}</h2>
				<h2 style="font-size: 1em; font-weight: bold;">{{ music.music_name }}</h2>
				<span style="font-size: 0.8em; font-weight: bold;">Tom: {{ music.tone.tone}}</span>
				<br>
				<p class="m-0 p-0" style="font-size: 0.8em; font-weight: bold;">Intro: {{ music.introduction }}</p>
				<br>
				<div @mouseover="mostrarProximoElemento">
					<div class="music m-0 p-0" v-html="music.music"></div>
				</div>


				<template v-if="chords"  v-for=" chord in chords" :key="chord.id">
                   
                   <div class="nota " :id="chord.chord_name" style="display: none;">
					   <h5 class="text-center title m-0 p-0 mt-2 mb-1 ">{{ chord.chord_name}}</h5>
					   <div class="chord-content m-0 p-0 pb-2 ps-2 d-flex">
                           <template v-for="(position , index) in  convertForArray(chord['chord_positions'])" :key="index">
                           
                               <div class="box m-0 me-2">
                               <div class="line l1">
                                   <span class="field2" v-for="(filed, index) in position[0]" :key="'l1-' + index">
                                       <span v-if="index == 'line_1_position_1'" class="field2"><span class="field-text">{{ filed }}</span></span><span v-else class="field"><span class="field-text">{{ filed }}</span></span>
                                   </span>
                               </div>
                               <div class="line l2">
                                   <span class="field2"  v-for="(filed, index) in position[1]"  :key="'l2-' + index">
                                       <span v-if="index == 'line_2_position_1'"class="field2"><span class="field-text">{{ filed }}</span></span><span v-else class="field"><span class="field-text">{{ filed }}</span></span>
                                   </span>
                               </div>
                               <div class="line l3">
                                   <span class="field2" v-for="(filed, index) in position[2]"  :key="'l3-' + index">
                                       <span v-if="index == 'line_3_position_1'" class="field2"><span class="field-text">{{ filed }}</span></span><span v-else class="field"><span class="field-text">{{ filed }}</span></span>
                                   </span>
                               </div>
                               <div class="line l4">
                                   <span class="field2"  v-for="(filed, index) in position[3]" :key="'l4-' + index">
                                       <span v-if="index == 'line_4_position_1'" class="field2"><span class="field-text">{{ filed }}</span></span><span v-else class="field"><span class="field-text">{{ filed }}</span></span>
                                   </span>
                               </div>
                               <div class="line l5">
                                   <span class="field2"  v-for="(filed, index) in position[4]"  :key="'l5-' + index">
                                       <span v-if="index == 'line_5_position_1'" class="field2"><span class="field-text">{{ filed }}</span></span><span v-else class="field"><span class="field-text">{{ filed }}</span></span>
                                   </span>
                               </div>
                           </div>
                           
                       </template>
                   </div>
                   </div>

                   <div id="tooltip" style="position: absolute; display: none;"></div>
               </template>

			</div>


			<div class="col-md-5 all-chords">
				<template v-if="chords">
        			<chords-list-wrap :token_crsf="token_crsf" :chords="chords" :delete="false" ></chords-list-wrap>
    			</template>
			</div>
		</div>
	</div>		
    
</template>
  
<script setup>
const props = defineProps(['token_crsf', 'music', 'chords']);

const convertForArray = ((json) => {
    let parsedArray = JSON.parse(json);
    return parsedArray
});


const mostrarProximoElemento = (event) => {
  if (event.target && event.target.classList.contains('acorde')) {
    const chord = event.target.textContent;
    enableDynamicTooltip(event.target, chord);  // Passa o target e o conteúdo para a função
  }
};

const enableDynamicTooltip = (element, chord) => {
  const tooltip = document.getElementById("tooltip");
  const contentElement = document.getElementById(chord); // Procuramos a div com esse id

  if (!contentElement) return; // Se não encontrar a div com o id correspondente, ignora
  const content = contentElement.innerHTML;

  const showTooltip = (e) => {
    tooltip.innerHTML = content;
    tooltip.style.display = "block";
    tooltip.style.left = `${e.pageX + 15}px`;
    tooltip.style.top = `${e.pageY + 15}px`;
  };

  const hideTooltip = () => {
    tooltip.style.display = "none";
  };

  // A variável `element` é agora passada corretamente para manipulação de eventos
  element.addEventListener("mousemove", showTooltip);
  element.addEventListener("mouseleave", hideTooltip);
  element.addEventListener("click", (e) => {
    showTooltip(e);
  });
};
</script>
<style scoped>

.container{
	background-color: white;
	padding: 20px;
}

#tooltip {
	background-color: #fff;
	border-radius: 5px;
	font-size: 12px;
	box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
	pointer-events: none;
	z-index: 9000;
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