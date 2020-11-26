<?php
    namespace infra\repositories;    
    use infra\MySqlRepository;
    use infra\interfaces\ICategoryRepository;
    use models\Category;
    use models\PaginatedResults;
    use PDO;
    use infra\helpers\SrcHelper;

    class CategoryRepository  extends MySqlRepository implements ICategoryRepository
    {
        public function add($category)
        {
            $stmt = $this->conn->prepare(
                "INSERT INTO categories (title, image) 
                values 
                (:title,:image)"
            );
            $stmt->bindValue(':title', $category->getTitle());
            $stmt->bindValue(':image',$category->getImage());
            $stmt->execute();
            
            return $this->conn->lastInsertId();
        }

        public function remove($categoryId)
        {
            $stmt = $this->conn->prepare(
                "delete from categories where categoryId = :categoryId "
            );
            $stmt->bindParam(':categoryId', $categoryId);
            $stmt->execute();
        }

        public function update($category)
        {
            $stmt = $this->conn->prepare(
                "UPDATE categories 
                    set 
                        title = :title, 
                        image = :image
                 where categoryId = :categoryId");
            
            $stmt->bindValue(':title', $category->getTitle());
            $stmt->bindValue(':image', $category->getImage());
            $stmt->bindValue(':categoryId', $category->getCategoryId());
            $stmt->execute();
        }

        public function getById($id)
        {
            $stmt = $this->conn->prepare(
                "SELECT categoryId, title, image from Categories
                 WHERE categoryId = :categoryId LIMIT 1"
            );
            $stmt->bindValue(':categoryId', intval($id));
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $category = null;
            if ($row) 
                $category = new Category(
                    $row['categoryId'], 
                    $row['title'], 
                    SrcHelper::getCategoryImg() . $row['image']
                );
            
            return $category;
        }

        public function total($search)
        {
            if (is_null($search) ||  $search === "")
            {
                $stmt = $this->conn->prepare(
                    "SELECT count(CategoryId) as total FROM categories "
                );
                $stmt->execute();
                $total = $stmt->fetch();
                return intval($total["total"]);
            }
            else
            {
                $stmt = $this->conn->prepare( 
                    "SELECT count(CategoryId) as total FROM categories 
                     WHERE title like :search  " 
                );
                $stmt->bindValue(":search", '%' . $search . '%');
                $total = $stmt->fetch();
                return intval($total["total"]);
            }  
        }

        public function getAll()
        {
            $categoriesResults = null;
            $stmt = $this->conn->prepare(
                "SELECT categoryId, title, image 
                    FROM categories  "
            );
            $stmt->execute();
            $categoriesResults = $stmt->fetchAll();
            
            $categoriesArray = array();
            foreach($categoriesResults as $row){
                $categoriesArray[] = new Category(
                    $row["categoryId"], 
                    $row["title"], 
                    SrcHelper::getCategoryImg() . $row["image"]
                );
            }

            return $categoriesArray;
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
            $categoriesResults = null;
            if (is_null($search) ||  $search === "")
            {
                $stmt = $this->conn->prepare(
                    "SELECT categoryId, title, image 
                     FROM categories limit :pageSize OFFSET :skipNumber "
                );
                $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
                $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
                $stmt->execute();
                $categoriesResults = $stmt->fetchAll();
            }
            else
            {
                $stmt = $this->conn->prepare( 
                    "SELECT categoryId, title, image FROM categories 
                     WHERE title like :search  order by title 
                     limit :pageSize OFFSET :skipNumber " 
                );
                $stmt->bindValue(":search", '%' . $search . '%');
                $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
                $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
                $stmt->execute();
                $categoriesResults = $stmt->fetchAll();
            }   

            $categoriesArray = array();
            foreach($categoriesResults as $row)
                $categoriesArray[] = new Category($row["categoryId"], $row["title"],$row["image"]);
            
            return new PaginatedResults(
                $categoriesArray, $total, count($categoriesArray), $page, $pageSize, "/admin/categoria?p="
            );
        }
    }
