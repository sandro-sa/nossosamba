<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
    
                <h2 class="text-center">Posição do acorde</h2>

                <form action="">
                    <div class="row ">
                        <div class="col-sm-2 m-1">
                            <input type="text" class="form-control" v-model="state.title" placeholder="Acorde">
                        </div>
                    </div>
                    <div v-for="(line, lineIndex) in state.lines" :key="'line-' + lineIndex" class="acorde">
                        <div class="col-sm-2 m-1" v-for="(filed, index, aux) in line" :key="lineIndex + '-' + index">
                            <input v-if="aux == 0" type="text" class="form-control" v-model="line[index]" />
                            <input v-else type="number" class="form-control" v-model="line[index]" />
                        </div>
                    </div>

                    <button class="btn btn-sm btn-success mt-2" type="button" @click="submit(state)">Salvar acorde</button>
                </form>
            </div>
            
            <div class="col-md-6 d-flex justify-content-center align-items-center">
               
                <div class="chord-content m-0 p-0 pb-2 ps-2 pe-2" style="background-color: white;">
                    <h5 class="text-center m-0 p-0  "><b>Previa</b></h5>
                    <p class="m-0 p-0 text-center ps-3"><b>{{ state.title }}</b></p>


                    <div >
                        <template  v-for="(filed, index) in state.lines[0]" :key="'l1-' + index">
                            <span v-if="index == 'line_1_position_1'" class="field-1 p-0 m-0">{{ filed }}</span><span  v-else class="field-2 p-0 m-0" ><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                        </template>
                        <span  class="field-11 p-0 m-0"></span>
                    </div>

                    <div >
                        <template   v-for="(filed, index) in state.lines[1]"  :key="'l2-' + index">
                            <span v-if="index == 'line_2_position_1'"class="field-1 p-0 m-0">{{ filed }}</span><span v-else class="field-4 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                        </template>
                        <span  class="field-11 p-0 m-0"></span>
                    </div>

                    <div >
                        <template  v-for="(filed, index) in state.lines[2]"  :key="'l3-' + index">
                            <span v-if="index == 'line_3_position_1'" class="field-1 p-0 m-0">{{ filed }}</span><span v-else class="field-6 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                        </template>
                        <span  class="field-11 p-0 m-0"></span>
                    </div>

                    <div >
                        <template  v-for="(filed, index) in state.lines[3]" :key="'l4-' + index">
                            <span v-if="index == 'line_4_position_1'" class="field-1 p-0 m-0">{{ filed }}</span><span v-else class="field-8 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 5000; background-color: white;"></i></span>
                        </template>
                        <span  class="field-11 p-0 m-0"></span>
                    </div>

                    <div>
                        <template  v-for="(filed, index) in state.lines[4]"  :key="'l5-' + index">
                            <span v-if="index == 'line_5_position_1'" class="field-1 p-0 m-0">{{ filed}}</span><span v-else class="field-10 p-0 m-0"><i v-show="filed" :class="'bi bi-'+filed+'-circle-fill'" style="z-index: 1000; background-color: white;"></i></span>
                        </template>
                        <span  class="field-11 p-0 m-0"></span>
                    </div>
                        
                </div>
            </div>
        </div>
    </div>

    <template v-if="chords">
        <div class="container pt-20px" style="background-color: white;">
            <h1 class="text-center mt-5">Lista de acordes</h1>
            <chords-list-wrap :token_crsf="token_crsf" :chords="chords" :delete="true" @deleteSuccess="deleteChord"></chords-list-wrap>

    </div>

    </template>
</template>


<script setup>
import { reactive, onMounted, ref, getCurrentInstance } from 'vue';
import { useChord } from '@/store/chord.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf']);
const chords = ref(false);
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const chordStore = useChord();
const page = urls.api+'chord';
const { proxy } = getCurrentInstance();
const config = {
   headers: {
       'Content-Type': 'multipart/form-data',
       'Accept': 'application/json',
   }
};

const messageSweet = ((text, type ) => {
	proxy.$swal.fire({
		title: text ,
		icon: type,

    });
});

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


const state = reactive({
  title: '',
  lines: [
    { line_1_position_1: '', line_1_position_2: '', line_1_position_3: '', line_1_position_4: '', line_1_position_5: '' },
    { line_2_position_1: '', line_2_position_2: '', line_2_position_3: '', line_2_position_4: '', line_2_position_5: '' },
    { line_3_position_1: '', line_3_position_2: '', line_3_position_3: '', line_3_position_4: '', line_3_position_5: '' },
    { line_4_position_1: '', line_4_position_2: '', line_4_position_3: '', line_4_position_4: '', line_4_position_5: '' },
    { line_5_position_1: '', line_5_position_2: '', line_5_position_3: '', line_5_position_4: '', line_5_position_5: '' }
  ]
});

const submit = (() => {
   const fields = {
        _method:'POST',
        chord_name : state.title,
        chord_positions : state.lines,
    }
    return chordStore.insert(page, fields, config)
    .then((response) => {
        if(response.request.status === 200 || response.request.status === 201 ){
            messageSweet( 'Novo acorde inserido','success');
            execute();
        }
    })
    .catch((e) => {
        returnCath(e);
    })
    .finally(() => isLoading.value = false);
});

const deleteChord = (() => {
    messageSweet( 'Acorde apagado','success');
    execute();
});

const execute = (async () => {

    isLoading.value = true;
    return await chordStore.get(page, config)
    .then( response => {
        chords.value = response.data.data;
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

<style scoped>
.acorde{
    display: flex;
}
.chord-content {
    margin: 10px 10px;
    font-family: monospace;
}

.field-2,
.field-4,
.field-6,
.field-8,
.field-11,
.field-10 {
    width: 1.2em;
    height: 1.8em;
    font-weight: normal;
    font-family: sans-serif;
    font-size: 1em;
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    position: relative;
    background-color: transparent;
    transition: background-color 0.3s ease;
}

.field-2::before,
.field-4::before,
.field-6::before,
.field-8::before,
.field-10::before {
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
.field-2 i,
.field-4 i,
.field-6 i,
.field-8 i,
.field-10 i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Centraliza o ícone em relação à linha */
    z-index: 1; /* Certifica que o ícone fica sobre a linha */
    pointer-events: none; /* Impede que o ícone interfira com a interação do usuário com outros elementos */
    padding: 1px;
    
}
.field-1,
.field-3,
.field-5,
.field-7,
.field-9 {
    width: 1.2em;
    height: 1.8em;
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

.field-2 {
    border: none;
    border-radius: 0px;
    border-top: #b18989 2px solid;
}

.field-4,
.field-6,
.field-8 {
    border: none;
    border-radius: 0px;
    border-top: #b18989 1px solid;
}

.field-10 {
    border: none;
    border-radius: 0px;
    border-top: #b18989 1px solid;
    border-bottom: #b18989 2px solid;
}

.field-1 {
    border-radius: 0px;
    border-right: #b18989 2px solid;
}

.field-11 {
    border-left: #b18989 2px solid;
}
input{
    background-color: white;
}
input:focus{
    background-color: rgb(245, 255, 236);
}
</style>