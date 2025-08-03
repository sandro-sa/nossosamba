<?php

namespace App\Http\Controllers\Web\AppWeb;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Web\Repertoire\Service;
use App\Models\Repertoire\Repertoire;

class RepertoireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('web.create_repertoire');
    }

   

    /**
     * Display the specified resource.
     */
    public function show(Repertoire $repertoire)
    {

       $musics = $repertoire->musics()
        ->withPivot(['tom', 'position'])
        ->orderBy('pivot_position') // <- ordenar pela coluna 'position' da tabela pivot
        ->get();
            
        $data = $musics->map(function ($music) {
            return [
                'music_id' => $music->id,
                'tom' => $music->pivot->tom,
           
            ];
        });

        $musics_array = [];
        $aux =0;
        foreach ($data as $key => $value) {
      
            $service = new Service($value['music_id'],$value['tom']);
            $musics_array[$aux] = $service->index();
            $aux++;

        }
    
        

        return view('web.show_repertoire',[
            'musics_array' => $musics_array,
        ]);
        
    }

}

  
