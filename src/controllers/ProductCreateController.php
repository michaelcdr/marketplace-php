<?php
    namespace controllers;
    
    use controllers;
    use infra;
    use models;
    use services\ProductService;

    class ProductCreateController implements IBaseController
    {
        private $_productService;
        public function __construct($factory)
        {
            $this->_productService = new ProductService($factory);
        }
        
        public function proccessRequest() : void
        {
            $model = $this->_productService->getProductCreateViewModel();
            require $_SERVER['DOCUMENT_ROOT'] . '\\views\\admin\\product\\cadastrar.php';
        }
    }
?>