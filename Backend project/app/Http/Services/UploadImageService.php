<?php

namespace App\Http\Services;

use Intervention\Image\Facades\Image;

class UploadImageService
{
    /**
     * @var array $mimeTypes
     *
     * accepted mime types list
     */
    private $mimeTypes;
    /**
     * @var array $dimentions
     *
     * list of dimension of any module
     */
    private $dimensions;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //initialization
        $this->mimeTypes = [
            "image/gif",
            "image/jpeg",
            "image/png",
        ];

        $this->dimensions = [
            "user" => [
                "minSize" => [
                    "w" => 1200,
                    "h" => 900
                ],
                "path" => "agents",
                "dimensions" => [
                    [
                        "w" => 1200,
                        "h" => 900
                    ],
                    [
                        "w" => 1200,
                        "h" => 628
                    ],
                    [
                        "w" => 637,
                        "h" => 478
                    ],
                    [
                        "w" => 303,
                        "h" => 227
                    ]
                ]
            ],
        ];
    }

    public function uploadImage($request)
    {
        $data = array();
        $image = $request->file;
        $name = time() . '.' . $image->extension();
        $img = Image::make($image);
        $data["correctMimeType"] = false;
        $data["correctSize"] = false;
        $data["correctMimeType"] = $this->checkMimeType($img);

//        $data["correctSize"]=($data["correctMimeType"]) ? $this->correctSize($request->imgPath,"user") : false;

        if ($data["correctMimeType"] === true) {
            $img->save(public_path('/upload/images') . '/' . $name);

            $thumbnailImg = Image::make($image);

            $thumbnailImg->resize(70, 70, function ($constraint) {

                $constraint->aspectRatio();

            })->save(public_path('/upload/thumbnail') . '/' . $name);
            $mediumImg = Image::make($image);
            $mediumImg->resize(200, 200, function ($constraint) {

                $constraint->aspectRatio();

            })->save(public_path('/upload/medium') . '/' . $name);
            return $name;
        }else{
            return "";
        }
    }

    /**
     * check if mime type is correct
     *
     * @return bool
     * @var string $path
     */
    private function checkMimeType($image)
    {

        return in_array($image->mime(), $this->mimeTypes);
    }

    /**
     * check if image size is correct
     *
     * @return bool
     * @var string $dimensionLabel
     * @var string $path
     */
    private function correctSize($path, $dimensionLabel)
    {
        $image = Image::make($path);
        return ($image->width() >= $this->dimensions[$dimensionLabel]["minSize"]["w"] && $image->height() >= $this->dimensions[$dimensionLabel]["minSize"]["h"]);
    }
}
