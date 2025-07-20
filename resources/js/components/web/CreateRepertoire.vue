<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
	<div class="container-fluid" v-if="!showMusics">
        <h2 class="text-start">Criar repertório aleatório</h2>
        <form  @submit.prevent="createRepertoire">
		<div class="row">
             <div class="col-md-6">
                <label for="" class="form-label">Escolher Ritmo</label>
                <select v-model="rhythm_id" class="form-select" aria-label="Default select example" required>
                <option selected>Escolha a cadência</option>
                <template  v-for="rhythm in rhythms" :key="rhythm.id">
                    <option :value="rhythm.id">{{ rhythm.rhythm}}</option>
                </template>
                </select>
            </div>
            <div class="col-md-6">
                <label for="" class="form-label">Escolher tom</label>
                <select v-model="tone_id"  class="form-select" aria-label="Default select example" >
                <option selected>Escolha o tom</option>
                 <template  v-for="tone in tones" :key="tone.id">
                    <option :value=" tone.id">{{ tone.tone}}</option>
                </template>>
                </select>
            </div>

             <div class="d-grid gap-2">
              <button class="btn btn-sm btn-primary mt-2 "  type="submit"> Criar</button>
            </div>
        </div>
        </form>

        <template v-if="musics">
             <div class="row">
                 <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary mt-2 "  type="buttom" @click="show()"  @back="showMusics = !showMusics"> Ver Repertório</button>
                </div>
                <div class="">
                    <ul v-for="music in musics"  class="list-group">
                        <li class="list-group-item">{{music.music_name}}</li>
                    </ul>
                </div>
               
            </div>
        </template>
	</div>

    <show-repertoire v-if="showMusics" :musics="musics"  @back="showMusics = !showMusics"></show-repertoire>
    
</template>
  
<script setup>
import { onMounted, ref, getCurrentInstance} from 'vue';
import { useRhythms } from '@/store/rhythms';
import { useTone } from '@/store/tone';
import { useCreateRepertoire } from '@/store/create_repertoire';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
import ShowRepertoire from './ShowRepertoire.vue';
const props = defineProps(['token_crsf']);
const rhythms = ref(false);
const tones = ref(false);
const musics = ref(false);
const rhythm_id = ref(null);
const tone_id = ref(null);
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const rhythmstore = useRhythms();
const toneStore = useTone();
const createRepertoireStore = useCreateRepertoire();
const pageRhythm = urls.api+'rhythm';
const pageTone = urls.api+'tone';
const pageRepertoire = urls.api+'repertoire/sort';
const { proxy } = getCurrentInstance();
const showMusics = ref(false);
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


const show = () => {
    showMusics.value = !showMusics.value
    console.log( showMusics.value)
}

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

const createRepertoire = (() => {

    if( !rhythm_id.value){
        return messageSweet( 'Excolher ritmo das musicas','warning');
    }

   const fields = {
        rhythm_id: rhythm_id.value,
        tone_id :tone_id.value,
    }
    return createRepertoireStore.generate(pageRepertoire, fields, config)
    .then((response) => {
        if(response.data.data.length === 0){
            messageSweet( 'Não existe repertório com esta combinação','warning');
        }else{
            musics.value = response.data.data;
        }
        
    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => isLoading.value = false);
});


const execute = async () => {
    isLoading.value = true;

    try {
        const rhythmResponse = await rhythmstore.get(pageRhythm, config);
        rhythms.value = rhythmResponse.data.data;
        const toneResponse = await toneStore.get(pageTone, config);
        tones.value = toneResponse.data.data;
    } catch (e) {
        returnCath(e); 
    } finally {
        isLoading.value = false;
    }
};

const returnCath = ((e) => {
	const retornCatch = catchDefault(e);
	messages(retornCatch[0],retornCatch[1])
});


const closeFullScreen  = () => {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) { // Para Firefox
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) { // Para Chrome, Safari e Opera
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { // Para Internet Explorer/Edge
        document.msExitFullscreen();
    }
}

onMounted(() => { 
    execute();
    closeFullScreen();
} );

</script>
