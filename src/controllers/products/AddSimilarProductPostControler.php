<?php

namespace controllers\products;

use controllers\IBaseController;
use services\SimilarProductService;
use models\Product;
use models\JsonError;
use models\JsonSuccess;
use Exception;

class AddSimilarProductPostControler implements IBaseController
{
    private $_service;

    public function __construct($factory)
    {
        $this->_service = new SimilarProductService($factory);
    }

    public function proccessRequest(): void
    {
        $retornoJson = null;
        try {
            $productId = filter_input(INPUT_POST, 'productId', FILTER_SANITIZE_STRING);
            $similarProductsIds = $_POST["similarProductsIds"];
            echo $productId;
            echo '<pre>';
            var_dump($similarProductsIds);
            echo '</pre>';
            exit;
            /*
            $retornoAdd =  $this->productService->add($imagesUploaded, $product);
            
            if (is_null($retornoAdd))
                throw new Exception();
            */
            $retornoJson = new JsonSuccess("Produto cadastrado com sucesso");
            header('Content-type:application/json;charset=utf-8');
        } catch (Exception $e) {
            $retornoJson = new JsonError("Não foi possivel cadastrar o produto");
        }
        echo json_encode($retornoJson);
    }
}
