<?php

namespace App\Traits;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;

trait ImageTrait
{
    protected function resizeAndStore($imageFile, $width = 1280, $height = 720): string
    {
        $imageName = time() . '_' . $imageFile->getClientOriginalName();
        $image = new ImageManager(new Driver())
            ->read($imageFile)
            ->cover($width, $height)
            ->encode(new AutoEncoder(quality: 75));

        Storage::disk('public')->put($imageName, $image);

        return $imageName;
    }
}