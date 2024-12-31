<template>
<div class="row">
    <div class="col-md-12">
        <h2 class="text-start">Inserir tom</h2>
        <form action="">
            <div class="row ">
                <div class="col-md-12">
                    <input type="text" class="form-control" v-model="tone" placeholder="inserir tom" maxlength="4">
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
import { ref } from 'vue';
import { useTone } from '@/store/tone.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf']);
const emits = defineEmits(['sendMessageDad', 'excuteLoadind','execute'])
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
        _method:'POST',
        tone: tone.value ? capitalizeWords(tone.value) : "",
    }
    return toneStore.insert(page, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            emits('sendMessageDad', 'Novo tom inserido','alert-success');
            tone.value = "";
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

</script>
<style scoped>
  input{
	background-color: white;
  }
</style>