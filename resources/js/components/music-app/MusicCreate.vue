<template>
	<alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<singer-create v-if="newSinger"  :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></singer-create>
				<tone-create v-if="newTone"  :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></tone-create>
				<rhythm-create v-if="newRhythm"  :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></rhythm-create>
				<form @submit.prevent="submitForm">
					<div class="row">

						<div class="col-md-4">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="music_name">Informar musico</label>
										<select v-model="singer" class="form-select" aria-label="Default select example">
											<template v-for="singer in singers" :key="singer.id">
												<option :value="singer.id">{{singer.singer_name  }}</option>
											</template>
										</select>
									</div>
								</div>
								<div class="col-md-4 d-flex justify-content-start align-items-end">
									<div class="">
										<button class="btn  btn-primary" type="button" @click="newSinger =! newSinger">Novo</button>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="music_name">Informar tom</label>
										<select v-model="tone" class="form-select" aria-label="Default select example">
											<template v-for="tone in tones" :key="tone.id">
												<option :value="tone">{{tone.tone  }}</option>
											</template>
										</select>
									</div>
								</div>
								<div class="col-md-4 d-flex justify-content-start align-items-end">
									<div class="">
										<button class="btn  btn-primary" type="button" @click="newTone =! newTone">Novo</button>
									</div>
								</div>
							</div>
							
						</div>

						<div class="col-md-4">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="music_name">Informar ritmo</label>
										<select v-model="rhythm" class="form-select" aria-label="Default select example" >
											<template v-for="rhythm in rhythms" :key="rhythm.id">
												<option :value="rhythm.id">{{rhythm.rhythm  }}</option>
											</template>
										</select>
									</div>
								</div>
								<div class="col-md-4 d-flex justify-content-start align-items-end">
									<div class="">
										<button class="btn  btn-primary" type="button" @click="newRhythm =! newRhythm">Novo</button>
									</div>
								</div>
							</div>
						</div>

					</div>
			
					<div class="form-group">
						<label for="music_name">Título da Música</label>
						<input v-model="music_name" type="text" class="form-control" id="music_name" name="music_name" placeholder="Digite o título da música">
					</div>

					<div class="form-group">
						<label for="music_name">Introdução</label>
						<input v-model="introduction" type="text" class="form-control" id="introduction" name="introduction" placeholder="Introdução em cifras">
					</div>
				
					<div class="form-group">
						<label for="music">Letra da Música</label>
						<div ref="editor" class="quill-editor"></div>
					</div>
		
					<div class="d-flex justify-content-end">
						<button type="button" class="btn btn-success mt-5" @click="submit()">Salvar</button>
					</div>
			</form>
			</div>
			<div class="col-md-4">
				<div  v-if="music" class="container-music mt-5">
					<h2 style="font-size: 1em; font-weight: bold;">{{ music_name }}</h2>
					<span v-if="tone" style="font-size: 0.8em; font-weight: bold;">Tom: {{ tone.tone }}</span>
					<br>
					<p class="m-0 p-0" v-if="introduction" style="font-size: 0.8em; font-weight: bold;">Intro: {{ introduction }}</p>
					<p class="m-0 p-0" v-if="chords" style="font-size: 0.8em; font-weight: bold;">Notas: | <span v-for="a , index in chords" :key="index">{{ a }} | </span></p>
					<br>
					<div v-html="music"></div>
				</div>
			</div>
		</div>

    </div>
  </template>
  
<script setup>
import { ref, onMounted, getCurrentInstance } from 'vue';
import { useSinger } from '@/store/singer.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
const { proxy } = getCurrentInstance();
const props = defineProps(['token_crsf']);
const newSinger = ref(false);
const newTone = ref(false);
const newRhythm = ref(false);
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const store = useSinger();
const singers = ref(null);
const tones = ref(null);
const rhythms = ref(null);
const singer = ref(null);
const tone = ref(null);
const rhythm = ref(null);
const introduction = ref(null);
const music_name = ref('');
const music = ref(null);
const editor = ref(null);
const chords = ref([]);


const config = {
   headers: {
       'Content-Type': 'multipart/form-data',
       'Accept': 'application/json',
   }
};

const messageSweet = ((text, type ) => {
	proxy.$swal.fire({
		title: text ,
		icon: type,

    });
});
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
const submit = (async () => {
    isLoading.value = true;
   const fields = {
        _method:'POST',
		singer_id: singer.value,
		tone_id: tone.value.id,
		rhythm_id: rhythm.value,
		introduction: introduction.value,
		music_name: music_name.value,
		music: music.value,
		chords: chords.value,
        
    }
    return store.insert(urls.api+'music', fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            messageSweet( 'Nova letra salva','success');
            execute();
			singer.value = null;
			tone.value = null;
			rhythm.value = null;
			introduction.value = null;
			music_name.value = null;
			music.value = null;
        }
    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => isLoading.value = false);
});
const getRhythm = (async () => {
    return await store.get(urls.api+'rhythm', config)
    .then( response => {
        rhythms.value = response.data.data;
    })
    .catch((e) => {
        returnCath(e);
    })
});
const getTone = (async () => {
    return await store.get(urls.api+'tone', config)
    .then( response => {
        tones.value = response.data.data;
    })
    .catch((e) => {
        returnCath(e);
    })
});
const getSinger = (async () => {
    return await store.get(urls.api+'singer', config)
    .then( response => {
        singers.value = response.data.data;
    })
    .catch((e) => {
        returnCath(e);
    })
});
const returnCath = ((e) => {
	
	const retornCatch = catchDefault(e);
	messages(retornCatch[0],retornCatch[1])
});
const execute = (() => {
	getSinger();
	getTone();
	getRhythm();
	newSinger.value = false;
	newTone.value = false;
	newRhythm.value = false;
	isLoading.value = false;
}) ;
onMounted(() => {
  const quill = new Quill(editor.value, {
      theme: 'snow',
      placeholder: 'Escreva a letra da música aqui...',
      modules: {
        toolbar: [
			[{ 'header': '1' }, { 'header': '2' }, { 'font': [] }],
			[{ 'list': 'ordered' }, { 'list': 'bullet' }],
			[{ 'align': [] }],
			['bold', 'italic', 'underline'],
			['link'],
			['blockquote'],
			[{ 'color': [] }, { 'background': [] }],
			['clean']
        ]
      }
    });
  
    quill.on('text-change', () => {
		let htmlContent = quill.root.innerHTML;
		music.value =  htmlContent
 		htmlContent = htmlContent.replace(/ {2,}/g, (match) => {
    	return match.replace(/ /g, '&nbsp;');
 	});

	music.value = processarTexto(htmlContent)
    });
	execute();
});

const processarTexto = (htmlContent) => {

	chords.value = [];
	const salvaAcordeArray = (acorde) => {
    let novoAcorde = acorde;

    novoAcorde = novoAcorde.replace(/M/g, "+");      
    novoAcorde = novoAcorde.replace(/SUS/g, "#");    
    novoAcorde = novoAcorde.replace(/Sus/g, "#");    
    novoAcorde = novoAcorde.replace(/dim/g, "°");     

    novoAcorde =  novoAcorde.replace(/\((\d{1,2})\)/g, (match, p1) => {
    
		let num = parseInt(p1);
    	if (num >= 1 && num <= 20) {
        	return '/' + num;  
    	}
    	return match;  
	});

	novoAcorde = novoAcorde.replace(/([A-Za-z]+)(-?\d{1,2})\/(-?\d{1,2})/g, (match, chord, numBeforeSlash, numAfterSlash) => {
    let numBefore = parseInt(numBeforeSlash); 
    let numAfter = parseInt(numAfterSlash);  

    if (Math.abs(numBefore) > Math.abs(numAfter)) {
        let newNumBefore = (numAfterSlash[0] === '-' ? '' : '') + numAfter;
        let newNumAfter = (numBeforeSlash[0] === '-' ? '' : '') + numBefore;
        return chord + newNumBefore + '/' + newNumAfter;
    }
    return match;
});   
    if (!chords.value.includes(novoAcorde)) {
        chords.value.push(novoAcorde);
    }

	return novoAcorde;
};


const adicionarClasseAcorde = (texto) => {

  const acordes = texto.split(/(\s|\&nbsp\;|\^|\s+)/); 
  let resultado = '';

  acordes.forEach((acorde) => {
    
    if (acorde.trim() && acorde !== " " && acorde !== "&nbsp;" && acorde !== "^") {
		acorde = salvaAcordeArray(acorde);
      	resultado += `<span class="acorde ${acorde} m-0 p-0">${acorde}</span>`;

    } else if (acorde === "^") {
      	resultado += '^'; 
    } else {
      	resultado += acorde;
    }

  });

  return resultado;

};

  let paragrafos = htmlContent.split(/<\/p>\s*<p>|<\/p>$|<p>/);
  paragrafos = paragrafos.map((paragrafo, index) => {
    paragrafo = paragrafo.trim();
    if (paragrafo === "") return '';
	if (index % 2 === 1 && paragrafo.trim()) {
      	paragrafo = adicionarClasseAcorde(paragrafo);
		  paragrafo = `<p class="m-0 p-0 mt-1" style="font-size: 0.6em; line-height: 0.8; color:blue; ">${paragrafo}</p>`;
    	return 	paragrafo;
    }else{
		paragrafo = `<p class="m-0 p-0" style="font-size: 0.8em; line-height: 1.5; font-weight: 600;">${paragrafo}</p>`;

    	return 	paragrafo;
	}

	
  });
   return paragrafos.filter(p => p !== '').join(''); // Filtrando paragrafos vazios
};


</script>
<style scoped>
  .quill-editor {
    min-height: 700px;
	background-color: rgb(255, 255, 255);
  }
	input, select{
	background-color: white;

	}
.container-music{
	background-color: white;
	padding: 20px;
}
.line {
    line-height: 0.2; /* Não esta funcionado */
    margin: 0;
}
</style>
  
