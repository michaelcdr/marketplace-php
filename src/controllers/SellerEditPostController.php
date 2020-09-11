<?php
    namespace controllers;
    use controllers\IBaseController;
    use models\Seller;
    use models\User;
    use models\Address;
    use services\SellerService;

    class SellerEditPostController implements IBaseController
    {
        private $_sellerService;

        public function __construct($factory)
        {
            $this->_sellerService = new SellerService($factory);
        }
        
        public function proccessRequest() : void
        {
            $retorno = $this->_sellerService->update();
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($retorno);
        }
    }
?>