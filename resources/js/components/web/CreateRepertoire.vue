<template>
  <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert" />

  <div class="container-fluid" v-if="!showMusicsSort">
    
      <div class="all-repertoire" v-if="allRepertoire" >
          <div 
          v-for="r in allRepertoire"
          :key="r.id"
          class="mb-4 mt-4 p-3 card repertoire"
          >

          <div class="d-flex justify-content-between align-items-center mb-1"><h5>{{ r.name }}</h5><button class="btn btn-dark btn-sm " @click="goRepertoire(r.id)">Ver Repertorio</button></div> 
              <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Musicas
              </button>

              <ul class="dropdown-menu">
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
                          <span class="text-muted">
                          {{ index + 1 }}. {{ element.music_name }}
                        
                          </span>
                          <span class="mx-1" @click="deleteSongRepertoire(r.id ,element)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                              <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                          </svg>
                          </span>
                      </div>
                      </template>
                  </draggable>
              </ul>
          </div>
      </div>

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
          <button class="btn btn-sm btn-secondary" type="submit">Criar</button>
        </div>
      </div>
    </form>

    <div v-if="musics && musics.length" class="row mt-3">
      <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-secondary" @click="showSort">Ver Repertório</button>
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


  <show-repertoire-sort
    v-if="showMusicsSort"
    :musics="musics"
    @back="showMusicsSort = false; closeFullScreen();"
  />


</template>

<script setup>
import { onMounted, ref, getCurrentInstance} from 'vue';
import { useRhythms } from '@/store/rhythms';
import { useTone } from '@/store/tone';
import { useCreateRepertoire } from '@/store/create_repertoire';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
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
const showMusicsSort = ref(false);
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

const showSort = () => {
    showMusicsSort.value = !showMusicsSort.value
    console.log( showMusicsSort.value)
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


const goRepertoire = (repertoire) => {
    window.location.href = `${urls.url}repertorios/${repertoire}`;
};

const onReorder = async (repertoire) => {
  const original = [...repertoire.musics].sort((a, b) => a.position - b.position);

  const updates = repertoire.musics.map((m, index) => {
    const originalPosition = original.find(o => o.id === m.id)?.position;
    return {
      music_id: m.id,
      tom: m.tom,
      position: index + 1,
      changed: originalPosition !== (index + 1)
    };
  }).filter(m => m.changed); // só os que mudaram

  if (updates.length === 0) {
    console.log("Nada mudou");
    return;
  }

  try {
    await Promise.all(
      updates.map(item =>
        rhythmstore.update( `${urls.api}repertoires/${repertoire.id}/musics/${item.music_id}`,
          { _method:"put", position: item.position, tom: item.tom, music_id: item.music_id },
          config
        )
      )
    );
    messages('Ordem atualizada com sucesso', 'alert-success');
  }catch (e) {
    returnCatch(e);
  }
};

const deleteSongRepertoire = (  (repertoire_id,song) =>{
  proxy.$swal.fire({
    // https://sweetalert2.github.io/#input-types
      title: `Retirar do repertorio`,
      text: `${song.music_name}?`,
      icon: 'question',
      confirmButtonText: 'Sim',
      cancelButtonText: 'Não',
      showCancelButton: true,
      reverseButtons: false ,
  }).then( async (result) => {
      if (result.isConfirmed) {
        return await rhythmstore.delete(`${urls.api}repertoires/${repertoire_id}/musics/${song.id}`,{_method: 'Delete'}, config)
        .then( response => {  
          messages('Musica retirada.', 'alert-success'); 
            execute();
            getRepertorie();
        })
        .catch((e) => {
            returnCatch(e)
        })
      }
    });
})

const returnCatch = (e) => {
  const [text, type] = catchDefault(e);
  console.log("ok1")
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
<style scoped>
.all-repertoire {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem; /* Espaço entre os itens */
}
.repertoire {
  flex: 1 1 300px; /* Cresce, encolhe e começa com 300px */
  max-width: 100%;
  background-color: white;
}
.text-muted{
    font-size: 12px;
}
.btn-secondary{
  background-color: #6a2d1a;
}
.dropdown-menu, .list-group-item, .form-select{
  background-color: white;
}
</style>