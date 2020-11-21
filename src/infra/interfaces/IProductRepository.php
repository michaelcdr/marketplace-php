<?php

namespace infra\interfaces;

interface IProductRepository
{
    public function getAll($page, $search, $userId, $pageSize, $site);
    public function totalOfProducts($search, $userId);
    public function add($product);
    public function getCurrentStock($productId);
    public function addImages($productId, $imagesNames);
    public function update($product);
    public function decreaseStockByOrderItens($orderItens);
    public function getAllByUserIdSeller($userId);
    public function getAllSimilarProducts($productId);
    public function getAllSimilarProductsPaginated($productId, $page, $search, $pageSize);
    public function getPossibleChoicesForSimilarProducts($page, $search, $userId, $pageSize, $currentProductId);
    public function totalOfPossibleChoicesForSimilarProducts($search, $userId, $currentProductId);
    public function getAllCurrentSimilarProductsIdsByProductId($productId);
}
