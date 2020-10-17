<?php

namespace controllers\categories;

use controllers\IBaseController;

class CategoryImageUploadController implements IBaseController
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
            $targetFilePath = 'img/categories/' . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            move_uploaded_file($file["tmp_name"], $targetFilePath);
            $imagesNames[] = $fileName;
        }
    }
}
