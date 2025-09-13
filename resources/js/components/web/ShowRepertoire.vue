<template>

    <div class="container-fluid">

        <div class="d-flex justify-content-end">

            <button @click="openFullScreen" class="btn btn-dark btn-sm mt-2 me-2">Tela cheia</button>

            <button @click="toggleAcordes" class="btn btn-dark btn-sm mt-2 me-2"> 

                {{ acordesVisiveis ? 'Mostrar' : 'Ocultar' }}   <i class="bi bi-music-note-list"></i>

            </button>

           <button @click="hideAcordes = !hideAcordes" class="btn btn-dark btn-sm mt-2">
                <template v-if="hideAcordes">

                    Negrito <i class="bi bi-eye-fill"></i>
                </template>

                <template v-else>

                    Negrito <i class="bi bi-eye-slash-fill"></i>
                </template>

            </button>
        </div>

        <template v-if="props.musics">

            <template v-for="music in musics">

                <div class="velocidade">

                    <p class="titulo m-0 p-0 " style="font-size: 0.7em;">##{{ introducao(music[0].music_name,music[1], music[2]) }} ##</p>

                    <p class="titulo m-0 p-0 " style="font-size: 0.7em;">##{{ music[0].singer.singer_name }} </p>

                    <p class="m-0 p-0" style="font-size: 0.7em;">Introdução: {{ music[0].introduction }}</p>

                    <span 
                        class="music m-0 p-0"
                        :class="{ 'hide-acordes': hideAcordes, 'music-estilo-extra': hideAcordes }"
                        v-html="letra(music[0].music, music[1], music[2])"
                        >
                        
                    </span>

                    <hr>
                </div>
            </template>

        </template>

    </div>  

    <div class="fixed-footer-buttons d-flex justify-content-end">
        <button class="" id="nextMusic" @click="scrollToVelocidade()"><i class="bi bi-caret-right-fill"></i></button>
    </div>

</template>

<script setup>
import { ref } from 'vue';
const props = defineProps(['token_crsf', 'musics']);
const hideAcordes = ref(false);

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

const openFullScreen = () => {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) { // Para Firefox
        document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) { // Para Chrome, Safari e Opera
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) { // Para Internet Explorer/Edge
        document.documentElement.msRequestFullscreen();
    }
}

const letra = (( music, mudou_tom,acordes) => {

    if (mudou_tom) {
    
        const alterarAcordes = (textoHtml, listaDeNovosAcordes) => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(textoHtml, 'text/html');
            const acordes = doc.querySelectorAll('span.acorde');

            acordes.forEach((acordeSpan) => {
                const acordeOriginal = acordeSpan.textContent.trim();
                if (listaDeNovosAcordes[acordeOriginal]) {
                    acordeSpan.textContent = listaDeNovosAcordes[acordeOriginal];
                }
            });
            return doc.body.innerHTML;
        };
        const textoHtmlAlterado = alterarAcordes(music, acordes);
        return textoHtmlAlterado;
    } else {
        return music;
    }
});


const introducao = ((introducao, mudou_tom,acordes) => {
    if (mudou_tom) {
  
        const alterarAcordes = (texto, listaDeNovosAcordes) => {
            const regex = /\b([A-Ga-g#]+[0-9]?[a-zA-Z]*)\b/g;
            return texto.replace(regex, (match) => {
                return listaDeNovosAcordes[match] || match;
            });
        };
        const novaIntroducao = alterarAcordes(introducao, acordes);

        return novaIntroducao;
    } else {
        return introducao;
    }
});

const acordesVisiveis = ref(true); // estado global


const toggleAcordes = () => {
  acordesVisiveis.value = !acordesVisiveis.value;

  document.querySelectorAll('p').forEach((p) => {
    const acordes = p.querySelectorAll('.acorde');

    if (acordes.length > 0) {
      if (acordesVisiveis.value) {
        acordes.forEach((acorde) => acorde.style.display = '');
        // Mostrar novamente spans extras
        p.querySelectorAll('.acorde-extra').forEach(e => e.style.display = '');
        p.style.display = '';
      } else {
        acordes.forEach((acorde) => acorde.style.display = 'none');

        // Esconde símbolos "^" fora dos spans
        p.innerHTML = p.innerHTML.replace(/\^/g, '<span class="acorde-extra" style="display:none">^</span>');

        if (p.innerText.trim() === '') {
          p.style.display = 'none';
        }
      }
    }
  });
};

</script>
<style scoped>
.container-fluid{

    background-color: #fff;
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

    .mt-1{
        margin-top: .05rem !important;
    }

    p{
        margin: 0px;
        padding: 0px;
    }
    /* quando acordes estão ocultos */
    .hide-acordes .acorde {
        display: none !important;
    }

    /* estilo adicional aplicado quando acordes estão ocultos */
    .music-estilo-extra {
        font-family: "Verdana", sans-serif !important;
        font-weight: bold !important;
    }
    
</style>