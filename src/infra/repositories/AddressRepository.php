<?php
    namespace infra\repositories;    
    use infra\MySqlRepository;
    use infra\interfaces\IAddressRepository;
    use models\Address;
    use models\PaginatedResults;
    use PDO;

    class AddressRepository 
        extends MySqlRepository 
        implements IAddressRepository
    { 
        public function add($address)
        {
            $stmt = $this->conn->prepare(
                "insert into addresses (street, cep, neighborhood, city, stateid, complement,userId)
                values(:street,:cep,:neighborhood,:city,:stateid,:complement,:userId);"
            );
            $stmt->bindValue(':street', $address->getStreet());
            $stmt->bindValue(':cep', $address->getCep());
            $stmt->bindValue(':neighborhood', $address->getNeighborhood());
            $stmt->bindValue(':city', $address->getCity());
            $stmt->bindValue(':stateid', intval($address->getStateId()));
            $stmt->bindValue(':complement', $address->getComplement());
            $stmt->bindValue(':userId', intval($address->getUserId()));
            $stmt->execute();
            
            return $this->conn->lastInsertId();
        }

        public function removeAllByUserId($userId)
        {
            $stmt = $this->conn->prepare(
                "delete from addresses where userId = :userId "
            );
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
        }

        public function remove($addressId)
        {
            $stmt = $this->conn->prepare(
                "delete from addresses where addressId = :addressId "
            );
            $stmt->bindParam(':addressId', $addressId);
            $stmt->execute();
        }

        public function update($address)
        {
            $stmt = $this->conn->prepare(
                "UPDATE addresses 
                    set 
                        street = :street, 
                        cep = :cep,
                        neighborhood = :neighborhood, 
                        city = :city,
                        stateid = :stateid, 
                        complement = :complement,
                        userId = :userId
                 where addressId = :addressId"
            );
            
            $stmt->bindValue(':street', $address->getStreet());
            $stmt->bindValue(':cep', $address->getCep());
            $stmt->bindValue(':neighborhood', $address->getNeighborhood());
            $stmt->bindValue(':city', $address->getCity());
            $stmt->bindValue(':stateid', $address->getStateId());
            $stmt->bindValue(':complement', $address->getComplement());
            $stmt->bindValue(':userId', $address->getUserId());
            $stmt->bindValue(':addressId', $address->getAddressId());
            $stmt->execute();
            
            return $this->conn->lastInsertId();
        }

        public function getAllByUserId($userId)
        {
            $stmt = $this->conn->prepare(
                "SELECT * from addresses where userId = :userId"
            );
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();
            $addressesResults = $stmt->fetchAll();

            //colocando resultados em um array de objetos
            $addressesArray = array();
            foreach($addressesResults as $row){
                $addressesArray[] = new Address(
                    $row["AddressId"], 
                    $row["UserId"],
                    $row["Street"], 
                    $row["CEP"],
                    $row["Neighborhood"],
                    $row["City"],
                    $row["StateId"],
                    $row["Complement"]
                );
            }

            return $addressesArray;
        }

        public function getFirstByUserId($userId)
        {
            $stmt = $this->conn->prepare(
                "SELECT * from addresses where userId = :userId LIMIT 1"
            );
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $address = null;
            if ($row) 
                $address = new Address(
                    $row["AddressId"], 
                    $row["UserId"],
                    $row["Street"], 
                    $row["CEP"],
                    $row["Neighborhood"],
                    $row["City"],
                    $row["StateId"],
                    $row["Complement"]
                );
            
            return $address;
        }

        // public function total($search)
        // {
        //     if (is_null($search) ||  $search === "")
        //     {
        //         $stmt = $this->conn->prepare(
        //             "SELECT count(CategoryId) as total FROM categories "
        //         );
        //         $stmt->execute();
        //         $total = $stmt->fetch();
        //         return intval($total["total"]);
        //     }
        //     else
        //     {
        //         $stmt = $this->conn->prepare( 
        //             "SELECT count(CategoryId) as total FROM categories 
        //              WHERE title like :search  " 
        //         );
        //         $stmt->bindValue(":search", '%' . $search . '%');
        //         $total = $stmt->fetch();
        //         return intval($total["total"]);
        //     }  
        // }

        // public function getAll()
        // {
        //     $categoriesResults = null;
        //     $stmt = $this->conn->prepare(
        //         "SELECT categoryId, title, image 
        //             FROM categories  "
        //     );
        //     $stmt->execute();
        //     $categoriesResults = $stmt->fetchAll();
            
        //     $categoriesArray = array();
        //     foreach($categoriesResults as $row){
        //         $categoriesArray[] = new Category(
        //             $row["categoryId"], $row["title"],$row["image"]
        //         );
        //     }

        //     return $categoriesArray;
        // }

        // public function getAllPaginated($page, $search, $pageSize)
        // {
        //     //configurando variaveis para paginação
        //     if (!isset($page))
        //         $page = 0;
            
        //     if (!isset($pageSize))
        //         $pageSize = 5;  
            
        //     $skipNumber = 0;
        //     if (!is_null($page) && $page > 0)
        //         $skipNumber = $pageSize * ($page - 1);
            
        //     $total = $this->total($search);
        //     //echo "page: " . $page . "<br/> skipNumber: " . $skipNumber . "<br/>";

        //     //obtendo lista de usuarios...
        //     $categoriesResults = null;
        //     if (is_null($search) ||  $search === "")
        //     {
        //         $stmt = $this->conn->prepare(
        //             "SELECT categoryId, title, image 
        //              FROM categories limit :pageSize OFFSET :skipNumber "
        //         );
        //         $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
        //         $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
        //         $stmt->execute();
        //         $categoriesResults = $stmt->fetchAll();
        //     }
        //     else
        //     {
        //         $stmt = $this->conn->prepare( 
        //             "SELECT categoryId, title, image FROM categories 
        //              WHERE title like :search  order by title 
        //              limit :pageSize OFFSET :skipNumber " 
        //         );
        //         $stmt->bindValue(":search", '%' . $search . '%');
        //         $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
        //         $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
        //         $stmt->execute();
        //         $categoriesResults = $stmt->fetchAll();
        //     }   
            
        //     //obtendo dados para controle da paginação
        //     $numberOfPages = ceil($total / $pageSize);
        //     $hasPreviousPage = false;
        //     if ($numberOfPages > 1 && $page > 1)
        //         $hasPreviousPage = true;

        //     $hasNextPage = false;
        //     if ($numberOfPages > intval($page))
        //         $hasNextPage = true;
            
        //     $categoriesArray = array();
        //     foreach($categoriesResults as $row){
        //         $categoriesArray[] = new Category(
        //             $row["categoryId"], $row["title"],$row["image"]
        //         );
        //     }

        //     $paginatedResults = new PaginatedResults(
        //         $categoriesArray, 
        //         $total, 
        //         count($categoriesArray),
        //         $hasPreviousPage,
        //         $hasNextPage,
        //         $page,
        //         $numberOfPages,
        //         "/admin/categoria?p="
        //     );

        //     return $paginatedResults;
        // }
    }
?>