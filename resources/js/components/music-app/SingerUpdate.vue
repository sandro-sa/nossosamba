<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
  
    <div class="row">
      <div class="col-md-12">
        <h2 class="text-start">Atualizar músico ou grupo</h2>
        <form @submit.prevent="submit">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" v-model="id" />
              <input type="text" class="form-control" v-model="singer_name" placeholder="Nome do músico ou grupo" />
            </div>
            <div class="col-md-12 mt-3">
              <select v-model="singer_type" class="form-select" aria-label="Tipo de músico">
                <option selected>Tipo de músico</option>
                <option value="0">Solo</option>
                <option value="1">Grupo</option>
              </select>
            </div>
  
            <!-- Campo para imagem -->
            <div class="col-md-12 mt-3">
              <input class="form-control" type="file" ref="imageFile" placeholder="Imagem do intérprete ou grupo" />
            </div>
  
            <div class="col-md-12 mt-3 d-flex justify-content-end">
              <button class="btn btn-sm btn-primary" type="submit">Atualizar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { onMounted, ref } from 'vue';
  import { useSinger } from '@/store/singer.js';
  import { catchDefault } from '@/utils/messagesCatch';
  import urls from '@/utils/urls';
  
  const props = defineProps(['token_crsf', 'editSinger']);
  const emits = defineEmits(['sendMessageDad', 'excuteLoadind', 'execute']);
  const isLoading = ref(false);
  const alert = ref(false);
  const msg = ref(false);
  const id = ref(null);
  const singer_name = ref(null);
  const singer_type = ref(null);
  const imageFile = ref(null);  // Criando um ref para o campo de arquivo
  const singerStore = useSinger();
  const page = urls.api + 'singer';
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data',
      'Accept': 'application/json',
    }
  };
  
  const capitalizeWords = (string) => {
    return string
      .split(' ')                    
      .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())  
      .join(' ');                   
  };
  
  const submit = () => {
    emits('excuteLoadind');
  
    // Criando um FormData para enviar o conteúdo do formulário, incluindo a imagem
    const fields = new FormData();
    fields.append('_method', 'PUT');
    fields.append('singer_name', capitalizeWords(singer_name.value));
    fields.append('singer_type', singer_type.value);
  
    // Adicionando a imagem ao FormData se ela existir
    if (imageFile.value?.files[0]) {
      fields.append('image', imageFile.value.files[0]);
    }
  
    // Enviando os dados ao servidor
    return singerStore.update(page + "/" + id.value, fields, config)
      .then((response) => {
        if (response.request.status === 200 || response.request.status === 201) {
          emits('sendMessageDad', 'Músico atualizado', 'alert-success');
          singer_name.value = "";
          singer_type.value = "";
          emits('execute');
        }
      })
      .catch((e) => {
        returnCatch(e);
      })
      .finally(() => emits('excuteLoadind'));
  };
  
  const returnCatch = (e) => {
    const retornCatch = catchDefault(e);
    emits('sendMessageDad', retornCatch[0], retornCatch[1]);
  };
  
  onMounted(() => {
    const singer = props.editSinger;
    setTimeout(() => {
      id.value = singer.id;
      singer_name.value = singer.singer_name;
      singer_type.value = singer.singer_type;
    }, 1000);
  });
  </script>
  
  <style scoped>
  input, select {
    background-color: white;
  }
  </style>
  