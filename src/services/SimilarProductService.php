<?php

namespace services;

use models\Product;
use models\ProductComAssociacaoSimilar;
use domain\admin\ProductWithSimilarProducts;

class SimilarProductService
{
    private $_repoProduct;

    public function __construct($factory)
    {
        $this->_repoProduct = $factory->getProductRepository();
    }

    public function getProductByIdWithSimilarProducts($productId, $page, $search, $pageSize)
    {
        $product = $this->_repoProduct->getById($productId);

        $paginatedResultsOfSimilarProducts = $this->_repoProduct
            ->getAllSimilarProductsPaginated($page, $search, $product->getUserId(), $pageSize, $productId);

        $paginatedResultsOfSimilarProducts->results = $this->stmtToProduct($paginatedResultsOfSimilarProducts->results);

        return new ProductWithSimilarProducts($product, $paginatedResultsOfSimilarProducts);
    }

    public function getPossibleChoicesForSimilarProducts($currentProductId)
    {
        $pagina = isset($_GET["p"]) ? intval($_GET["p"]) : 1;
        $search = isset($_GET["s"]) ? $_GET["s"] : null;

        $product = $this->_repoProduct->getById($currentProductId);

        $paginatedResults = $this->_repoProduct
            ->getPossibleChoicesForSimilarProducts($pagina, $search, $product->getUserId(), 5, $currentProductId);

        $paginatedResults->results = $this->stmtToProductComAssociacao($paginatedResults->results);
        return $paginatedResults;
    }

    public function getAllPaginated($currentProductId)
    {
        $pagina = isset($_GET["p"]) ? intval($_GET["p"]) : 1;
        $search = isset($_GET["s"]) ? $_GET["s"] : null;
        $product = $this->_repoProduct->getById($currentProductId);
        
        $paginatedResults = $this->_repoProduct
            ->getAllSimilarProductsPaginated($pagina, $search, $product->getUserId(), 5, $currentProductId);
            
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
    public function stmtToProductComAssociacao($produtosResult)
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

    public function update($productId, $arrayOfIdsSimilarProducts)
    {
        $this->_repoProduct->removeAllSimilarProducts($productId);
        $this->_repoProduct->addSimilarProducts($productId, $arrayOfIdsSimilarProducts);
    }
}
