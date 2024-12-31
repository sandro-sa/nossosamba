<template>
<div class="row">
    <div  class="col-md-12">
        <h2 class="text-start">Atualizar ritmo</h2>
        <form action="">
            <div class="row ">
                <div class="col-md-12">
                    <input type="hidden" class="form-control" v-model="id" >
                    <input type="text" class="form-control" v-model="rhythm" placeholder="Nome do estilo de samba">
                </div>
                <div class="col-md-12  mt-3">
                    <select v-model="time" class="form-select" aria-label="Default select example">
                    <option selected>Velocidade</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    </select>
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
import { onMounted, ref } from 'vue';
import { useRhythms } from '@/store/rhythms.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf', 'editRhythm']);
const emits = defineEmits(['sendMessageDad', 'excuteLoadind','execute'])
const id = ref(null);
const rhythm = ref(null);
const time = ref(null);
const rhythmStore = useRhythms();
const page = urls.api+'rhythm';

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
    emits('excuteLoadind');
   const fields = {
        _method:'PUT',
        rhythm: capitalizeWords(rhythm.value),
        time: time.value
    }
    return rhythmStore.update(page+"/"+id.value, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            emits('sendMessageDad', 'Ritmo atualizado','alert-success');
            rhythm.value = "";
            time.value = "";
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
	emits('sendMessageDad',retornCatch[0],retornCatch[1])
});
onMounted(() => {
    const data = props.editRhythm
    setTimeout(() =>{
        id.value = data.id;
        rhythm.value = data.rhythm;
        time.value = data.time;
    },1000);
});
</script>