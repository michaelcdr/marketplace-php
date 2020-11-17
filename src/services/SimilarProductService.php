<?php

namespace services;

use models\Product;

class SimilarProductService
{
    private $_repoProduct;
    private $_repoUser;

    public function __construct($factory)
    {
        $this->_repoProduct = $factory->getProductRepository();
        $this->_repoUser = $factory->getUserRepository();
    }

    public function getAllPaginated($currentProductId)
    {
        $pagina = 1;
        if (isset($_GET["p"]))
            $pagina = intval($_GET["p"]);

        $search = null;
        if (isset($_GET["s"]))
            $search = $_GET["s"];

        $userId = null;

        if ($_SESSION["role"] === "vendedor")
            $userId = $_SESSION["userId"];

        $paginatedResults = $this->_repoProduct->getPossibleChoicesForSimilarProducts($pagina, $search, $userId, 5, $currentProductId);
        $paginatedResults->results = $this->stmtToProduct($paginatedResults->results);
        return $paginatedResults;
    }

    /* 
    * Transforma uma lista PDO statement em uma lista de Model Product.
    */
    public function stmtToProduct($produtosResult)
    {
        $products = array();
        foreach ($produtosResult as $productItem) {
            $product = new Product(
                $productItem["ProductId"],
                $productItem["Title"],
                number_format($productItem["Price"], 2, ",", "."),
                $productItem["Description"],
                $productItem["CreatedAt"],
                $productItem["CreatedBy"],
                $productItem["Offer"],
                $productItem["Stock"],
                $productItem["Sku"],
                $productItem["UserId"],
                $productItem["Seller"]
            );
            $product->setDefaultImage($productItem["ImageFileName"]);
            $products[] = $product;
        }

        return $products;
    }

    public function remove($productId)
    {
        $this->_repoProduct->remove($productId);
    }
}
