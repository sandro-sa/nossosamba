<template>
    <div class="container">
        <template v-if="props.musics">
            <template v-for="music in musics">
                <div class="velocidade">
                    <p class="titulo m-0 p-0 ">##{{ music.music_name }} ##</p>
                    <p class="titulo m-0 p-0 ">##{{ music.singer.singer_name }} </p>
                    <p class="m-0 p-0">Introdução: {{ music.introduction }}</p>
                    <span class="music m-0 p-0" v-html="music.music"></span>
                    <hr>
                </div>
            </template>
        </template>
    </div>  

    <div class="fixed-footer-buttons d-flex justify-content-end">
        <button class="btn btn-sm btn-dark mt-2 me-4" :musics="musics" type="button" @click="$emit('back')">Voltar</button>
        <button class="" id="nextMusic" @click="scrollToVelocidade()"><i class="bi bi-caret-right-fill"></i></button>
    </div>
</template>

<script setup>

const props = defineProps(['token_crsf', 'musics']);





const scrollToVelocidade = () => {
   
    const velocidades = Array.from(document.querySelectorAll('.velocidade'));
    let closestIndex = -1;
    let closestDistance = Infinity;

    velocidades.forEach((element, index) => {
        const rect = element.getBoundingClientRect();
        const distanceFromTop = Math.abs(rect.top);
        if (distanceFromTop < closestDistance) {
            closestDistance = distanceFromTop;
            closestIndex = index;
        }
    });

    const nextIndex = closestIndex + 1;
    if (nextIndex < velocidades.length) {
        const nextElement = velocidades[nextIndex];
        nextElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}


</script>
<style scoped>
.container-fluid{
    margin: 0px;
    padding: 0px;
}
    .fixed-footer-buttons {
        position: fixed; /* Fixa no rodapé da tela */
        bottom: 0; /* Alinha ao fundo da tela */
        left: 0; /* Fixa na lateral esquerda */
        right: 0; /* Fixa na lateral direita */
        z-index: 9999; /* Garante que os botões fiquem sobre outros elementos */
        padding: 10px 20px; /* Adiciona algum espaçamento */
     
    }

    .fixed-footer-buttons .btn {
        margin: 0 5px;
    }
     .music {
        word-wrap: break-word; /* Força a quebra de palavra quando ela ultrapassar a largura */
        white-space: normal;   /* Garante que o texto possa quebrar normalmente */
        display: block;        /* Faz o span se comportar como um bloco, garantindo a quebra de linha */
        width: 100%;            /* Garante que o conteúdo ocupe 100% da largura disponível */
        word-break: break-word; /* Quebra a palavra longa se necessário */
    }
</style>
