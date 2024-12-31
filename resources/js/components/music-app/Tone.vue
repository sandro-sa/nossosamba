<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
    <div class="container">
        <tone-update v-if="update" :editTone="editTone" :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></tone-update>
        <tone-create v-else :token_crsf="token_crsf" @sendMessageDad="messages"  @excuteLoadind="isLoading = !isLoading" @execute="execute"></tone-create>
        <div class="row">
            <div class="col">
                <h2 class="text-start mt-3">Tons</h2>
            <table class="table table-bordered table-info table-hover">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tom</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for=" tone in tones" :key="tone.id">
                    <tr @click="updateTone(tone)">
                    <th scope="row">{{ tone.id }}</th>
                    <td>{{ tone.tone }}</td>
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
import { useTone } from '@/store/tone.js';
import{ catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';
const props = defineProps(['token_crsf']);
const tones = ref(false);
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const id = ref(null);
const editTone = ref(null);
const update = ref(false);
const toneStore = useTone();
const page = urls.api+'tone';
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

const updateTone = ((singer) =>{
  isLoading.value = true;
  editTone.value = singer;
  update.value = false;
  setTimeout(() => {
    editTone.value = singer;
    update.value = true;
    isLoading.value = false;
  },1000)
});


const execute = (async () => {
    isLoading.value = true;
    return await toneStore.get(page, config)
    .then( response => {
        tones.value = response.data.data;
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