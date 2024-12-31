<template>
	<div class="container">
		<alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
		<div class="row">
            <div class="col-md-6">
                <h2 class="text-start mt-3">{{singer.singer_name}}</h2>
            	<table  v-if="musics" class="table table-bordered table-info table-hover">
					<thead>
						<tr>
						<th scope="col">#</th>
						<th scope="col">Musicas</th>
						<th scope="col">Ritmo</th>
						<th scope="col">tom</th>
						<th scope="col">Visualizar</th>
						</tr>
					</thead>
					<tbody>
						<template v-for=" music in musics" :key="music.id">
						<tr >
						<th scope="row">{{ music['id'] }}</th>
						<td>{{ music['music_name'] }}</td>
						 <td>{{ music['rhythm']['rhythm'] }}</td>
						<td>{{ music['tone']['tone'] }}</td>
                        <td class="p-1"><button class="btn"  type="button" @click="questionAlert(music)" style="color: red;"><i class="bi bi-trash"></i></button>
						<button class="btn"  type="button" @click="showMusic(music)" style="color: blue;"><i class="bi bi-file-earmark-music-fill"></i></button>
                        </td> 
						</tr> 
						</template>
					</tbody>
            	</table>
       		</div>
			<div class="col-md-6">
				<template v-if="music">
					
				<div @mouseover="mostrarProximoElemento">
					<show-singer-music  :token_crsf="token_crsf" :music="music" ></show-singer-music>
				</div>
				</template>
				<template v-if="chords"  v-for=" chord in chords" :key="chord.id">
                   
                   <div class="nota" :id="chord.chord_name" style="display: none;">
                       <h5 class="text-center title m-0 p-0 mt-2 ">{{ chord.chord_name}}</h5>
                       <div class="chord-content mb-4 d-flex">
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
   		</div>
	</div>
</template>
  
<script setup>
import {onMounted, ref,getCurrentInstance } from 'vue';
import urls from '@/utils/urls';
import { useSinger } from '@/store/singer.js';
import{ catchDefault } from '@/utils/messagesCatch';
const props = defineProps(['token_crsf','singer']);
const urlSingerMusics = urls.api+'singer_musics/'+props.singer.id;
const urlMusic = urls.api+'music/';
const singerStore = useSinger();
const musics = ref(false);
const chords = ref(false);
const music = ref("");
const isLoading = ref(false);
const msg = ref(false);
const alert = ref(false);
const { proxy } = getCurrentInstance();
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

const convertForArray = ((json) => {
    let parsedArray = JSON.parse(json);
    return parsedArray
});

const questionAlert = (music) => {
  proxy.$swal.fire({
   // https://sweetalert2.github.io/#input-types
    title: `Excluir ${music.music_name} ?`,
    text: 'Você quer continuar esta ação?',
    icon: 'question',
    confirmButtonText: 'Sim',
    cancelButtonText: 'Não',
    showCancelButton: true,
    reverseButtons: false ,
}).then((result) => {
    if (result.isConfirmed) {
       deleteMusic(music.id)
    }
  });
};

const resutSucess = () => {
  proxy.$swal.fire({
    title: `Sucesso !`,
        icon: 'success',

    });
};


const showMusic = ((value) => {
	music.value = "";
	setTimeout(() =>{
		music.value = value;
	})
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

const deleteMusic = (async (id) => {
    isLoading.value = true;
    const field = {
        _method:'DELETE',}
    return await singerStore.delete(urlMusic+id,field, config)
    .then( response => {
        resutSucess()
        execute()
    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => isLoading.value = false);
});


const execute = (async () => {
    isLoading.value = true;
    return await singerStore.get(urlSingerMusics, config)
    .then( response => {

        musics.value = response.data.musics;
        chords.value = response.data.chords;
		
    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => isLoading.value = false);
});

const returnCath = ((e) => {
	const retornCatch = catchDefault(e);
	messages(retornCatch[0],retornCatch[1])
});


onMounted(() => {
	execute()
	});
</script>
<style scoped>
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
    border: 2px solid black;
    padding: 5px;
    padding-right: 20px;
    
}
.field {
    width: 1.3em;
    height: 1.8em;
    font-weight: bold;
    font-family: sans-serif;
    font-size: larger;
    border-top: #f80303 1px solid; 
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    position: relative;
    overflow: hidden;
  
}
.field::before {
    content: "";
    position: absolute;
    top: 0; 
    bottom: 0;
    left: 50%; 
    width: 1px; 
    background-color: #f80303; 
    transform: translateX(-50%);
}
.field2 {
    width: 1em;
    height: 2em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
   
}

</style>