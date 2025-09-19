<template>
    <alert-loading :msg="msg" :isLoading="isLoading" :alert="alert"></alert-loading>
    <div class="container">
        <singer-update
            v-if="update"
            :editSinger="editSinger"
            :token_crsf="token_crsf"
            @sendMessageDad="messages"
            @excuteLoadind="isLoading = !isLoading"
            @execute="execute"
        />
        <singer-create
            v-else
            :token_crsf="token_crsf"
            @sendMessageDad="messages"
            @excuteLoadind="isLoading = !isLoading"
            @execute="execute"
        />

        <div v-if="singers" class="row">
            <div class="col">
                <h2 class="text-start mt-3">Músicos/Grupos</h2>

                <!-- Campo de pesquisa -->
                <input
                    type="text"
                    class="form-control mb-3"
                    placeholder="Pesquisar por nome..."
                    v-model="searchQuery"
                />

                <table class="table table-bordered table-light table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Controle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="singer in filteredSingers" :key="singer.id">
                            <tr>
                                <th scope="row">{{ singer.id }}</th>
                                <td>{{ singer.singer_name }}</td>
                                <td>{{ singer.singer_type ? 'Grupo' : 'Solo' }}</td>
                                <td>
                                    <button class="btn me-3" title="Editar" @click="updateSinger(singer)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a :href="urls.url + 'lista/musicas/' + singer.id">
                                        <button class="btn" title="Musicas">
                                            <i class="bi bi-music-note-list"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        </template>
                        <tr v-if="filteredSingers.length === 0">
                            <td colspan="4" class="text-center">Nenhum resultado encontrado.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { useSinger } from '@/store/singer.js';
import { catchDefault } from '@/utils/messagesCatch';
import urls from '@/utils/urls';

const props = defineProps(['token_crsf']);

const singers = ref([]);
const editSinger = ref(false);
const update = ref(false);
const isLoading = ref(false);
const alert = ref(false);
const msg = ref(false);
const searchQuery = ref(''); // Campo de pesquisa

const singerStore = useSinger();
const page = urls.api + 'singer';

const config = {
    headers: {
        'Content-Type': 'multipart/form-data',
        'Accept': 'application/json',
    }
};

const messages = (text, type) => {
    msg.value = text;
    alert.value = type;
    setTimeout(() => {
        resetMessages();
    }, 2000);
};

const resetMessages = () => {
    msg.value = false;
    alert.value = false;
};

const updateSinger = (singer) => {
    isLoading.value = true;
    editSinger.value = singer;
    update.value = false;

    // Rola para o topo da página
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

    setTimeout(() => {
        editSinger.value = singer;
        update.value = true;
        isLoading.value = false;
    }, 1000);
};

const execute = async () => {
    isLoading.value = true;
    try {
        const response = await singerStore.get(page, config);
        singers.value = response.data.data;
        update.value = false;
    } catch (e) {
        returnCath(e);
    } finally {
        isLoading.value = false;
    }
};

const returnCath = (e) => {
    const retornCatch = catchDefault(e);
    messages(retornCatch[0], retornCatch[1]);
};

// Computed: Filtrar cantores pelo nome (case insensitive)
const filteredSingers = computed(() => {
    if (!searchQuery.value.trim()) return singers.value;
    return singers.value.filter(singer =>
        singer.singer_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

onMounted(() => execute());
</script>

<style>
.btn-success {
    background-color: #508570;
    border-radius: 0px;
}
</style>
