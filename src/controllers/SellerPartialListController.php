<?php
    namespace controllers;
    use infra;
    use models;
    use infra\repositories;
    use models\JsonSuccess;
    use models\JsonError;
    use models\Seller;
    use services\SellerService;

    class SellerPartialListController implements IBaseController
    {
        private $_sellerService;

        public function __construct($factory)
        {
            $this->_sellerService = new SellerService($factory);
        }
        
        public function proccessRequest() : void
        {
            $paginatedResults = $this->_sellerService->getAllPaginated();
            $sellers = $paginatedResults->results;
            require "views/admin/sellers/lista-table.php";
        }
    }
?>