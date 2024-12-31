<template>
<div class="row">
    <div class="col-md-12">
        <h2 class="text-start">Inserir tom</h2>
        <form action="">
            <div class="row ">
                <div class="col-md-12">
                    <input type="hidden" class="form-control" v-model="id" placeholder="inserir tom">
                    <input type="text" class="form-control" v-model="tone" placeholder="inserir tom" maxlength="4">
                </div>

                <div class="col-md-12 mt-3 d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary" type="button" @click="submit()">Atualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>
<script setup>
import {onMounted, ref } from 'vue';
import { useTone } from '@/store/tone.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf', 'editTone']);
const emits = defineEmits(['sendMessageDad', 'excuteLoadind','execute'])
const id = ref(null);
const tone = ref(null);
const toneStore = useTone();
const page = urls.api+'tone';
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
        _method:'PUT',
        tone: tone.value ? capitalizeWords(tone.value) : "",
    }
    return toneStore.update(page+'/'+id.value, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            emits('sendMessageDad', 'Tom atualizado.','alert-success');
            tone.value = null;
            emits('execute');
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
onMounted(() => {
    const dataForUpdate = props.editTone
    setTimeout(() =>{
        id.value = dataForUpdate.id;
        tone.value = dataForUpdate.tone;
      
    },1000);
});
</script>
<style scoped>
  input{
	background-color: white;
  }
</style>