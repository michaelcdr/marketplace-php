<?php
    namespace infra\interfaces;
    
    interface IProductRepository 
    {
        // public function add($user);
        // public function remove($user);
        public function getAll($page, $search, $userId, $pageSize,$site);
        public function totalOfProducts($search, $userId);
        public function add($product);
        public function getCurrentStock($productId);
        public function addImages($productId, $imagesNames);
        public function update($product);
        public function decreaseStockByOrderItens($orderItens);
        public function getAllByUserIdSeller($userId);
    }
?>