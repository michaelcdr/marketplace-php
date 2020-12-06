<?php

namespace controllers\products;

use controllers\IBaseController;

class ListOfRatingPendingController implements IBaseController
{
    private $_productRepository;

    public function __construct($factory)
    {
        $this->_productRepository = $factory->getProductRepository();
    }

    public function proccessRequest(): void
    {
        require $_SERVER['DOCUMENT_ROOT'] . '\\views\\admin\\product\\rating-pending.php';
    }
}
