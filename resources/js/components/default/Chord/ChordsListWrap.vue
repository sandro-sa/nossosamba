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
                            <span v-if="index == 'line_1_position_1'" class="list-field-1 p-0 m-0">{{ filed }}</span><span  v-else class="list-field-2 p-0 m-0" ><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; "></i></span>
                        </template>
                        <span  class="list-field-11 p-0 m-0"></span>
                    </div>

                    <div >
                        <template   v-for="(filed, index) in position[1]"  :key="'l2-' + index">
                            <span v-if="index == 'line_2_position_1'"class="list-field-1 p-0 m-0">{{ filed }}</span><span v-else class="list-field-4 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; "></i></span>
                        </template>
                        <span  class="list-field-11 p-0 m-0"></span>
                    </div>

                    <div >
                        <template  v-for="(filed, index) in position[2]"  :key="'l3-' + index">
                            <span v-if="index == 'line_3_position_1'" class="list-field-1 p-0 m-0">{{ filed }}</span><span v-else class="list-field-6 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; "></i></span>
                        </template>
                        <span  class="list-field-11 p-0 m-0"></span>
                    </div>

                    <div >
                        <template  v-for="(filed, index) in position[3]" :key="'l4-' + index">
                            <span v-if="index == 'line_4_position_1'" class="list-field-1 p-0 m-0">{{ filed }}</span><span v-else class="list-field-8 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; "></i></span>
                        </template>
                        <span  class="list-field-11 p-0 m-0"></span>
                    </div>

                    <div>
                        <template  v-for="(filed, index) in position[4]"  :key="'l5-' + index">
                            <span v-if="index == 'line_5_position_1'" class="list-field-1 p-0 m-0">{{ filed}}</span><span v-else class="list-field-10 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 1000; "></i></span>
                        </template>
                        <span  class="list-field-11 p-0 m-0"></span>
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
.list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 10px;
}

.list-chord {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 10px;
}

.title {
    font-style: italic;
    font-weight: 700;
    text-align: center;
    margin-bottom: 10px;
}

.list-field-2,
.list-field-4,
.list-field-6,
.list-field-8,
.list-field-10 {
    width: 1em;
    height: 1.5em;
    font-weight: normal;
    font-family: sans-serif;
    font-size: 1em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    position: relative;
    border-radius: 3px;
    background-color: transparent;
    transition: background-color 0.3s ease;
}

.list-field-2::before,
.list-field-4::before,
.list-field-6::before,
.list-field-8::before,
.list-field-10::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 0.3px; /* A linha para representar a corda */
    background-color: #070707; /* Cor da linha da corda */
    transform: translateX(-50%);
}

/* Ajustando o ícone para ficar sobre a linha */
.list-field-2 i,
.list-field-4 i,
.list-field-6 i,
.list-field-8 i,
.list-field-10 i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Centraliza o ícone em relação à linha */
    z-index: 1; /* Certifica que o ícone fica sobre a linha */
    pointer-events: none; /* Impede que o ícone interfira com a interação do usuário com outros elementos */
    padding: 1px;
    
}
.list-field-1,
.list-field-3,
.list-field-5,
.list-field-7,
.list-field-9 {
    width: 1em;
    height: 1.5em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    background-color: #f7f7f7; /* Cor neutra para representar casas vazias */
    border-radius: 3px;
}

i{
    font: 0.8em sans-serif;
    background-color: white;
}

.list-field-2 {
    border: none;
    border-radius: 0px;
    border-top: #b18989 2px solid;
}

.list-field-4,
.list-field-6,
.list-field-8 {
    border: none;
    border-radius: 0px;
    border-top: #b18989 1px solid;
}

.list-field-10 {
    border: none;
    border-radius: 0px;
    border-top: #b18989 1px solid;
    border-bottom: #b18989 2px solid;
}

.list-field-1 {
    border-radius: 0px;
    border-right: #b18989 2px solid;
}

.list-field-11 {
    border-left: #b18989 2px solid;
}

.list-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: monospace;
    margin-bottom: 20px;
}

.list-content div {
    display: flex;
    justify-content: center;
}

.list-content span {
    display: inline-block;
    margin-right: 5px;
}

.d-flex {
    display: flex;
}

.m-0 {
    margin: 0;
}

.p-0 {
    padding: 0;
}

.mt-2 {
    margin-top: 10px;
}

.btn {
    background-color: transparent;
    border: none;
    cursor: pointer;
}

.btn:hover {
    color: #ff5722;
}

.bi-trash {
    font-size: 1em;
}


</style>