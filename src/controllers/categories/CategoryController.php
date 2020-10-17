<?php
    namespace controllers\categories;
    
    class CategoryController
    {
        private $_repoCategories;

        public function __construct($factory)
        {
            $this->_repoCategories = $factory->getCategoryRepository();
        }

        public function getCategories()
        {
            return $this->_repoCategories->getAll();
        }
    }
