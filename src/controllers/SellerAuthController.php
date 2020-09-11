<?php
    namespace controllers;
    

    class SellerAuthController implements IBaseController
    {
        public function __construct($factory)
        {
        }

        public function proccessRequest() : void
        {
            if (!isset($_SESSION["userId"])) 
                require "views/seller/login.php";
            else 
                header('location: /admin/vendedor');
        }
    }
?>