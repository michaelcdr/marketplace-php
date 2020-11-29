<?php

namespace controllers;

class DetailsProductController implements IBaseController
{
    private $_repoProduct;

    public function __construct($factory)
    {
        $this->_repoProduct = $factory->getProductRepository();
    }

    public function proccessRequest(): void
    {
        if (is_null($_GET["id"]) || !isset($_GET["id"]))
            header('location: /');

        $product = $this->_repoProduct->getById($_GET["id"]);
        $attributesValues = $this->_repoProduct->getAllAttributesValues($_GET["id"]);
        $similarProducts = $this->_repoProduct->getAllSimilarProducts($_GET["id"]);
        $isLiked = !isset($_SESSION["userId"]) ? false : $this->_repoProduct->isLiked($_GET["id"], $_SESSION["userId"]);
        $product->addRangeAttributeValues($attributesValues);
        require "views/produto/details-product.php";
    }
}
