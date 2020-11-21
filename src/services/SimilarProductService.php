<?php

namespace services;

use models\ProductComAssociacaoSimilar;

class SimilarProductService
{
    private $_repoProduct;

    public function __construct($factory)
    {
        $this->_repoProduct = $factory->getProductRepository();
    }

    public function getAllPaginated($currentProductId)
    {
        $pagina = isset($_GET["p"]) ? intval($_GET["p"]) : 1;
        $search = isset($_GET["s"]) ? $_GET["s"] : null;
        $userId = $_SESSION["role"] === "vendedor" ? $_SESSION["userId"] : null;

        $paginatedResults = $this->_repoProduct->getPossibleChoicesForSimilarProducts($pagina, $search, $userId, 5, $currentProductId);
        $paginatedResults->results = $this->stmtToProduct($paginatedResults->results);
        return $paginatedResults;
    }

    public function getAllCurrentSimilarProductsIdsByProductId($produtoId)
    {
        return $this->_repoProduct->getAllCurrentSimilarProductsIdsByProductId($produtoId);
    }

    /* 
    * Transforma uma lista PDO statement em uma lista de ProductComAssociacaoSimilar.
    */
    public function stmtToProduct($produtosResult)
    {
        $products = array();
        foreach ($produtosResult as $productItem) {
            $product = new ProductComAssociacaoSimilar(
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
            $product->setAssociado($productItem["Associado"]);
            $products[] = $product;
        }

        return $products;
    }

    public function remove($productId)
    {
        $this->_repoProduct->remove($productId);
    }
}
