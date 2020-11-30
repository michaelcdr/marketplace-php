<?php

namespace controllers\subcategories;

use controllers\IBaseController;

class SubCategoryListController implements IBaseController
{
    private $_repoSubCategories;

    public function __construct($factory)
    {
        $this->_repoSubCategories = $factory->getSubCategoryRepository();
    }

    public function proccessRequest(): void
    {
        $page = isset($_GET["p"]) ? intval($_GET["p"]) : 1;
        $paginatedResults = $this->_repoSubCategories->getAllPaginated(intval($_GET["id"]),$page, null, 5);
        $subCategories = $paginatedResults->results;
        
        require "views/admin/subcategories/lista.php";
    }
}
