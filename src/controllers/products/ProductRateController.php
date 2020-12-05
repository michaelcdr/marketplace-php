<?php

namespace controllers\products;

use controllers\IBaseController;
use services\ProductService;

class ProductListController implements IBaseController
{
    public function __construct($factory)
    {
        $this->_productService = new ProductService($factory);
    }

    public function proccessRequest(): void
    {
        require $_SERVER['DOCUMENT_ROOT'] . '\\views\\admin\\product\\avaliar.php';
    }
}
