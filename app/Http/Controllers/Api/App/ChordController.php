<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Chord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Resources\Api\Music\ChordResource;
use App\Http\Controllers\Api\App\ChordService\RetornaListaDeAcordes;
use App\Http\Controllers\Api\App\ChordService\RetornaTamanhoDoAcorde;
use App\Http\Controllers\Api\App\ChordService\PreencheTrastesQueFaltam;
use App\Http\Controllers\Api\App\ChordService\RetornaAcordaNaPosicaoCerta;
use App\Http\Controllers\Api\App\ChordService\RetornaColunaOndeOhAcordeComeca;
use App\Http\Controllers\Api\App\ChordService\RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde;
use App\Http\Controllers\Api\App\ChordService\RetornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta;



class ChordController extends Controller{

    public function index()
    {
        $chords = Chord::orderBy('chord_name', 'asc')->get();
        return ChordResource::collection($chords);
    }
    public function store(Request $request)
    {
        $fields = $request->all();

        $nomeDoAcorde = $fields['chord_name'];
        $acorde = $fields['chord_positions'];
        $tamanhoDoAcorde = RetornaTamanhoDoAcorde::retornaTamanhoDoAcorde($acorde);
        $posicaoOndeComecaArcorde = RetornaColunaOndeOhAcordeComeca::retornaColunaOndeOhAcordeComeca($acorde);

        if($posicaoOndeComecaArcorde == -1 ){
            throw new MusicException('Erro na posição onde o acorde inicia');
        }

        $acordeEstaNaMesmaPosicaoDaTraste = RetornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta::retornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta($acorde);

        if(!$acordeEstaNaMesmaPosicaoDaTraste && $posicaoOndeComecaArcorde < 5){
            $acorde = RetornaAcordaNaPosicaoCerta::retornaAcordaNaPosicaoCerta($acorde);
            $acorde = PreencheTrastesQueFaltam::preencheTrastesQueFaltam($acorde);
        }

        $listaComNomeEhPosicaoDoAcorde = RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde::retornaListaDeNomesEhPosicaoOndeInciaOhAcorde($posicaoOndeComecaArcorde, $nomeDoAcorde);

       
        $listaDeAcordes = RetornaListaDeAcordes::retornaListaDeAcordes($listaComNomeEhPosicaoDoAcorde, $acorde, $tamanhoDoAcorde);


        $sucesso = 0;
        foreach($listaDeAcordes as $chave => $acorde){
           $resposta = ChordController::storeAcorde($acorde);
           if($resposta){
                $sucesso++;
            }
        }

        if($sucesso == 0){
            throw new MusicException('Acorde já existe');
        }
    
        return response()->json(["data"=>"sucess","message"=>$sucesso],201);

        // $posicao_onde_comeca_acorde = ChordController::retornaColunaOndeOhAcordeComeca($fields);
        // $lista_de_acordes = ChordController::retornaListaDeNomesEHposicaoOndeInciaOhAcorde($posicao_onde_comeca_acorde, $nomeDoAcorde);
        // $array_com_acordes = $this->generateListChords($fields, $lista_de_acordes, $posicao_onde_comeca_acorde);
    
        //dd( $acorde,  $lista_de_acordes, $posicao_onde_comeca_acorde );
      
        // foreach($lista_de_acordes as $chave => $valor){
        //    if($chave == $acorde){
        //         if(){
        //         }
        //    }
        // }
        // if ($chord instanceof Chord) {
        //     $existingPositions = json_decode($chord->chord_positions, true);
        //     $newPosition = $fields['chord_positions'];
        //     foreach ($existingPositions as $position) {
        //         if ($position == $newPosition) {
        //             throw new MusicException('As posições do acorde já existem.');
        //         }
        //     }
            
        //     array_push($existingPositions,$newPosition);
        //     $chord->chord_positions = json_encode($existingPositions);
        //     $chord->save();
        //     return new ChordResource($chord);
        // } else {
        //     $fields['chord_positions'] = json_encode([$fields['chord_positions']]);
        //     $newChord = Chord::create($fields);
        //     return new ChordResource($newChord);
        // }
        
    }
    // private function getChordPositions($fields){

    //     $coluna_0 = $fields['chord_positions'][0]['coluna_0_corda_0'] ;
    //     $coluna_1 = $fields['chord_positions'][1]['coluna_1_corda_0'] ;
    //     $coluna_2 = $fields['chord_positions'][2]['coluna_2_corda_0'] ;
    //     $coluna_3 = $fields['chord_positions'][3]['coluna_3_corda_0'] ;
    //     $coluna_4 = $fields['chord_positions'][4]['coluna_4_corda_0'] ;
    //     $coluna_5 = $fields['chord_positions'][5]['coluna_5_corda_0'] ;

    //     if($coluna_0 == null && $coluna_1 == null && $coluna_2 == null && $coluna_3 == null && $coluna_4 == null && $coluna_5 == null){
    //         throw new MusicException('Indicar a posição onde o arcode inicia');
    //     }
    //     if( $coluna_0 == null &&
    //         $coluna_1 == null &&
    //         $coluna_2 == null &&
    //         $coluna_3 == null &&
    //         $coluna_4 == null &&
    //         is_numeric($coluna_5))
    //     {
    //         $coluna = $coluna_5;
    //     }elseif(
    //         $coluna_0 == null &&
    //         $coluna_1 == null &&
    //         $coluna_2 == null &&
    //         $coluna_3 == null &&
    //         $coluna_5 == null &&
    //         is_numeric($coluna_4))
    //     {
    //         $coluna = $coluna_4;
    //     }elseif(
    //         $coluna_0 == null &&
    //         $coluna_1 == null &&
    //         $coluna_2 == null &&
    //         $coluna_4 == null &&
    //         $coluna_5 == null &&
    //         is_numeric($coluna_3))
    //     {
    //         $coluna = $coluna_3;
    //     }elseif(
    //         $coluna_0 == null &&
    //         $coluna_1 == null &&
    //         $coluna_3 == null &&
    //         $coluna_4 == null &&
    //         $coluna_5 == null &&
    //         is_numeric($coluna_2))
    //     {
    //         $coluna = $coluna_2;
    //     }elseif(
    //         $coluna_0 == null &&
    //         $coluna_2 == null &&
    //         $coluna_3 == null &&
    //         $coluna_4 == null &&
    //         $coluna_5 == null &&
    //         is_numeric($coluna_1))
    //     {
    //         $coluna = $coluna_1;
    //     }elseif(
    //         $coluna_1 == null &&
    //         $coluna_2 == null &&
    //         $coluna_3 == null &&
    //         $coluna_4 == null &&
    //         $coluna_5 == null &&
    //         is_numeric($coluna_0))
    //     {
    //         $coluna = $coluna_0;
    //     }else{
    //         throw new MusicException('Exiten duas posições no braço');
    //     }
    //     if($coluna < 0 || $coluna > 12){
    //         throw new MusicException('Posição no braço inválida');
    //     }
    //     return (int)$coluna;
    // }

    // private function generateListChordsNames( $posicao_onde_comeca_acorde,$acorde ){
    //     $escala = [
    //         "C" => 1, "C#" =>2,
    //         "Db" => 2, "D" => 3, "D#" => 4,
    //         "Eb" =>4 , "E" => 5, "F" =>6,
    //         "F#" => 7 , "Gb" => 7, "G" => 8,
    //         "G#" => 9, "Ab" => 9 , "A" => 10 ,
    //         "A#" => 11, "Bb" => 11 , "B" => 12
    //     ];
    
    //     $tom = substr($acorde, 1);
    //     $lista_de_acordes = [];
    //     $nova__lista_de_acordes = [];
    //     foreach ($escala as $chave => $valor) {
    //         $novo_acorde = $chave . $tom;
    //         $lista_de_acordes[$novo_acorde] = $valor;
    //     }

    //     $numero = 0;
    //     foreach ($lista_de_acordes as $chave => $valor) {
    //         if($acorde == $chave){
    //            $numero =  $posicao_onde_comeca_acorde - $valor;
    //         }
    //     }

    //     foreach ($lista_de_acordes as $chave => $valor) {
    //         $novo_acorde = $chave;
    //         $nova_chave = $valor + ($numero);
    //         if($nova_chave > 12){
    //             $nova__lista_de_acordes[$novo_acorde] = $nova_chave - 12;
    //         }elseif($nova_chave < 0){
    //             $nova__lista_de_acordes[$novo_acorde] = $nova_chave + 12;
    //         }else{
    //             $nova__lista_de_acordes[$novo_acorde] = $nova_chave;
    //         }
            
    //     }
    //     return $nova__lista_de_acordes;

    // }

    // private function generateListChords( $fields, $lista_de_acordes, $posicao_onde_comeca_acorde){
       
      
    //     $novos_acordes = [];
    //     foreach($lista_de_acordes as $chave => $posicaoDoNovoAcorde){
    //         $coluna = 0;
    //         $finalDoAcorde = 0;
    //         $incioDoAcorde= null;
    //         $pestanaMenorQue5 = false;
    //         $pestanaMenorQue5ForaDaPosicao = false;
    //         $cordas_pressionadas = [] ;
           
    //         foreach($fields['chord_positions'] as $chave2 => $coluna_corda){
           
    //             if($coluna <= 5){
                        
    //                 if(( $coluna_corda["coluna_".$coluna."_corda_0"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_1"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_2"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_3"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_4"] != null)  && ($posicaoDoNovoAcorde <= 5 ) && ($coluna == $posicaoDoNovoAcorde)
    //                         )
    //                     {
    //                         $pestanaMenorQue5 = true;
    //                     }
    //                     elseif(( $coluna_corda["coluna_".$coluna."_corda_0"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_1"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_2"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_3"] != null &&
    //                         $coluna_corda["coluna_".$coluna."_corda_4"] != null) && ($posicaoDoNovoAcorde <= 5) && ($coluna != $posicaoDoNovoAcorde)
    //                     ){
    //                         //Pestana menor que 5 mais fora da posição
    //                         $pestanaMenorQue5ForaDaPosicao = true;
            
    //                     }
    //                     elseif(
    //                         $coluna_corda["coluna_".$coluna."_corda_0"] != null &&
    //                     ($coluna_corda["coluna_".$coluna."_corda_1"] != null ||
    //                         $coluna_corda["coluna_".$coluna."_corda_2"] != null ||
    //                         $coluna_corda["coluna_".$coluna."_corda_3"] != null ||
    //                         $coluna_corda["coluna_".$coluna."_corda_4"])
    //                     ){
    //                         //Onde começa o acorde
    //                         $incioDoAcorde = $coluna;
    //                         $finalDoAcorde = $coluna;
    //                         if( $coluna_corda["coluna_".$coluna."_corda_1"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_1"] = 1;
    //                         }
    //                         if( $coluna_corda["coluna_".$coluna."_corda_2"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_2"] = 2;
    //                         }
    //                         if( $coluna_corda["coluna_".$coluna."_corda_3"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_3"] = 3;
    //                         }
    //                         if( $coluna_corda["coluna_".$coluna."_corda_4"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_4"] = 4;
    //                         }
                        
                    
    //                 }elseif(
    //                         $coluna_corda["coluna_".$coluna."_corda_0"] == null &&
    //                         (
    //                             $coluna_corda["coluna_".$coluna."_corda_1"] != null ||
    //                             $coluna_corda["coluna_".$coluna."_corda_2"]  != null ||
    //                             $coluna_corda["coluna_".$coluna."_corda_3"]  != null ||
    //                             $coluna_corda["coluna_".$coluna."_corda_4"]
    //                         )
    //                     ){
    //                         if( $coluna_corda["coluna_".$coluna."_corda_1"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_1"] = 1;
    //                         }
    //                         if( $coluna_corda["coluna_".$coluna."_corda_2"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_2"] = 2;
    //                         }
    //                         if( $coluna_corda["coluna_".$coluna."_corda_3"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_3"] = 3;
    //                         }
    //                         if( $coluna_corda["coluna_".$coluna."_corda_4"] != null){
    //                             $cordas_pressionadas["coluna_".$coluna."_corda_4"] = 4;
    //                         }
                        
    //                         $finalDoAcorde = $coluna;
    //                     }
    //             }
    //             $coluna++;
    //         }

        
    //         $finalDoNovoAcorde = $finalDoAcorde - $incioDoAcorde + $posicaoDoNovoAcorde ;
    //         $tamanhoDoAcorde = ($finalDoAcorde - $incioDoAcorde ) +1;
            
    //         if($pestanaMenorQue5){
    //             //dd("1",$chave,$novos_acordes);
    //             $novos_acordes[$chave] = ["chord_positions" => $fields['chord_positions']];
    //         }elseif($pestanaMenorQue5ForaDaPosicao){
    //             //dd("2",$chave);
    //             $novos_acordes[$chave] = ["chord_positions" => ChordController::retornarAcorde($fields['chord_positions'], $posicaoDoNovoAcorde, $posicao_onde_comeca_acorde, $cordas_pressionadas)];
    //             //dd( $novos_acordes);
    //         }elseif($finalDoNovoAcorde <= 5  ){
    //             //dd("3");
    //             $novos_acordes[$chave] = ["chord_positions" => ChordController::retornarAcorde($fields['chord_positions'], $posicaoDoNovoAcorde, $posicao_onde_comeca_acorde, $cordas_pressionadas)];
             
    //         }
    //         elseif($finalDoAcorde == $incioDoAcorde && $posicaoDoNovoAcorde <= 5){
    //             //dd("4",$chave);
    //             $novos_acordes[$chave] = ["chord_positions" => ChordController::retornarAcorde($fields['chord_positions'], $posicaoDoNovoAcorde, $posicao_onde_comeca_acorde, $cordas_pressionadas)];
           
    //         }elseif($finalDoAcorde == $incioDoAcorde && $posicaoDoNovoAcorde > 5){
    //            //dd("5",$chave);
    //             $novos_acordes[$chave] =  $this->fixarAcordeNoIncioDaEscala($fields['chord_positions'],$tamanhoDoAcorde, $posicaoDoNovoAcorde);
              
    //         }elseif($finalDoNovoAcorde > 5){
    //             //dd("6");
    //             $novos_acordes[$chave] =  $this->fixarAcordeNoIncioDaEscala($fields['chord_positions'],$tamanhoDoAcorde, $posicaoDoNovoAcorde);
    //         }
    //         else{
    //             dd("ok-1",$novos_acordes);
    //             //$fields['chord_positions'][$posicao_onde_comeca_acorde]["coluna_".$posicao_onde_comeca_acorde."_corda_1"] = $posicaoDoNovoAcorde ;
    //             //$novos_acordes[$chave] = ["chord_positions" => $fields['chord_positions']];
                
    //         }

    //     }

    //     dd($novos_acordes);
    //     return $novos_acordes;
    // }

    // private function retornarAcorde($chord, $posicaoDoNovoAcorde, $posicao_onde_comeca_acorde, $cordas_pressionadas){
        
    //     $posicao = null;
    //     $numeroDacoluna = 0;
    //     $posicaoLinha = null;
    //     foreach($chord as $coluna => $linhas){
    //         $aux = 0;
    //         foreach($linhas as $linha => $linha_valor){
    //             if($aux == 0 && $linha_valor != null){
    //                 $posicao = $linha_valor;
    //                 $posicaoLinha = $numeroDacoluna;
    //             }
    //             $aux++;
    //         }
    //         $numeroDacoluna++;
    //     }

        
       
    //     if(($posicao == $posicaoLinha) && ($posicao == $posicaoDoNovoAcorde) && (count($cordas_pressionadas) == 4)){
    //         $chord = $this->preencheColuanaQueFaltam($chord);
    //         return $chord;

    //     }
    //     else{
           
    //         if($posicao_onde_comeca_acorde < $posicaoDoNovoAcorde){
    //             $novo_acorde = [];
    //             $colunaDoNovoAcorde = $posicaoDoNovoAcorde;
    //             $coluna_onde_inicia_o_acorde_esta_preenchida = false;
    //             $existe_coluna = false;
    //             foreach($chord as $coluna => $cordas){
    //                 $numero_da_corda = 0;
    //                 foreach ($cordas as $corda => $pressionada){
    //                     if($colunaDoNovoAcorde <= 5){
    //                         if(!$coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda == 0 && $pressionada != null){
    //                             $novo_acorde[$colunaDoNovoAcorde]["coluna_{$colunaDoNovoAcorde}_corda_{$numero_da_corda}"] = $posicaoDoNovoAcorde;
    //                             $coluna_onde_inicia_o_acorde_esta_preenchida = true;
    //                             $existe_coluna = true;
    //                         }
    //                         if($coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda == 0 && $pressionada == null){
    //                             $novo_acorde[$colunaDoNovoAcorde]["coluna_{$colunaDoNovoAcorde}_corda_{$numero_da_corda}"] = null;
    //                             $coluna_onde_inicia_o_acorde_esta_preenchida = true;
    //                             $existe_coluna = true;
    //                         }
    //                         if($coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda != 0 ){
    //                             $novo_acorde[$colunaDoNovoAcorde]["coluna_{$colunaDoNovoAcorde}_corda_{$numero_da_corda}"] = $pressionada;
    //                             $existe_coluna = true;
    //                         }
                        
    //                         $numero_da_corda++;
    //                     }
    //                 }
    //                 if($existe_coluna){

    //                     $colunaDoNovoAcorde++;
    //                 }
    //             }
    //         }else{
    //             $novo_acorde = [];
    //             $coluna = 0;
    //             $coluna_existe = false;
    //             $coluna_onde_inicia_novo_acorde = (int)$posicaoDoNovoAcorde;
    //             foreach($chord as $chave => $valor){
                   
    //                 if($coluna >= (int)$coluna_onde_inicia_novo_acorde){
    //                     $posicao= 0 ;
    //                     $numero_da_corda = 0;
    //                     foreach($valor as $chave2 => $valor2)
    //                     {
    //                         if($coluna == $posicao_onde_comeca_acorde && $posicao == 0){
    //                             $novo_acorde[$coluna_onde_inicia_novo_acorde]["coluna_{$posicaoDoNovoAcorde}_corda_{$numero_da_corda}"] = $posicaoDoNovoAcorde;
    //                             $posicao++;
    //                             $coluna_existe = true;
    //                         }
    //                         if($coluna == $posicao_onde_comeca_acorde && $posicao != 0){
                              
    //                             if($coluna_onde_inicia_novo_acorde <= 5 ){
    //                                 $novo_acorde[$coluna_onde_inicia_novo_acorde]["coluna_{$posicaoDoNovoAcorde}_corda_{$numero_da_corda}"] = $valor2;
                                  
    //                             }
    //                         }else{
                               
    //                             if($coluna_existe && $coluna_onde_inicia_novo_acorde <= 5){
        
    //                                 $novo_acorde[$coluna_onde_inicia_novo_acorde]["coluna_{$coluna_onde_inicia_novo_acorde}_corda_{$numero_da_corda}"] = $valor2;
    //                                 $coluna_existe = true;

    //                             }
    //                         }
    //                         $numero_da_corda++;
    //                     }
    //                     if($coluna_existe){
    //                         $coluna_onde_inicia_novo_acorde++;
    //                     }
    //                 }
                  
    //                 $coluna++;
    //                 $aux++;
    //                 $numeroDacoluna++;
    //             }

                
    //         }

    //         if($posicao_onde_comeca_acorde == 3){
    //             dd($novo_acorde);
    //         }

    //        $novo_acorde = $this->preencheColuanaQueFaltam($novo_acorde);
    //         return $novo_acorde;
    //     }
        
    // }
    
    // private function fixarAcordeNoIncioDaEscala($acorde, $tamanhoDoAcorde, $posicaoDoNovoAcorde){
       
    //    $coluna_que_inicia_o_acorde =  6 - $tamanhoDoAcorde ;
    //    $novo_acorde = [];
    //    $nova_coluna = 0;
    //    $coluna_onde_inicia_o_acorde_esta_preenchida = false;
    //     foreach ($acorde as $key => $coluna_cordas) {
    //         $coluna_iniciada = false;
    //         $numero_da_corda = 0;
    //         foreach ($coluna_cordas as $key => $corda) {

    //             if($coluna_que_inicia_o_acorde <=5){
                    
    //                 if(!$coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda == 0 && $corda != null){
    //                     $novo_acorde[$coluna_que_inicia_o_acorde]["coluna_".$coluna_que_inicia_o_acorde."_corda_".$numero_da_corda] = $posicaoDoNovoAcorde;
    //                     $coluna_onde_inicia_o_acorde_esta_preenchida = true;
    //                     $coluna_iniciada = true;
    //                 }
    //                 if($coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda != 0){
    //                     $novo_acorde[$coluna_que_inicia_o_acorde]["coluna_".$coluna_que_inicia_o_acorde."_corda_".$numero_da_corda] = $corda;
    //                     $coluna_iniciada= true;
    //                 }
    //             }

    //             $numero_da_corda++;
    //         }
    //         if( $coluna_iniciada){
    //             $coluna_que_inicia_o_acorde++;
    //         }
    //         $nova_coluna++;
    //     }
        
    //     $novo_acorde = $this->preencheColuanaQueFaltam($novo_acorde);
    //     return $novo_acorde;
    // }

    // private function preencheColuanaQueFaltam($acorde){
     
    //     $coluna_1_corda_0 = false;
    //     $coluna_2_corda_1 = false;
    //     $coluna_3_corda_2 = false;
    //     $coluna_4_corda_3 = false;
    //     $coluna_5_corda_4 = false;
    
        
    //     foreach($acorde as $chave => $valor){
            
           
    //         if (array_key_exists("coluna_1_corda_1", $valor)) {
    //             $coluna_1_corda_0 = true;
    //         }elseif(array_key_exists("coluna_2_corda_1", $valor)) {
    //             $coluna_2_corda_1 = true;
    //         }elseif(array_key_exists("coluna_3_corda_2", $valor)) {
    //             $coluna_3_corda_2 = true;
    //         }elseif(array_key_exists("coluna_4_corda_3", $valor)) {
    //             $coluna_4_corda_3 = true;
    //         }elseif(array_key_exists("coluna_5_corda_4", $valor)) {
    //             $coluna_5_corda_4 = true;
    //         }elseif(array_key_exists("coluna_0_corda_0", $valor)) {
    //             unset($acorde[0]);
    //         }
    //     }
    
    //     if(!$coluna_1_corda_0) {
    //         $acorde[1]["coluna_1_corda_0"] = null;
    //         $acorde[1]["coluna_1_corda_1"] = null;
    //         $acorde[1]["coluna_1_corda_2"] = null;
    //         $acorde[1]["coluna_1_corda_3"] = null;
    //         $acorde[1]["coluna_1_corda_4"] = null;
    //     }
    //     if(!$coluna_2_corda_1) {
    //         $acorde[2]["coluna_2_corda_0"] = null;
    //         $acorde[2]["coluna_2_corda_1"] = null;
    //         $acorde[2]["coluna_2_corda_2"] = null;
    //         $acorde[2]["coluna_2_corda_3"] = null;
    //         $acorde[2]["coluna_2_corda_4"] = null;
    //     }
    //     if(!$coluna_3_corda_2) {
    //         $acorde[3]["coluna_3_corda_0"] = null;
    //         $acorde[3]["coluna_3_corda_1"] = null;
    //         $acorde[3]["coluna_3_corda_2"] = null;
    //         $acorde[3]["coluna_3_corda_3"] = null;
    //         $acorde[3]["coluna_3_corda_4"] = null;
    //     }
    //     if(!$coluna_4_corda_3)  {
    //         $acorde[4]["coluna_4_corda_0"] = null;
    //         $acorde[4]["coluna_4_corda_1"] = null;
    //         $acorde[4]["coluna_4_corda_2"] = null;
    //         $acorde[4]["coluna_4_corda_3"] = null;
    //         $acorde[4]["coluna_4_corda_4"] = null;
    //     }
    //     if(!$coluna_5_corda_4)  {
    //         $acorde[5]["coluna_5_corda_0"] = null;
    //         $acorde[5]["coluna_5_corda_1"] = null;
    //         $acorde[5]["coluna_5_corda_2"] = null;
    //         $acorde[5]["coluna_5_corda_3"] = null;
    //         $acorde[5]["coluna_5_corda_4"] = null;
    //     }
    //     ksort($acorde);
    //     return $acorde;
    // }

    
   
    private static function storeAcorde($acorde)
    {

        $chord = Chord::where('chord_name', $acorde['chord_name'])->first();

        if ($chord instanceof Chord) {
            $existingPositions = json_decode($chord->chord_positions, true);

            $newPosition = $acorde['chord_positions'];
            foreach ($existingPositions as $position) {
                if ($position == $newPosition) {
                    return false;
                }
            }
            
            array_push($existingPositions,$newPosition);

            $chord->chord_positions = json_encode($existingPositions);

            $chord->save();

            return true;

        } else {
            $acorde['chord_positions'] = json_encode([$acorde['chord_positions']]);
            Chord::create($acorde);
            return true;
        }
        
    }
    
    public function destroy(Request $request,string $id)
    {
        $fields = $request->all();
        $chord = Chord::findOrFail($id);
        if ($chord instanceof Chord) {
            $existingPositions = json_decode($chord->chord_positions, true);
            $key = (int)$fields['key'];
            if(count($existingPositions) > 1)
            {
                unset($existingPositions[$key]);
                $existingPositions = array_values($existingPositions);
                $chord->chord_positions = json_encode($existingPositions);
                $chord->save();
             
            }else {
                $chord->delete();
                return response()->json([],201);
            }
        }else{
            throw new MusicException('Acorde não encontrado');
        }
    }
}
