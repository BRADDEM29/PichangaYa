<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
// 🟢 OJO: En la versión 2, el import es más corto:
use Intervention\Image\Facades\Image; 

class ImageHelper
{
    /**
     * Versión compatible con Intervention Image v2.7
     */
    public static function upload($file, $directory = 'profile-photos')
    {
        $filename = uniqid() . '.webp';
        $path = $directory . '/' . $filename;

        // 🟢 DIFERENCIA 1: En v2 se usa 'make', no 'read'
        $image = Image::make($file);

        // 🟢 DIFERENCIA 2: La forma de redimensionar en v2 es con callbacks
        // Esto reduce la imagen a 1000px de ancho solo si es más grande, manteniendo proporción
        $image->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // 🟢 DIFERENCIA 3: Convertir a WebP en v2
        $encoded = $image->encode('webp', 80);

        // Guardar
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}