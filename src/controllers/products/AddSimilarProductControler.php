<?php

namespace controllers\products;

use controllers\IBaseController;
use infra\helpers\SrcHelper;
use services\ProductService;

class AddSimilarProductControler implements IBaseController
{
    private $_productService;

    public function __construct($factory)
    {
        $this->_productService = new ProductService($factory);
    }

    public function proccessRequest(): void
    {
        $paginatedResults = $this->_productService->getAllPaginated();
        $products = $paginatedResults->results;
        require $_SERVER['DOCUMENT_ROOT'] . '\\views\\admin\\product\\add-similar.php';
    }
}
