<?php

namespace services;

use models\Product;
use models\ProductCreateViewModel;
use models\ProductEditViewModel;
use models\helpers\PathHelper;
use infra\helpers\SrcHelper;

class ProductService
{
    private $_repoProduct;
    private $_repoUser;
    private $_pathHelper;

    public function __construct($factory)
    {
        $this->_repoProduct = $factory->getProductRepository();
        $this->_repoUser = $factory->getUserRepository();
        $this->_pathHelper = new PathHelper();
    }

    public function getProductCreateViewModel()
    {
        $colPrice = "col-md-6";
        $sellers = null;
        if ($_SESSION["role"] == "admin") {
            $colPrice = "col-md-3";
            $sellers = $this->_repoUser->getSellers();
        }
        return new ProductCreateViewModel($colPrice, $sellers);
    }

    public function getProductEditViewModel($productId)
    {
        $colPrice = "col-md-6";
        $sellers = null;
        if ($_SESSION["role"] == "admin") {
            $colPrice = "col-md-3";
            $sellers = $this->_repoUser->getSellers();
        }
        return new ProductEditViewModel($colPrice, $sellers);
    }

    public function getAllForSearchPaginated()
    {
        $pagina = 1;
        if (isset($_GET["p"]))
            $pagina = intval($_GET["p"]);

        $search = null;
        if (isset($_GET["s"]))
            $search = $_GET["s"];

        $userId = null;
        $paginatedResults = $this->_repoProduct->getAll($pagina, $search, null, 5, true);
        $paginatedResults->results = $this->stmtToProduct($paginatedResults->results);
        return $paginatedResults;
    }

    public function getAllPaginated()
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

        $paginatedResults = $this->_repoProduct->getAll($pagina, $search, $userId, 5, false);
        $paginatedResults->results = $this->stmtToProduct($paginatedResults->results);
        return $paginatedResults;
    }

    public function getById($productId)
    {
        $product = $this->_repoProduct->getById($productId);

        return $product;
    }

    public function add($images, $product)
    {
        //persistindo produto...
        $productId = $this->_repoProduct->add($product);

        if (is_null($productId))
            return null;
        //persistindo imagens...
        if (isset($images) && !is_null($images) && $images != "") {
            $imagesNames = explode("$$", $images);
            if (count($imagesNames) > 0)
                $this->_repoProduct->addImages($productId, $imagesNames);
        }
        return $productId;
    }

    public function update($images, $product)
    {
        $productId = $product->getId();
        $this->_repoProduct->removeAllImages($productId);
        $this->_repoProduct->update($product);

        //persistindo imagens...
        if (isset($images) && !is_null($images) && $images != "") {
            $imagesNames = explode("$$", $images);
            if (count($imagesNames) > 0)
                $this->_repoProduct->addImages($productId, $imagesNames);
        }
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
