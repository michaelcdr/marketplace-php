<?php

namespace controllers\products;

use controllers\IBaseController;

class ProductImageUploadController implements IBaseController
{
    public function __construct($factory)
    {
    }

    public function proccessRequest(): void
    {
        $imagesNames = array();
        $file = $_FILES['images'];
        if (isset($file["name"])) {
            $fileName = basename($file["name"]);
            $targetFilePath = 'img/products/' . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            move_uploaded_file($file["tmp_name"], $targetFilePath);
            $imagesNames[] = $fileName;
        }
    }
}
