<?php

namespace controllers\attributes;

use controllers\IBaseController;

class AttributeListController  implements IBaseController
{
    private $_repoAttribute;

    public function __construct($factory)
    {
        $this->_repoAttribute = $factory->getAttributeRepository();
    }

    public function proccessRequest(): void
    {
        $page = 1;
        if (isset($_GET["p"]))
            $page = intval($_GET["p"]);

        $paginatedResults = $this->_repoAttribute->getAllPaginated($page, null, 5);
        echo '<pre>';
        var_dump($paginatedResults);
        echo '</pre>';
        //exit;
        $attributes = $paginatedResults->results;
        require "views/admin/attributes/index.php";
    }
}
