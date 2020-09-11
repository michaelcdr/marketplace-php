<?php
    namespace infra\interfaces;
    interface ISubCategoryRepository 
    {
        public function add($category);
        public function remove($categoryId);
        public function update($category);
        public function getById($id);
        public function total($search);
        public function getAll();
        public function getAllPaginated($page, $search, $pageSize);
    }
?>