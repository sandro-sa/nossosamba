<?php

trait TraitAchaPosicaoPosicaoDaLinhaNumeroDacoluna{

    public static function acharPosicaoPosicaoDaLinhaNumeroDacoluna($chord){
        $posicao = null;
        $numeroDacoluna = 0;
        $posicaoLinha = null;
        foreach($chord as $coluna => $linhas){
            $aux = 0;
            foreach($linhas as $linha => $linha_valor){
                if($aux == 0 && $linha_valor != null){
                    $posicao = $linha_valor;
                    $posicaoLinha = $numeroDacoluna;
                }
                $aux++;
            }
            $numeroDacoluna++;
        }

        return [$posicao, $posicaoLinha, $numeroDacoluna];
    }
}