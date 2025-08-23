<template>
	<alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>


<div class="mb-3 position-relative d-flex justify-content-end align-items-start">

  <input 
    v-model="searchTerm" 
    @input="handleSearchInput" 
    type="text" 
    class="form-control me-2" 
    placeholder="Buscar música..."
    style="max-width: 400px;"
  />
  <ul v-if="filteredMusics.length > 0" class="autocomplete-list" >
    <li 
      v-for="music in filteredMusics" 
      :key="music.id"
      @click="goToMusic(music.id)"
    >
      {{ reduceText(music.music_name, 30) }}
    </li>
  </ul>
</div>


		<div class="d-flex flex-wrap ">
      
      		<template v-for="singer in singers" :key="singer.id">
            <template v-if="!singer.musics">
              
              <div class="dropdown m-1 ">
                <div class="card" style=" width: 200px; border-radius: 0px;">
                  <img :src="'/storage/'+singer.image " class="card-img-top" :alt="'foto do cantor ou grupo '+ singer.singer_name" style=" border-radius: 0px;">
              </div>
                <button class="btn btn btn-secondary text-start "type="button" :title="singer.singer_name">{{ reduceText(singer.singer_name,  12) }}</button>
              </div>
            </template>
            <template v-else >
              <div class="dropdown m-1">
                <div class="card" style=" width: 200px; border-radius: 0px;" >
                  <img :src="'/storage/'+singer.image " class="card-img-top" :alt="'foto do cantor ou grupo '+ singer.singer_name" style=" border-radius: 0px;">
              </div>
                  <button class="btn  btn-primary text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false" :title="singer.singer_name">
                    <i  class="bi bi-music-note-list me-2"></i> {{ reduceText(singer.singer_name, 12) }} 
                  </button>
                  <ul class="dropdown-menu">
                    <template  v-for="music in singer.musics" :key="music.id">
                      <!-- gostaria de uma rolagem para mais que 6 music_name -->
                      <li :title="music.music_name"><a class="dropdown-item" :href="pageMusic+music.id">{{ reduceText(music.music_name, 22 )}}</a></li>
                    </template>
                  </ul>
              </div>
            </template>
			
			</template>
		</div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue';
import { useSinger } from '@/store/singer.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
import 'quill/dist/quill.snow.css';
const props = defineProps(['token_crsf']);
const singerStore = useSinger();
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const singers = ref(null);
const page = urls.api+'all_singer';
const pageMusic = urls.url+'letra/';

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

const execute = (async () => {
    isLoading.value = true;
    return await singerStore.get(page, config)
    .then( response => {
        singers.value = response.data.data;

    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => isLoading.value = false);
});

const reduceText =  ((text, limit=18) => {
    if(text.length > limit){
        return text.substring(0, limit) + "...";
    }
    return text;
});

const searchTerm = ref('');
const filteredMusics = ref([]);

const handleSearchInput = () => {
  const query = searchTerm.value.trim().toLowerCase();
  if (query.length < 3) {
    filteredMusics.value = [];
    return;
  }

  // Flatten all musics from all singers
  const allMusics = singers.value
    .filter(singer => singer.musics && singer.musics.length > 0)
    .flatMap(singer => singer.musics);

  // Filter based on input
  filteredMusics.value = allMusics.filter(music =>
    music.music_name.toLowerCase().includes(query)
  );
};

const goToMusic = (id) => {
  window.location.href = pageMusic + id;
};

const returnCath = ((e) => {
	const retornCatch = catchDefault(e);
	messages(retornCatch[0],retornCatch[1])
});

onMounted(() => {
	execute();
});


</script>
<style scoped>

  .flex-wrap{
    justify-content: center;
  }

  input, select{
	background-color: white;
  }
  .btn-primary{
    width: 200px;
    background-color: #a76f67;
    color: white;
    padding-left: 20px;
    border: #89443d;
    border-radius: 0px;
  }
  .btn-primary:hover{
    width: 200px;
    background-color: #89443d;
    color: white;
  }
  .btn-secondary{
    width: 200px;
    border-color: #c49990;
    background-color: #c49990;
    color: white;
    padding-left: 20px;
    border-radius: 0px;
  }

  .dropdown-menu{
    background-color: #ffffff;
    border: #ffffff;
    border-radius: 0px;
    min-width: 200px;
    max-height: 200px; /* define altura máxima */
    overflow-y: auto;  /* ativa rolagem vertical */
  }

  .dropdown-item{
    font-size: 0.8em;
  }
  .dropdown-item:hover{
    background-color: #e2c4b9;
    color: #ffffff;
  }

.autocomplete-list {
  position: absolute;
  background-color: white;
  border: 1px solid #ccc;
  border-radius: 0px 0px  5px 5px;
  width: 400px;
  max-height: 200px;
  overflow-y: auto;
  z-index: 999;
  list-style: none;
  margin: 30px 8px 0 0;
  padding: 0;
}

.autocomplete-list li {
  padding: 8px 12px;
  cursor: pointer;
}

.autocomplete-list li:hover {
  background-color: #e2c4b9;
  color: white;
}
@media only screen and (max-width: 600px) {

    .flex-wrap{
      display: flex;
      justify-content: center;
    }
    .card {
      display: none;
    }
    .dropdown{
      display: flex;
      justify-content: center;
      width: 100%;
    }
    .btn-primary,
    .btn-secondary {
      width: 100% !important;
    }
    .dropdown-menu {
      min-width: 100% !important;
    }
    
	}

</style>
  