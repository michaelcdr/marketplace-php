<?php
    namespace controllers;
    
    use controllers;
    use infra;
    use models;
    use infra\repositories;

    class CategoryEditController implements IBaseController
    {
        private $_repoCategory;

        public function __construct($factory)
        {
            $this->_repoCategory = $factory->getCategoryRepository();
        }
        
        public function proccessRequest() : void
        {
            $id = $_GET["id"];
            
            $category = $this->_repoCategory->getById($id);

            
            
            require "views/admin/categories/editar.php";
        }
    }
?>