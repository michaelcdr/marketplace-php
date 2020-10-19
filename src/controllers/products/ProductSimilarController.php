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
        $model = $this->_productService->getById($_GET['id']);
        $pathJs = SrcHelper::getProductAdminJs();
        //$model = $this->_productService->getProductCreateViewModel();
        require $_SERVER['DOCUMENT_ROOT'] . '\\views\\admin\\product\\similar.php';
    }
}
