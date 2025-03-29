<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Singer;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Requests\Api\Music\SingerRequest;
use App\Http\Resources\Api\Music\SingerResource;


class SingerController extends Controller
{
    public function index()
    {
        try{
            $singers = Singer::with('musics')->orderBy('singer_name')->get();
            return SingerResource::collection($singers);
        }catch(\Exception $e){
            throw new MusicException("Erro ao listar musicos.");
        }
        
    }
    public function store(SingerRequest $request)
    {
       
        $fields = $request->validated();
        
        $singer = Singer::where('singer_name', $fields['singer_name'])->first();
        if ($singer instanceof Singer) {
            throw new MusicException('Músico/Grupo já foi cadastrado.');
        }
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/storage/image/singers', $imageName);
            $tempImagePath = storage_path('app/public/storage/image/singers/' . $imageName);
            $processedImage = $this->imageSize($tempImagePath);
            $processedImagePath = 'image/singers/processed_' . $imageName;
            $fields['image'] = $processedImagePath;
            unlink($tempImagePath);  
        }
        $newSinger = Singer::create($fields);
        return new SingerResource($newSinger);
    }
    public function update(SingerRequest $request, string $id)
{
    $fields = $request->validated();
    $singer_validade = Singer::where('singer_name', $fields['singer_name'])->first();
    if ($singer_validade instanceof Singer && $singer_validade->id != (int)$id) {
        throw new MusicException('Músico/Grupo já está cadastrado.');
    }

    try {
        $singer = Singer::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($singer->image) {
                $oldImagePath = storage_path('app/public/' . $singer->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/storage/image/singers', $imageName);
            $tempImagePath = storage_path('app/public/storage/image/singers/' . $imageName);
            $processedImage = $this->imageSize($tempImagePath);
            $processedImagePath = 'image/singers/processed_' . $imageName;
            $fields['image'] = $processedImagePath;
            unlink($tempImagePath);  // Exclui o arquivo temporário
        }
        $singer->fill($fields);
        $singer->save();

        // Retorna a resposta com o recurso de cantor atualizado
        return new SingerResource($singer);
        
    } catch (\Exception $e) {
        throw new MusicException('Erro ao atualizar Músico/Grupo: ' . $e->getMessage());
    }
}


    // public function update(SingerRequest $request, string $id)
    // {
    //     $fields = $request->validated();
    //     $singer_validade = Singer::where('singer_name', $fields['singer_name'])->first();
    //     if( $singer_validade instanceof Singer && $singer_validade->id != (int)$id){
    //         throw new MusicException('Musico/Grupo já esta cadastrado.');
    //     }

    //     try{
    //         $singer = Singer::findOrFail($id);
    //         $singer->fill($fields);
    //         $singer->save();
    //         return new SingerResource($singer);
    //     }catch(\Exception $e){
    //         throw new MusicException('Erro ao atualizar Musico/Grupo.');

    //     }
    // }
    public function destroy(string $id)
    {
        try{
            $singer = Singer::findOrFail($id);
            $singer->delete();
            return response()->json([],201);
        }catch(\Exception $e){
            throw new MusicException('Musico/Grupo não encontrado.');
        }
    }

    private function imageSize($imagePath)
{
    if (!file_exists($imagePath)) {
        throw new MusicException( 'Erro: Imagem não encontrada.');
    }

    $imageData = file_get_contents($imagePath);
    if ($imageData === false) {
        throw new MusicException( 'Erro: Falha ao ler a imagem.');
    }

    $image = imagecreatefromstring($imageData);
    if (!$image) {
        throw new MusicException( 'Erro: Falha ao criar a imagem.');
    }

    $width = imagesx($image);
    $height = imagesy($image);

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $luminosity = (int)(0.2126 * $r + 0.7152 * $g + 0.0722 * $b);
            $color = $luminosity > 128 ? imagecolorallocate($image, 255, 255, 255) : imagecolorallocate($image, 0, 0, 0);
            imagesetpixel($image, $x, $y, $color);
        }
    }

    // Redimensionamento
    $newWidth = 200;
    $newHeight = 200;
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $processedImagePath = 'processed_' . basename($imagePath);
    $destinationPath = public_path('storage/image/singers');

    if (!is_dir($destinationPath) && !mkdir($destinationPath, 0777, true)) {
        imagedestroy($image);
        imagedestroy($newImage); // Libera a nova imagem também
        throw new MusicException( 'Erro: Falha ao criar o diretório.');
    }

    if (!imagepng($newImage, $destinationPath . '/' . $processedImagePath)) {
        imagedestroy($image);
        imagedestroy($newImage); // Libera a nova imagem também
        throw new MusicException( 'Erro: Falha ao salvar a imagem.');
    }

    imagedestroy($image);
    imagedestroy($newImage); // Libera a nova imagem também
    return $destinationPath . '/' . $processedImagePath;
}


}
