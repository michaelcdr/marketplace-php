<?php
    namespace infra\repositories;    
    use infra\MySqlRepository;
    use infra\interfaces\ICategoryRepository;
    use models\SubCategory;
    use models\PaginatedResults;
    use PDO;

    class SubCategoryRepository 
        extends MySqlRepository 
        implements ISubCategoryRepository
    {


        public function add($subCategory)
        {
            $stmt = $this->conn->prepare(
                "INSERT INTO SubCategories (title, categoryId) 
                values 
                (:title,:categoryId)"
            );
            $stmt->bindValue(':title', $subCategory->getTitle());
            $stmt->bindValue(':categoryId', $subCategory->getCategoryId());
            $stmt->execute();
            
            return $this->conn->lastInsertId();
        }

        public function remove($subCategoryId)
        {
            $stmt = $this->conn->prepare(
                "update Products set subCategoryId = null where subCategoryId = :subCategoryId "
            );
            $stmt->bindParam(':subCategoryId', $subCategoryId);
            $stmt->execute();

            $stmt = $this->conn->prepare(
                "delete from SubCategories where subCategoryId = :subCategoryId "
            );
            $stmt->bindParam(':subCategoryId', $subCategoryId);
            $stmt->execute();
        }

        public function update($subCategory)
        {
            $stmt = $this->conn->prepare(
                "UPDATE categories 
                    set 
                        title = :title, 
                        categoryId = :categoryId
                 where subCategoryId = :subCategoryId");
            
            $stmt->bindValue(':title', $subCategory->getTitle());
            $stmt->bindValue(':categoryId', $subCategory->getCategoryId());
            $stmt->bindValue(':subCategoryId', $subCategory->getSubCategory());
            $stmt->execute();
        }

        public function getById($id)
        {
            $stmt = $this->conn->prepare(
                "SELECT categoryId, title, subCategoryId from SubCategories
                 WHERE subCategoryId = :subCategoryId LIMIT 1"
            );
            $stmt->bindValue(':subCategoryId', intval($id));
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $subCategory = null;
            if ($row) 
                $subCategory = new SubCategory(
                    $row['subCategoryId'], 
                    $row['categoryId'], 
                    $row['title']
                );
            
            return $subCategory;
        }

        public function total($search)
        {
            if (is_null($search) ||  $search === "")
            {
                $stmt = $this->conn->prepare(
                    "SELECT count(SubCategoryId) as total FROM SubCategories "
                );
                $stmt->execute();
                $total = $stmt->fetch();
                return intval($total["total"]);
            }
            else
            {
                $stmt = $this->conn->prepare( 
                    "SELECT count(SubCategoryId) as total FROM SubCategories 
                     WHERE title like :search  " 
                );
                $stmt->bindValue(":search", '%' . $search . '%');
                $total = $stmt->fetch();
                return intval($total["total"]);
            }  
        }

        public function getAll()
        {
            $subCategoriesResults = null;
            $stmt = $this->conn->prepare(
                "SELECT SubCategoryId, title, CategoryId 
                    FROM SubCategories  "
            );
            $stmt->execute();
            $subCategoriesResults = $stmt->fetchAll();
            
            $subCategoriesArray = array();
            foreach($subCategoriesResults as $row){
                $subCategoriesArray[] = new SubCategory(
                    $row["SubCategoryId"], 
                    $row["ategoryId"],
                    $row["title"]
                );
            }

            return $subCategoriesArray;
        }

        public function getAllPaginated($page, $search, $pageSize)
        {
            //configurando variaveis para paginação
            if (!isset($page))
                $page = 0;
            
            if (!isset($pageSize))
                $pageSize = 5;  
            
            $skipNumber = 0;
            if (!is_null($page) && $page > 0)
                $skipNumber = $pageSize * ($page - 1);
            
            $total = $this->total($search);
            //echo "page: " . $page . "<br/> skipNumber: " . $skipNumber . "<br/>";

            //obtendo lista de usuarios...
            $subCategoriesResults = null;
            if (is_null($search) ||  $search === "")
            {
                $stmt = $this->conn->prepare(
                    "SELECT subCategoryId,categoryId, title  
                     FROM SubCategories limit :pageSize OFFSET :skipNumber "
                );
                $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
                $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
                $stmt->execute();
                $subCategoriesResults = $stmt->fetchAll();
            }
            else
            {
                $stmt = $this->conn->prepare( 
                    "SELECT subCategoryId, categoryId, title FROM SubCategories 
                     WHERE title like :search  order by title 
                     limit :pageSize OFFSET :skipNumber " 
                );
                $stmt->bindValue(":search", '%' . $search . '%');
                $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
                $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
                $stmt->execute();
                $subCategoriesResults = $stmt->fetchAll();
            }   
            
            //obtendo dados para controle da paginação
            $numberOfPages = ceil($total / $pageSize);
            $hasPreviousPage = false;
            if ($numberOfPages > 1 && $page > 1)
                $hasPreviousPage = true;

                $hasNextPage = $numberOfPages >= intval($page) ? false :true;
            
            $subCategoriesArray = array();
            foreach($subCategoriesResults as $row){
                $subCategoriesArray[] = new SubCategory(
                    $row["subCategoryId"],
                    $row["categoryId"],
                    $row["image"]
                );
            }

            $paginatedResults = new PaginatedResults(
                $subCategoriesArray, 
                $total, 
                count($subCategoriesArray),
                $hasPreviousPage,
                $hasNextPage,
                $page,
                $numberOfPages,
                "/admin/subcategoria?p="
            );

            return $paginatedResults;
        }
    }
