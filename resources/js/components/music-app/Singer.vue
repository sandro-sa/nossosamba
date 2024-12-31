<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
    <div class="container">
        <singer-update v-if="update" :editSinger="editSinger" :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></singer-update>
        <singer-create v-else :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></singer-create>
        <div v-if="singers" class="row">
            <div class="col">
                <h2 class="text-start mt-3">Músicos/Grupos</h2>
            <table class="table table-bordered table-info table-hover">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Controle</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for=" singer in singers" :key="singer.id">
                    <tr>
                    <th scope="row">{{ singer.id }}</th>
                    <td>{{ singer.singer_name }}</td>
                    <td v-if="singer.singer_type">Grupo</td><td v-else>Solo</td>
                    <td>
                        <button class="btn me-3" title="Editar" @click="updateSinger(singer)" ><i class="bi bi-pencil-square"></i></button>
                        <a :href="urls.url+'lista/musicas/'+singer.id"><button class="btn" title="Musicas"><i class="bi bi-music-note-list"></i></button></a>
                    </td>
                    </tr> 
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useSinger } from '@/store/singer.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf']);
const singers = ref(false);
const editSinger = ref(false);
const update = ref(false);
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const singerStore = useSinger();
const page = urls.api+'singer';
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

const updateSinger = ((singer) =>{
  isLoading.value = true;
  editSinger.value = singer;
  update.value = false;
  setTimeout(() => {
    editSinger.value = singer;
    update.value = true;
    isLoading.value = false;
  },1000)
 
 
});

const execute = (async () => {
    isLoading.value = true;
    return await singerStore.get(page, config)
    .then( response => {
        singers.value = response.data.data;
        update.value = false;
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

onMounted(() => execute() );
</script>