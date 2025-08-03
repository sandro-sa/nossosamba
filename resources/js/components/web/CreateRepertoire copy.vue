<template>
  <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert" />

  <div class="container-fluid" v-if="!showMusics">
    <h2 class="text-start">Criar repertório aleatório</h2>

    <form @submit.prevent="createRepertoire">
      <div class="row">
        <div class="col-md-6">
          <label class="form-label">Escolher Ritmo</label>
          <select v-model="rhythm_id" class="form-select" required>
            <option disabled selected>Escolha a cadência</option>
            <option v-for="rhythm in rhythms" :key="rhythm.id" :value="rhythm.id">
              {{ rhythm.rhythm }}
            </option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Escolher Tom</label>
          <select v-model="tone_id" class="form-select">
            <option disabled selected>Escolha o tom</option>
            <option v-for="tone in tones" :key="tone.id" :value="tone.id">
              {{ tone.tone }}
            </option>
          </select>
        </div>

        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-sm btn-primary" type="submit">Criar</button>
        </div>
      </div>
    </form>

    <div v-if="musics && musics.length" class="row mt-3">
      <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-success" @click="show">Ver Repertório</button>
      </div>

      <div class="mt-2">
        <ul class="list-group">
          <li v-for="music in musics" :key="music.id" class="list-group-item">
            {{ music.music_name }}
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- <div v-if="allRepertoire">
    <div
      v-for="r in allRepertoire"
      :key="r.id"
      class="mb-4 p-3 border rounded"
    >
      <h5>{{ r.name }}</h5>

      <draggable
        v-model="r.musics"
        item-key="id"
        group="musics"
        @end="onReorder(r)"
        handle=".drag-handle"
        class="list-group"
      >
        <template #item="{ element, index }">
          <div
            class="list-group-item d-flex justify-content-between align-items-center"
          >
            <span class="drag-handle me-2" style="cursor: grab">☰</span>
            <span>
              {{ index + 1 }}. {{ element.music_name }}
              <small class="text-muted">(Tom: {{ element.tom }})</small>
            </span>
          </div>
        </template>
      </draggable>
    </div>
  </div> -->

  <show-repertoire
    v-if="showMusics"
    :musics="musics"
    @back="showMusics = false; closeFullScreen();"
  />
</template>

<script setup>
import { onMounted, ref, getCurrentInstance} from 'vue';
import { useRhythms } from '@/store/rhythms';
import { useTone } from '@/store/tone';
import { useCreateRepertoire } from '@/store/create_repertoire';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
import ShowRepertoire from './ShowRepertoire.vue';
import draggable from 'vuedraggable';
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
const api_all_repertoire = urls.api+'repertoire/allRepertoire';
const { proxy } = getCurrentInstance();
const showMusics = ref(false);
const allRepertoire = ref(null);
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
        returnCatch(e);
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
        returnCatch(e); 
    } finally {
        isLoading.value = false;
    }
    
};

const getRepertorie = (async () => {
   
    return await rhythmstore.get(api_all_repertoire, config)
    .then( response => {
        allRepertoire.value = response.data.data;
    })
    .catch((e) => {
        console.error("Erro: ",e)
    })

   
});


const onReorder = (repertoire) => {
  const positions = repertoire.musics.map((m, index) => ({
    music_id: m.id,
    position: index + 1,
  }));

  axios.put(`/api/repertoires/${repertoire.id}/musics/reorder`, { positions }, config)
    .then(() => {
      messageSweet('Ordem atualizada com sucesso', 'success');
    })
    .catch(returnCatch);
};


const returnCatch = (e) => {
  const [text, type] = catchDefault(e);
  messages(text, type);
};

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
   getRepertorie();
} );

</script>
