<?php


trait TraitFixaAcordeNoInicioDaEscala{

    use TraitPreencheAcordeQueFaltam;

    public static function fixarAcordeNoIncioDaEscala($acorde, $tamanhoDoAcorde, $posicaoDoNovoAcorde){
       
        $coluna_que_inicia_o_acorde =  6 - $tamanhoDoAcorde ;
        $novo_acorde = [];
        $nova_coluna = 0;
        $coluna_onde_inicia_o_acorde_esta_preenchida = false;
         foreach ($acorde as $key => $coluna_cordas) {
             $coluna_iniciada = false;
             $numero_da_corda = 0;
             foreach ($coluna_cordas as $key => $corda) {
 
                 if($coluna_que_inicia_o_acorde <=5){
                     
                     if(!$coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda == 0 && $corda != null){
                         $novo_acorde[$coluna_que_inicia_o_acorde]["coluna_".$coluna_que_inicia_o_acorde."_corda_".$numero_da_corda] = $posicaoDoNovoAcorde;
                         $coluna_onde_inicia_o_acorde_esta_preenchida = true;
                         $coluna_iniciada = true;
                     }
                     if($coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda != 0){
                         $novo_acorde[$coluna_que_inicia_o_acorde]["coluna_".$coluna_que_inicia_o_acorde."_corda_".$numero_da_corda] = $corda;
                         $coluna_iniciada= true;
                     }
                 }
 
                 $numero_da_corda++;
             }
             if( $coluna_iniciada){
                 $coluna_que_inicia_o_acorde++;
             }
             $nova_coluna++;
         }
         
         $novo_acorde = TraitFixaAcordeNoInicioDaEscala::preencheColuanaQueFaltam($novo_acorde);
         return $novo_acorde;
    }
}