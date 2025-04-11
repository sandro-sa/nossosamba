<?php

namespace  App\Http\Controllers\Web\AppWeb;

use App\Models\Music\Chord;
use App\Models\Music\Music;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\App\ChordService\RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde;

class ShowMusicController extends Controller
{
    private $escalaMaior = [
        "C" => 1,
        "C#" => 2, "D" => 3,
        "D#" => 4, "E" => 5, "F" => 6,
        "F#" => 7, "G" => 8,
        "G#" => 9, "A" => 10,
        "A#" => 11, "B" => 12
    ];
    
    private $escalaMenor = [
        "C" => 1,
        "C#" => 2, "D" => 3,
        "D#" => 4, "E" => 5, "F" => 6,
        "F#" => 7, "G" => 8,
        "G#" => 9, "A" => 10,
        "A#" => 11, "B" => 12
    ];

    private $escalaVerificacao = [
        "C","D", "E", "F", "G", "A", "B"
    ];

    private $escalaSustenida = [
        "C" => 1,
        "C#" => 2,
        "D" => 3,
        "D#" => 4,
        "E" => 5,
        "F" => 6,
        "F#" => 7,
        "G" => 8,
        "G#" => 9,
        "A" => 10,
        "A#" => 11,
        "B" => 12
    ];
    
    private $escalaBemol = [
        "C" => 1,
        "Db" => 2,
        "D" => 3,
        "Eb" => 4,
        "E" => 5,
        "F" => 6,
        "Gb" => 7,
        "G" => 8,
        "Ab" => 9,
        "A" => 10,
        "Bb" => 11,
        "B" => 12
    ];

    public function index($id, $posicaoDoNovoTom = null)
    {
        $music = Music::where('id',$id)->first();

        if(!$music){
            return view('error.not-found');
        }
        $tom = $this->retornaTom($music->tone->tone);
        $acordeEhMenor = $this->verificaSeAcordeEhMenor($music->tone->tone);
        //Aqui entra o tipo da escala (sustenido ou bemol)
        $tipoEscala = $this->tipoDeEscalaPorTom($music->tone->tone);
        // Aqui define qual escala usar (maior ou menor)
        $escala = $acordeEhMenor ? $this->escalaMenor : $this->escalaMaior;

        foreach ($escala as $chave => $valor) {
            if($tom == $chave){
                $posicao = $valor;
            }
        }

        $listaDeTons = RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde::retornaListaDeNomesEhPosicaoOndeInciaOhAcorde($posicao, $music->tone->tone);
        $arrayChords = json_decode($music->chords);
        $listaDeAcordeParaMudarTom = false;

        if($posicaoDoNovoTom != null){
            if($posicaoDoNovoTom < $posicao || $posicaoDoNovoTom > $posicao){
                $novaPosicao = $posicao - $posicaoDoNovoTom;
                $listaDeAcordeParaMudarTom = $this->retornaListaDeAcordeParaMudarTom($arrayChords, $novaPosicao, $acordeEhMenor, $tipoEscala);
                
                $arrayChords = $this->retornaNovosAcordes($arrayChords, $novaPosicao, $acordeEhMenor, $tipoEscala);
            }
        }

        $chords = Chord::whereIn('chord_name', $arrayChords)->get();

        $tons = [];
        $aux = 0;
        foreach ($listaDeTons as $tom => $posicao) {
            $tons[$aux]["tom"] = $tom;
            $tons[$aux]["posicao"] = $posicao;
            $aux++;
        }
        
        $novoTom = false;
        if($listaDeAcordeParaMudarTom){
           $novoTom = $this->retornaNovoTom($music->tone->tone,$listaDeAcordeParaMudarTom, $posicaoDoNovoTom );
        }
       
       $listaDeTons = json_encode($tons);
       $listaDeAcordeParaMudarTom = json_encode($listaDeAcordeParaMudarTom);
     //  dd($listaDeAcordeParaMudarTom);
        $music->load(['tone','singer','rhythm']);
        return view('web.show-music',[
            'music' => $music,
            'chords' => $chords,
            'listaDeTons' => $listaDeTons,
            'listaDeAcordeParaMudarTom' => $listaDeAcordeParaMudarTom,
            "novoTom" => $novoTom,
        ]);
      
    }
    private function retornaTom($tomOriginal){
        $tom = strtoupper(substr($tomOriginal, 0, 1));
        $segundoCaracterDoAcorde = mb_substr($tomOriginal, 1, 1, 'UTF-8');
    
        if ($segundoCaracterDoAcorde == "b" || $segundoCaracterDoAcorde == "#") {
            $tom .= $segundoCaracterDoAcorde;
        }
    
        return $tom;
    }
    private function verificaSeAcordeEhMenor($nomeDoAcorde){

        $segundoCaracter = mb_substr($nomeDoAcorde, 1, 1, 'UTF-8');
        $terceiroCaractere = mb_substr($nomeDoAcorde, 2, 1, 'UTF-8');
        if($segundoCaracter == "m"|| $terceiroCaractere == "m"){
            return true;
        }
        return false;

    }
    private function retornaNovaNota($novaPosicao, $nota, $tipoEscala)
    {
        $escala = $this->obterEscalaCorreta($tipoEscala);
    
        // 🧪 SE NÃO EXISTIR NA ESCALA, TENTA TROCAR A ESCALA!
        if (!isset($escala[$nota])) {
            // Tenta outra escala
            $tipoEscalaAlternativa = $tipoEscala === 'bemol' ? 'sustenido' : 'bemol';
            $escala = $this->obterEscalaCorreta($tipoEscalaAlternativa);
        }
    
        // Ainda não encontrou? Aborta e retorna a nota original
        if (!isset($escala[$nota])) {
            return $nota;
        }
    
        $posicaoNotaOriginal = $escala[$nota];
    
        // Faz o deslocamento circular corretamente (1-12)
        $novaPosicaoNota = (($posicaoNotaOriginal - $novaPosicao - 1 + 12) % 12) + 1;
    
        // Busca o nome da nova nota
        foreach ($escala as $nomeNota => $valor) {
            if ($valor === $novaPosicaoNota) {
                return $nomeNota;
            }
        }
    
        return $nota; // fallback de segurança
    }
    
    private function retornaListaDeAcordeParaMudarTom($arrayChords, $novaPosicao, $escalaEhMenor,$tipoEscala){
        
        $cont = 0;
        foreach($arrayChords as $key => $value){

            $nota = $this->retornaTom($value);
            
            $novaNota = $this->retornaNovaNota($novaPosicao, $nota,$tipoEscala);
            
            $restanteDoAcorde = $this->retornaRestanteDoAcorde($value,$novaPosicao,$escalaEhMenor);
            
            $novosAcordes[$value] = $novaNota.$restanteDoAcorde;
            $cont++;
        }

        return $novosAcordes;
    }
    private function retornaNovosAcordes($arrayChords, $novaPosicao,  $escalaEhMenor,$tipoEscala){

        foreach($arrayChords as $key => $value){
            
            $nota = $this->retornaTom($value);

            $novaNota = $this->retornaNovaNota($novaPosicao, $nota,$tipoEscala );
            

            $restanteDoAcorde = $this->retornaRestanteDoAcorde($value,$novaPosicao, $escalaEhMenor);

            $novosAcordes[ $key] = $novaNota.$restanteDoAcorde;
        }

        return $novosAcordes;
    }
    private function retornaRestanteDoAcorde($tom, $novaPosicao, $escalaEhMenor){
    
        $restanteDoAcorde = substr($tom, 1);
        $sustenidoOuBemol = mb_substr($tom, 1, 1, 'UTF-8');
       
        if($sustenidoOuBemol == "b" || $sustenidoOuBemol == "#"){
            $restanteDoAcorde = substr($tom, 2);
        }

        $temTomNotaMusical = $this->verificaSeAcordeTemTomMaior($restanteDoAcorde,$novaPosicao);

        if($temTomNotaMusical){
            $novaNota  = $this->retornaNovaNotaAposNota($novaPosicao, $temTomNotaMusical, $escalaEhMenor);
            $restanteDoAcorde = str_replace($temTomNotaMusical, $novaNota, $restanteDoAcorde);
        }
        return $restanteDoAcorde;

    }
    private function retornaNovoTom($tom, $arrayDeTons, $novaPosicao){

        foreach($arrayDeTons as $key => $value){
            if($tom == $key){
                return $value;
            }
        }
        
        if(array_key_exists($tom, $this->escalaMaior )){
            foreach($this->escalaMaior as $key => $chave){
                if($novaPosicao == $chave){
                    return $key;
                }
            }
    
        }

        if(array_key_exists($tom, $this->escalaMenor )){
            foreach($this->escalaMaior as $key => $chave){
                if($novaPosicao == $chave){
                    return $key;
                }
            }
    
        }
    }
    private function verificaSeAcordeTemTomMaior($restanteDoAcorde){
        
        $contémLetras = false;

        foreach ($this->escalaVerificacao as $letra) {
            if (stripos($restanteDoAcorde, $letra) !== false) {
                $contémLetras = $letra;
                break;
            }
        }

        if ($contémLetras) {
            return $contémLetras;
        }

        return false;
    }
    private function retornaNovaNotaAposNota($novaPosicao, $nota, $acordeEhMenor){

        
        $posicao = null;
        $escala = [];
        if($acordeEhMenor){
            $escala = $this->escalaMenor;
        }else{
            $escala = $this->escalaMaior;
        }

        foreach ($escala as $chave => $valor) {
            if($nota == $chave){
                
                $posicao = $valor - $novaPosicao;
               // dd($nota,$chave,$posicao,$valor, $novaPosicao);
            }
        }

        if(!$posicao){
           $posicao = 12;
        }
        else if($posicao < 0){
            $posicao = 12 - ($posicao *-1);
        }
        else if($posicao > 12){
            $posicao = $posicao - 12;
        }
        
        foreach ($escala as $chave => $valor) {
            if($posicao == $valor){
                return $chave;
            }
        }

    }
    private function tipoDeEscalaPorTom($tomOriginal)
    {
        $tomBase = strtoupper($this->retornaTom($tomOriginal));

        if (str_contains($tomBase, 'B')) {
            return 'bemol';
        }

        $tonsComBemol = ['F', 'Bb', 'Eb', 'Ab', 'Db', 'Gb', 'Cb'];
        if (in_array($tomBase, $tonsComBemol)) {
            return 'bemol';
        }

        return 'sustenido';
    }
    private function obterEscalaCorreta( $tipoEscala)
    {
        if ($tipoEscala === 'bemol') {
            return $this->escalaBemol;
        }

        return $this->escalaSustenida;
    }
}
