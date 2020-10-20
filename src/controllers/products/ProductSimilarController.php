<?php

namespace controllers\products;

use controllers\IBaseController;
use infra\helpers\SrcHelper;
use services\ProductService;

class ProductSimilarController implements IBaseController
{
    private $_productService;

    public function __construct($factory)
    {
        $this->_productService = new ProductService($factory);
    }

    public function proccessRequest(): void
    {
        $productWithSimilarProducts = $this->_productService->getProductByIdWithSimilarProducts($_GET['id'], 0, null, 10);
        // echo '<pre>';
        // var_dump($productWithSimilarProducts);
        // echo '</pre>';
        $pathJs = SrcHelper::getProductAdminJs();
        $model = $productWithSimilarProducts->getProduct();
        $paginatedResults = $productWithSimilarProducts->getPaginatedResultsOfSimilarProducts();
        $products = $productWithSimilarProducts->getPaginatedResultsOfSimilarProducts()->results;
        require $_SERVER['DOCUMENT_ROOT'] . '\\views\\admin\\product\\similar.php';
    }
}
