<?php
    namespace controllers\products;
    
    use controllers;
    use models\Product;
    use controllers\IBaseController;
    use services\ProductService;
    use models\JsonError;
    use models\JsonSuccess;
    use Exception;
    class ProductCreatePostController implements IBaseController
    {
        private $productService;
        public function __construct($factory)
        {
            $this->productService = new ProductService($factory);
        }
        
        public function proccessRequest() : void
        {
            $retornoJson = null;
            try {
                //higienizando campos...
                $title = filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING);
                $price = filter_input(INPUT_POST,'price',FILTER_SANITIZE_STRING);
                $description = filter_input(INPUT_POST,'description',FILTER_SANITIZE_STRING);
                $offer = filter_input(INPUT_POST,'offer',FILTER_SANITIZE_STRING);
                $stock = filter_input(INPUT_POST,'stock',FILTER_SANITIZE_STRING);
                $sku = filter_input(INPUT_POST,'sku',FILTER_SANITIZE_STRING);
                $userId = filter_input(INPUT_POST,'userId',FILTER_SANITIZE_STRING);

                if ($_SESSION["role"] == "vendedor")
                    $userId = $_SESSION["userId"];

                //montando model...
                $product = new Product(
                    null, 
                    $title, 
                    $price, 
                    $description, 
                    null, 
                    'michael', 
                    $offer, 
                    $stock,
                    $sku,
                    $userId,
                    null
                );

                //validando model se tiver ok o service resolve a treta!
                if (!$product->isValid()){
                    $retornoJson = new JsonError(
                        "Não foi possível cadastrar o produto, ocorreram erros de validação verifique a seguir"
                    ); 
                }
                else
                {
                    $imagesUploaded = null;
                    if (isset($_POST['images']))
                        $imagesUploaded = $_POST['images'];

                    $retornoAdd =  $this->productService->add($imagesUploaded, $product);
                    
                    if (is_null($retornoAdd))
                        throw new Exception();

                    $retornoJson = new JsonSuccess("Produto cadastrado com sucesso");
                    header('Content-type:application/json;charset=utf-8');
                }
            } 
            catch(Exception $e)
            {
                $retornoJson = new JsonError("Não foi possivel cadastrar o produto"); 
            }
            echo json_encode($retornoJson);
        }
    }
