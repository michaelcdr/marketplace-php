<?php
    namespace controllers;
    use services\ProductService;

    class SearchController implements IBaseController
    {
         private $_productService;
        

        public function __construct($factory)
        {
            $this->_productService = new ProductService($factory);
        }
        
        public function proccessRequest() : void
        {
            $paginatedResults  =  $this->_productService->getAllForSearchPaginated();
            $products = $paginatedResults ->results;
            
            require "views/home/pesquisa.php";
        }
    }
?>