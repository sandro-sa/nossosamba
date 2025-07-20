<template>
    <div class="container">

        <template v-if="props.musics">
            <template v-for="music in musics" class="list-group">
                <div class="velocidade 350 D2">
                    <p class="titulo m-0 p-0 ">##{{ music.singer.singer_name }} | {{ music.music_name }} ##</p>
                    <p class="m-0 p-0">Introdução: {{ music.introduction }}</p>
                    <div class="music m-0 p-0" v-html="music.music"></div>
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
</style>
