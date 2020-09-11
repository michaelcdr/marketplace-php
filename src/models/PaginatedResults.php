<?php
    namespace models;
    
    class PaginatedResults 
    {
        public $results;
        public $qtdTotal;
        public $qtdTotalFiltered;
        public $hasPreviousPage;
        public $hasNextPage;
        public $page;
        public $numberOfPages;

        public $urlPrevPage;
        public $attrDisablePrev;
        public $urlNextPage;
        public $attrDisableNext;

        public function __construct(
            $results,$qtdTotal,$qtdTotalFiltered,$hasPreviousPage,$hasNextPage,$page,$numberOfPages,$urlPagina)
        {
            $this->results = $results;
            $this->qtdTotal = $qtdTotal;
            $this->qtdTotalFiltered = $qtdTotalFiltered;
            $this->hasPreviousPage= $hasPreviousPage;
            $this->hasNextPage = $hasNextPage;
            $this->page = $page;
            $this->numberOfPages = $numberOfPages;

            //dados de controle da paginação
            $this->urlPrevPage = $urlPagina . ($this->page - 1);
            $this->attrDisablePrev = "";
            if ($this->hasPreviousPage == false)
                $this->attrDisablePrev = "disabled";

            $this->urlNextPage = $urlPagina . ($this->page + 1);
            $this->attrDisableNext = "";
            if (!$this->hasNextPage)
                $this->attrDisableNext = "disabled";
        }
    }
?>