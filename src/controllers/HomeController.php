<?php
    namespace controllers;
    use infra;
    use infra\repositories;
    use PDO;
    
    class HomeController implements IBaseController
    {
        private $_repoOfertas;
        private $_repoCarousel;
        private $_repoCategories;

        public function __construct($factory)
        {
            $this->_repoOfertas = $factory->getProductOnOfferRepository();
            $this->_repoCarousel = $factory->getCarouselRepository();
            $this->_repoCategories = $factory->getCategoryRepository();
        }

        public function getProductsOnOffer()
        {
            return $this->_repoOfertas->getAll();
        }

        public function getCaroselItens()
        {
            return $this->_repoCarousel->getAll();
        }
        
        public function proccessRequest() : void
        {
            $ofertas = $this->getProductsOnOffer();
            $caroselItens = $this->getCaroselItens();
            $categories = $this->_repoCategories->getAll();
            
            require "views/home/home.php";
        }
    }
?>