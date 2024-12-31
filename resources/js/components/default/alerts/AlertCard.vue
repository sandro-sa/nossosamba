<template>
    <div class="mtb-div-alert">
        <div :class="typeAlert"  class="alert text-center" role="alert" > 
           {{message}} 
        
        </div>
    </div>
 
</template>
<script setup>

const props = defineProps(['message','typeAlert'])

</script>
<style scope>
    .mtb-div-alert {
        z-index: 8; /* Aumentei o z-index para que fique acima da div de loading */
        position: fixed;
        top: 80px;
        right: 35px; /* Posiciona a div mtb-div-alert no canto superior direito */
        display: flex;
        justify-content: flex-end; /* Alinha o conteúdo à direita */
    }

     .alert {
        border-radius: 0px;
        white-space: normal;
        min-width: 350px;
    
    }

    @media screen and (max-width: 992px) {
        .mtb-div-alert {
            top: 80px;
            right: 20Px; 
            display: flex;
            justify-content: center; /* Alinha o conteúdo à direita */
        }
    }
  
  
</style>
