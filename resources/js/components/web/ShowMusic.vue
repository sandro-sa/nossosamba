<template>
	<div class="container">


		<div class="row">
			<div class="col-md-5">
                <span class="info">
                    <h2 style="font-size: 1em; font-weight: bold;">{{music.singer.singer_name}}</h2>
                    <h2 style="font-size: 1em; font-weight: bold;">{{ music.music_name }}</h2>
                    <span style="font-size: 0.8em; font-weight: bold;">De: {{ music.composers}}</span>
                    <br>
                </span>

                <div class="dropdown">
                    <button class="btn  btn-sm dropdown-toggle m-0 btn-tom" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Tom: <span v-if="mudou_tom">{{ mudou_tom}}</span><span v-else>{{ music.tone.tone}}</span>
                    </button>
                    <ul class="dropdown-menu " style="max-width: 200px;">
                        <div class="row">
                            <li v-for="(nota, index) in convertForArray(tons)" :key="index" class="col-4" >
                                <a class="dropdown-item" :href="url+'letra/'+ music.id+'/'+nota.posicao">{{ nota.tom }}</a>
                            </li>
                        </div>
                    </ul>
                </div>

				
				<p class="m-0 p-0" style="font-size: 0.8em; font-weight: bold;">Intro: {{ introducao()}}</p> 

				<div @mouseover="mostrarProximoElemento">
					<div class="music m-0 p-0" v-html="letra()"></div>
				</div>

				<template v-if="chords"  v-for=" chord in chords" :key="chord.id">
                   
                   <div class="nota " :id="chord.chord_name" style="display: none;">
					   <h5 class="text-center title m-0 p-0 mt-2 mb-1 ">{{ chord.chord_name}}</h5>
					   <div class="chord-content m-0 p-0 pb-2 ps-2 ">
                           <template v-for="(position , index) in  convertForArray(chord['chord_positions'])" :key="index">
                           
                            <div class="box ">

                                <div >
                                    <template  v-for="(filed, index) in position[1]" :key="'l1-' + index">
                                        <span v-if="index == 'coluna_1_corda_0'" class="field-1 p-0 m-0">{{ filed }}</span><span  v-else class="field-2 p-0 m-0" ><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                                    </template>
                                    <span  class="field-11 p-0 m-0"></span>
                                </div>

                                <div >
                                    <template   v-for="(filed, index) in position[2]"  :key="'l2-' + index">
                                        <span v-if="index == 'coluna_2_corda_0'"class="field-1 p-0 m-0">{{ filed }}</span><span v-else class="field-4 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                                    </template>
                                    <span  class="field-11 p-0 m-0"></span>
                                </div>

                                <div >
                                    <template  v-for="(filed, index) in position[3]"  :key="'l3-' + index">
                                        <span v-if="index == 'coluna_3_corda_0'" class="field-1 p-0 m-0">{{ filed }}</span><span v-else class="field-6 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                                    </template>
                                    <span  class="field-11 p-0 m-0"></span>
                                </div>

                                <div >
                                    <template  v-for="(filed, index) in position[4]" :key="'l4-' + index">
                                        <span v-if="index == 'coluna_4_corda_0'" class="field-1 p-0 m-0">{{ filed }}</span><span v-else class="field-8 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                                    </template>
                                    <span  class="field-11 p-0 m-0"></span>
                                </div>

                                <div>
                                    <template  v-for="(filed, index) in position[5]"  :key="'l5-' + index">
                                        <span v-if="index == 'coluna_5_corda_0'" class="field-1 p-0 m-0">{{ filed}}</span><span v-else class="field-10 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 1000; background-color: white;"></i></span>
                                    </template>
                                    <span  class="field-11 p-0 m-0"></span>
                                </div>
                                        
                           </div>
                           
                       </template>
                   </div>
                   </div>

                   <div id="tooltip" style="position: absolute; display: none;"></div>
               </template>

			</div>

			<div class="col-md-7 all-chords">
				<template v-if="chords">
        			<chords-list-wrap :token_crsf="token_crsf" :chords="chords" :delete="false" ></chords-list-wrap>
    			</template>
			</div>
		</div>
	</div>		
    
</template>
  
<script setup>

import urls from '@/utils/urls';

const props = defineProps(["token_crsf","music","chords","tons", "lista_de_novos_acordes", "mudou_tom"]);

const url = urls.url;


const letra = (() => {

    if (props.mudou_tom) {
    
        const alterarAcordes = (textoHtml, listaDeNovosAcordes) => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(textoHtml, 'text/html');
            const acordes = doc.querySelectorAll('span.acorde');

            acordes.forEach((acordeSpan) => {
                const acordeOriginal = acordeSpan.textContent.trim();
                if (listaDeNovosAcordes[acordeOriginal]) {
                    acordeSpan.textContent = listaDeNovosAcordes[acordeOriginal];
                }
            });
            return doc.body.innerHTML;
        };
        const textoHtmlAlterado = alterarAcordes(props.music.music, props.lista_de_novos_acordes);
        return textoHtmlAlterado;
    } else {
        return props.music.music;
    }
});


const introducao = (() => {
    if (props.mudou_tom) {
  
        const alterarAcordes = (texto, listaDeNovosAcordes) => {
            const regex = /\b([A-Ga-g#]+[0-9]?[a-zA-Z]*)\b/g;
            return texto.replace(regex, (match) => {
                return listaDeNovosAcordes[match] || match;
            });
        };
        const novaIntroducao = alterarAcordes(props.music.introduction, props.lista_de_novos_acordes);

        return novaIntroducao;
    } else {
        return props.music.introduction;
    }
});


const convertForArray = ((json) => {
    let parsedArray = JSON.parse(json);
    return parsedArray
});

const mostrarProximoElemento = (event) => {
  if (event.target && event.target.classList.contains('acorde')) {
    const chord = event.target.textContent;
    enableDynamicTooltip(event.target, chord);  
  }
};

const enableDynamicTooltip = (element, chord) => {
  const tooltip = document.getElementById("tooltip");
  const contentElement = document.getElementById(chord); 

  if (!contentElement) return; 
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

.btn-tom{
  margin: 0px;
  padding: 0px;
}

.btn-tom:hover{
    border: 0px;
}
.btn-tom:focus{
    border: 0px;
}

.dropdown-menu{
    background-color: #89443d;
    padding-right: 15px;
}
.dropdown-menu li a{
    color: #fff;
}
.dropdown-menu li a:hover{
    background-color: #89443d;
    color: #b18989;
    border: 0px;
}
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

.field-2,.field-4,.field-6,.field-8,.field-10,.field-11 {
    width: 1em;
    height: 1.8em;
    font-weight: normal;
    font-family: sans-serif;
    font-size: 1em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    position: relative;
    background-color: transparent;
    transition: background-color 0.3s ease;
}

.field-2::before,.field-4::before,.field-6::before,.field-8::before,.field-10::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 0.3px; /* A linha para representar a corda */
    background-color: #070707; /* Cor da linha da corda */
    transform: translateX(-50%);
}

.field-1,.field-3,.field-5,.field-7,.field-9 {
    width: 1em;
    height: 1.8em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    background-color: #f7f7f7; /* Cor neutra para representar casas vazias */
    border-radius: 3px;
}
.field-2{
    border: none;
    border-radius: 0px;
    border-top:#b18989 2px solid ;
}

.field-4,.field-6,.field-8{
    border: none;
    border-radius: 0px;
    border-top:#b18989 1px solid ;
}
.field-10{
    border: none;
    border-radius: 0px;
    border-top:#b18989 1px solid ;
    border-bottom:#b18989 2px solid ;
}

.field-1 {
    border-radius: 0px;
    border-right:#b18989 2px solid ;
   
}
.field-11{
    margin: 0px;
    padding: 0px;
    border-left:#b18989 2px solid ;
}
.box{
    margin: 0px;
    padding: 0px;
    margin-right: 10px;
}
@media only screen and (max-width: 600px) {
    .all-chords{
		display: none;
	}
    .info{
        display: none;
    }
    .chord-content{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
       
    }
    .box{
        margin-bottom: 5px;
    }
}

</style>