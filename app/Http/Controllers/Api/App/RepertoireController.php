<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Music;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Resources\Api\Music\MusicResource;

class RepertoireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function createRepertoire(Request $request)
    {
        $rhythm_id = $request["rhythm_id"];
        $tone_id = $request["tone_id"];

        // Inicia a consulta
        $musics = Music::where('rhythm_id', $rhythm_id);

        // Adiciona a condição de 'tone_id', se fornecido
        if ($tone_id) {
            $musics->where('tone_id', $tone_id)->inRandomOrder(); // Ordena aleatoriamente
        } else {
            $musics->orderBy('tone_id')->inRandomOrder(); // Ordena por tone_id, caso não tenha tone_id
        }

        try {
            // Executa a consulta com os filtros e carrega as relações
            $musics = $musics->get(); // Executa a consulta aqui

            $musics->load(['singer', 'tone', 'rhythm']); // Carrega as relações

            return MusicResource::collection($musics); // Retorna os resultados com o recurso

        } catch (\Exception $e) {
            throw new MusicException('Erro ao listar músicas');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
