<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
    <div class="container">
        <div class="row">
            <rhythm-update v-if="update" :editRhythm="editRhythm" :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></rhythm-update>
            <rhythm-create v-else :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></rhythm-create>
        </div>
        <div class="row">
            <div class="col">
                <h2 class="text-start mt-3">Ritmos</h2>
            <table class="table table-bordered table-info table-hover">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ritmo</th>
                    <th scope="col">Tempo</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for=" rhythm in rhythms" :key="rhythm.id">
                    <tr @click="updateRhythm(rhythm)">
                    <th scope="row">{{ rhythm.id }}</th>
                    <td>{{ rhythm.rhythm }}</td>
                    <td>{{ rhythm.time }}</td>
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
import { useRhythms } from '@/store/rhythms.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf']);
const editRhythm = ref(false);
const rhythms = ref(false);
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const update = ref(false);
const rhythmStore = useRhythms();
const page = urls.api+'rhythm';
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


const updateRhythm = ((singer) =>{
  isLoading.value = true;
  editRhythm.value = singer;
  update.value = false;
  setTimeout(() => {
    editRhythm.value = singer;
    update.value = true;
    isLoading.value = false;
  },1000)
});

const execute = (async () => {
    isLoading.value = true;
    return await rhythmStore.get(page, config)
    .then( response => {
        rhythms.value = response.data.data;
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