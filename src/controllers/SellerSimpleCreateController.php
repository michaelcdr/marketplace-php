<?php
    namespace controllers;
    use services\SellerService;

    class SellerSimpleCreateController implements IBaseController
    {
        public function __construct($factory)
        {
        }

        public function proccessRequest() : void
        {
            if (!isset($_SESSION["userId"])) {
                header('location: /vendedor-indentificacao');
            } else {
                header('location: /admin/produto');
            }
        }
    }
?>