<template>
<div class="list container d-flex justify-content-center">
    <div  v-for=" chord in chords" :key="chord.id" >
        <div class="list-chord ">      
            <span v-for="(position , index) in  convertForArray(chord.chord_positions)" :key="index">
                <div class="list-content m-0 p-0 mt-2 ">
                    <div class="d-flex justify-content-center m-0 p-0 ">
                        <h6 class="m-0 p-0 text-center title">{{ chord.chord_name}}</h6>
                    </div>

                    <div >
                        <template  v-for="(filed, index) in position[0]" :key="'l1-' + index">
                            <span v-if="index == 'line_1_position_1'" class="list-field2 ">{{ filed }}</span><span v-else class="list-field ">{{ filed }}</span>
                        </template>
                    </div>

                    <div >
                        <template   v-for="(filed, index) in position[1]"  :key="'l2-' + index">
                            <span v-if="index == 'line_2_position_1'"class="list-field2">{{ filed }}</span><span v-else class="list-field">{{ filed }}</span>
                        </template>
                    </div>

                    <div >
                        <template  v-for="(filed, index) in position[2]"  :key="'l3-' + index">
                            <span v-if="index == 'line_3_position_1'" class="list-field2">{{ filed }}</span><span v-else class="list-field">{{ filed }}</span>
                        </template>
                    </div>

                    <div >
                        <template  v-for="(filed, index) in position[3]" :key="'l4-' + index">
                            <span v-if="index == 'line_4_position_1'" class="list-field2">{{ filed }}</span><span v-else class="list-field">{{ filed }}</span>
                        </template>
                    </div>

                    <div>
                        <template  v-for="(filed, index) in position[4]"  :key="'l5-' + index">
                            <span v-if="index == 'line_5_position_1'" class="list-field2">{{ filed}}</span><span v-else class="list-field m-0 p-0">{{ filed }}</span>
                        </template>
                    </div>

                    <div v-if="delete" class="d-flex justify-content-center">
                        <button class="btn" @click="deleteChord(chord,index)"><i class="bi bi-trash" style="color: red;" ></i></button>
                    </div>
                </div>
            </span>
        </div>
    </div>
</div>
</template>


<script setup>
import {  ref } from 'vue';
import { useChord } from '@/store/chord.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf', 'chords', 'delete']);
const emtis = defineEmits(['deleteSuccess', 'messages'])
const isLoading = ref(false);
const chordStore = useChord();
const page = urls.api+'chord';

const config = {
   headers: {
       'Content-Type': 'multipart/form-data',
       'Accept': 'application/json',
   }
};

const convertForArray = ((json) => {
    let parsedArray = JSON.parse(json);
    return parsedArray
});

const deleteChord = ((chord, index) => {
   const fields = {
        _method:'Delete',
        id : chord.id,
        chord_name : [chord.chord_name],
        key: index,
    }

    return chordStore.delete(page+"/"+fields.id, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
           emtis('deleteSuccess');

        }
    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => isLoading.value = false);
});

const returnCath = ((e) => {
	const retornCatch = catchDefault(e);
	emtis('messages',retornCatch[0],retornCatch[1])
});


</script>

<style scoped>
.list{
    display: flex;
    flex-wrap: wrap;
}
.list-content {
    font-family: monospace;
}
.list-chord{
    display: flex;
    flex-wrap: wrap;
}
.title{
    font-style: italic;
    font-weight: 700;
}
.list-field {
    width: 1em;
    height: 1.8em;
    font-weight: normal;
    font-family: sans-serif;
    font-size: .8em;
    border-top: #f80303 1px solid; /* Mantém a borda superior da célula */
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    position: relative; /* Necessário para o posicionamento da line */
    overflow: hidden; /* Garante que a line não ultrapasse as extremidades */
}
.list-field::before {
    content: "";
    position: absolute;
    top: 0; /* Começa a line no topo da célula */
    bottom: 0; /* Faz a line cobrir a célula sem ultrapassar */
    left: 55%; /* Centraliza a line na horizontal */
    width: 1px; /* Largura da line */
    background-color: #289c0b; /* Cor da line */
    transform: translateX(-50%); /* Ajusta o centro exato da line */
}
.list-field2 {
    width: 1em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    margin: 0px;
    padding: 0px;
}
</style>