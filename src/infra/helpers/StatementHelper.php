<?php

namespace infra\helpers;

use models\ProductComAssociacaoSimilar;
use models\Product;

class StatementHelper
{
    public static function ToProductComAssociacaoSimilar($productItem)
    {
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

        return $product;
    }

    public static function ToProduct($productItem)
    {
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
        return $product;
    }
}
