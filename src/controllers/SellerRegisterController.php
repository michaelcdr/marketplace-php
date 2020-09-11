<?php
    namespace controllers;
    use services\SellerService;

    class SellerRegisterController implements IBaseController
    {
        private $_sellerService;

        public function __construct($factory)
        {
            $this->_sellerService = new SellerService($factory);
        }

        public function proccessRequest() : void
        {
            if (!isset($_SESSION["userId"])) 
            {
                require "views/seller/register.php";
            } 
            else 
            {
                header('location: /admin/vendedor');
            }
        }
    }
?>