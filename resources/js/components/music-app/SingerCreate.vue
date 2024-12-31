<template>
<div class="row">
    <div class="col-md-12">
        <h2 class="text-start">Inserir músico ou grupo</h2>
        <form action="">
            <div class="row ">
                <div class="col-md-12">
                    <input type="text" class="form-control" v-model="singer_name" placeholder="Nome do músico ou grupo">
                </div>
                <div class="col-md-12  mt-3">
                    <select v-model="singer_type" class="form-select" aria-label="Default select example">
                    <option selected>Tipo de músico</option>
                    <option value="0">Solo</option>
                    <option value="1">Grupo</option>
                    </select>
                </div>
                <div class="col-md-12 mt-3 d-flex justify-content-end">
                    <button class="btn btn-sm btn-success" type="button" @click="submit()">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script setup>
import {ref } from 'vue';
import { useSinger } from '@/store/singer.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf']);
const emits = defineEmits(['sendMessageDad', 'excuteLoadind','execute'])
const singer_name = ref(null);
const singer_type = ref(null);
const singerStore = useSinger();
const page = urls.api+'singer';

const config = {
   headers: {
       'Content-Type': 'multipart/form-data',
       'Accept': 'application/json',
   }
};

const capitalizeWords = ((string) => {
  return string
    .split(' ')                    
    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())  
    .join(' ');                   
});
const submit = (() => {
    emits('excuteLoadind')
   const fields = {
        _method:'POST',
        singer_name: singer_name.value ? capitalizeWords(singer_name.value) : singer_name.value,
        singer_type: singer_type.value
    }
    return singerStore.insert(page, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            emits('sendMessageDad', 'Novo músico inserido','alert-success');
            singer_name.value = "";
            singer_type.value = "";
            emits('execute')
        }
    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => emits('excuteLoadind'));
});
const returnCath = ((e) => {
	const retornCatch = catchDefault(e);
	emits('sendMessageDad',retornCatch[0],retornCatch[1]);
});
</script>
<style scoped>
  input, select{
	background-color: white;
  }
</style>