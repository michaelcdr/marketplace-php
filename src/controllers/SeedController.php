<?php
    namespace controllers;
    use infra;
    use infra\repositories;

    class SeedController implements IBaseController
    {
        private $_repoSeed;

        public function __construct($factory)
        {
            // echo "seed > construtor<br/>";
            // echo "<pre>";
            // var_dump($factory);
            // echo "<pre/>";
            $this->_repoSeed = $factory->getSeedRepository();
        }

        public function proccessRequest() : void
        {
            // echo "entrou controller seed";
            $this->_repoSeed->seed();
        }
    }
?>