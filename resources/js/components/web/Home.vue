<template>
	<alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
	<div class="container" >
		<div class="d-flex flex-wrap">
      		<template v-for="singer in singers" :key="singer.id">
            <template v-if="!singer.musics">
              <div class="dropdown m-1 ">
                <button class="btn btn btn-secondary text-start "type="button" :title="singer.singer_name">{{ reduceText(singer.singer_name) }}</button>

              </div>
            </template>
            <template v-else >
              <div class="dropdown m-1">
                  <button class="btn  btn-primary text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false" :title="singer.singer_name">
                    <i  class="bi bi-music-note-list me-2"></i> {{ reduceText(singer.singer_name) }} 
                  </button>
                  <ul class="dropdown-menu">
                    <template  v-for="music in singer.musics" :key="music.id">
                      <li :title="music.music_name"><a class="dropdown-item" :href="pageMusic+music.id">{{ reduceText(music.music_name, 20 )}}</a></li>
                    </template>
                  </ul>
              </div>
            </template>
			
			</template>
		</div>
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

const returnCath = ((e) => {
	const retornCatch = catchDefault(e);
	messages(retornCatch[0],retornCatch[1])
});

onMounted(() => {
	execute();
});


</script>
<style scoped>
  .quill-editor {
    min-height: 700px;
	background-color: rgb(255, 255, 255);
  }
  input, select{
	background-color: white;
  }
  .btn-primary{
    width: 200px;
    background-color: #0d6efd;
    color: white;
    padding-left: 20px;
  }
  .btn-primary:hover{
    width: 200px;
    background-color: #0e5bce;
    color: white;
  }
  .btn-secondary{
    width: 200px;
    border-color: #a4abb4;
    background-color: #a4abb4;
    color: white;
    padding-left: 20px;
  }

  .dropdown-menu{
    background-color: aliceblue;
    min-width: 200px;
  }

  @media only screen and (max-width: 600px) {

    .flex-wrap{
      display: flex;
      justify-content: center;
    }
	}

</style>
  