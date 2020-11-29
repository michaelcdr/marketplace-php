<?php
    namespace controllers\products;
    
    use models\Product;
    use controllers\IBaseController;
    use services\ProductService;
    use models\JsonError;
    use models\JsonSuccess;
    use Exception;
    use domain\entities\AttributeValue;

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
                $product = new Product(
                    null, 
                    filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING), 
                    filter_input(INPUT_POST,'price',FILTER_SANITIZE_STRING), 
                    filter_input(INPUT_POST,'description',FILTER_SANITIZE_STRING), 
                    null, 
                    'michael', 
                    filter_input(INPUT_POST,'offer',FILTER_SANITIZE_STRING), 
                    filter_input(INPUT_POST,'stock',FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST,'sku',FILTER_SANITIZE_STRING),
                    $_SESSION["role"] == "vendedor" ? $_SESSION["userId"] : filter_input(INPUT_POST,'userId',FILTER_SANITIZE_STRING),
                    null
                );

                $attributesValues = json_decode($_POST['attributesValues']);
                for ($i=0; $i < count($attributesValues); $i++) { 
                    $product->addAttributeValue(
                        new AttributeValue($attributesValues[$i]->attributeId,$product->getId(),$attributesValues[$i]->value)
                    );
                }
                
                if (!$product->isValid())
                    $retornoJson = new JsonError("Não foi possível cadastrar o produto, ocorreram erros de validação verifique a seguir"); 
                else
                {
                    $imagesUploaded = isset($_POST['images']) ? $_POST['images'] : null;
                    $retornoAdd =  $this->productService->add($imagesUploaded, $product);
                    if (is_null($retornoAdd)) throw new Exception();
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
