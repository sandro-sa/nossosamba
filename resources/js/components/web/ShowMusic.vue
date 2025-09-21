<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
	<div class="container">
		<div class="row">
			<div class="col-xl-5 col-md-12">
                <span class="info">

                <div v-if="modalRepertorie && isLoggedIn" class="card mb-1" >
                    <h5 class="card-header">Meus Repertorios</h5>
                    <div class="card-body ">
                        <select v-if="repertoires.length != 0" class="form-select" aria-label="Default select example" v-model="repertoireId" >
                            <option selected>Escolha o repertorio</option>
                            <template v-for="repertorie in repertoires">
                                <option :value="repertorie.id">{{repertorie.name}}</option>
                            </template>
                        </select>
                        <p v-else>Não há repertorio cadastrado!</p>
                        
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <button type="button" class="btn btn-sm btn-dark" @click="modalNewRepertoire = !modalNewRepertoire,modalRepertorie = !modalRepertorie " >Cadastrar repertorio</button>

                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-secondary me-2" @click="modalRepertorie = !modalRepertorie, addRepertorie = !addRepertorie " >Voltar</button>
                                <button type="button" class="btn btn-sm btn-primary" @click="submitNewMusicRepertoire()">Adiconar</button>

                            </div>
                        </div>
                    </div>       
                </div> 


                 <div v-if="modalNewRepertoire && isLoggedIn" class="card mb-1" >
                    <h5 class="card-header">Criar repertorio</h5>
                    <div class="card-body ">
                       
                        <div class="col-md-12">
                            <input type="text" class="form-control" v-model="repertoireName" placeholder="Inserir nome do repertorio" >
                        </div>
                        
                        <div class="d-flex justify-content-between mt-2">
                
                        
                            <button type="button" class="btn btn-sm btn-secondary me-2" @click="modalNewRepertoire = !modalNewRepertoire,  addRepertorie = !addRepertorie " >Voltar</button>
                            <button type="button" class="btn btn-sm btn-primary" @click="submit()">Salvar</button>

                            
                        </div>
                    </div>       
                </div> 



                <div class="d-flex justify-content-end">
                    <button v-if="isLoggedIn && addRepertorie"  class="btn btn-sm btn-primary mb-1" type="button" @click="modalRepertorie = !modalRepertorie,addRepertorie = !addRepertorie  " >Adicionar ao repertorio</button>
                </div>


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
                                <a  class="dropdown-item" :href="url+'letra/'+ music.id+'/'+nota.posicao">{{ nota.tom }}</a>
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
                           
                            <div class="box">

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

			<div class="col-xl-7 all-chords">
				<template v-if="chords">
        			<chords-list-wrap :token_crsf="token_crsf" :chords="chords" :delete="false" ></chords-list-wrap>
    			</template>
			</div>
		</div>
	</div>		
    
</template>
  
<script setup>
import { ref, onMounted } from 'vue';
import { useRhythms } from '@/store/rhythms.js';
import urls from '@/utils/urls';
import{ catchDefault } from '@/utils/messagesCatch';
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const props = defineProps(["token_crsf","music","chords","tons", "lista_de_novos_acordes", "mudou_tom"]);
const url = urls.url;
const isLoggedIn = ref(window.Laravel?.isLoggedIn || false);
const api_repertoire = urls.api+'repertoires';
const api_song = urls.api+'songs';
const rhythmStore = useRhythms();
const repertoires = ref(false);
const modalNewRepertoire = ref(false);
const modalRepertorie = ref(false);
const addRepertorie = ref(true);
const repertoireName = ref(null);
const repertoireId = ref(null);

const config = {
   headers: {
       'Content-Type': 'multipart/form-data',
       'Accept': 'application/json',
   }
};

const messages = ((text, type ) => {
    msg.value = text;
    alert.value = type;
    setTimeout(() =>{
        resetMessages();
    }, 2000)
});
const resetMessages = (( ) => {
    msg.value = false;
    alert.value = false;
});


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

    const rect = element.getBoundingClientRect();
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollLeft = window.scrollX || document.documentElement.scrollLeft;

    tooltip.style.left = `${rect.left + scrollLeft}px`;
    tooltip.style.top = `${rect.bottom + scrollTop + 5}px`; // 5px abaixo do acorde
  };

  const hideTooltip = () => {
    tooltip.style.display = "none";
  };

  // Remover listeners duplicados primeiro
  element.removeEventListener("mousemove", showTooltip);
  element.removeEventListener("mouseleave", hideTooltip);

  element.addEventListener("mousemove", showTooltip);
  element.addEventListener("mouseleave", hideTooltip);
  element.addEventListener("click", showTooltip);
};


const submit = (() => {

    if(!repertoireName.value){
        return messages('Insira o nome do repertorio!','alert-danger')
    }
   const fields = {
        _method:'POST',
        name: repertoireName.value,
    }
    return rhythmStore.insert(api_repertoire, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            repertoireName.value = null;
            modalNewRepertoire.value = !modalNewRepertoire.value;
            addRepertorie.value = !addRepertorie.value
            getRepertorie();
            messages("Novo repertorio salvo!", "alert-success")
        }
    })
    .catch((e) => {
        returnCath(e);
    })
});

const submitNewMusicRepertoire = (() => {

    const pathParts = window.location.pathname.split('/');
    const tom = Number(pathParts[3]);  // 3

    if(!repertoireId.value || repertoireId.value == null ){
        return messages('Selecione o repertorio!','alert-danger')
    }
   const fields = {
        _method:'POST',
        music_id :props.music.id,
        tom:  tom || 0,
    }

    return rhythmStore.insert( `${urls.api}repertoires/${repertoireId.value}/musics`, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            repertoireName.value = null;
            addRepertorie.value = !addRepertorie.value;
            modalRepertorie.value= !modalRepertorie.value;
            getRepertorie();
            messages("Musica inserida no repertorio", "alert-success")
        }
    })
    .catch((e) => {
        returnCath(e);
    })
});

const getRepertorie = (async () => {
   
    return await rhythmStore.get(api_repertoire, config)
    .then( response => {
        repertoires.value = response.data.data;
    })
    .catch((e) => {
        console.error("Erro: ",e)
    })
   
});

const returnCath = ((e) => {
	const retornCatch = catchDefault(e);
	messages(retornCatch[0],retornCatch[1])
});



onMounted( () => {

  if (isLoggedIn.value) {
    getRepertorie();
  }
}) ;
</script>
<style scoped>

.card{
    border: solid, 1px, #89443d ;
    background-color: #fff;
    color: #89443d;
}

.form-select{
    background-color: #fff;
}
.form-control{
    background-color: #fff;
}

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
    display: flex;
    flex-wrap: wrap;
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
.btn-primary{
    background-color: #6a2d1a;
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