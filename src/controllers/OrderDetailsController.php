<?php
    namespace controllers;
    use infra;
    use models;
    use infra\repositories;
    use models\JsonSuccess;
    use models\JsonError;
    use models\User;
    use services\OrderService;

    class OrderDetailsController implements IBaseController
    {
        private $_orderService;

        public function __construct($factory)
        {
            $this->_orderService = new OrderService($factory);
        }
        
        public function proccessRequest() : void
        {
            $order = $this->_orderService->getOrderWithProducts();
            require "views/admin/order/details.php";
        }
    }
?>