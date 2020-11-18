<?php

namespace infra\repositories;

use infra\MySqlRepository;
use infra\interfaces\IProductRepository;
use models\Product;
use models\PaginatedResults;
use PDO;

class ProductRepository
extends MySqlRepository
implements IProductRepository
{
    public function totalOfProducts($search, $userId)
    {
        $stmt = null;
        $userClausule = "";
        if (is_null($search) ||  $search === "") {
            if (isset($userId) && $userId != null)
                $userClausule = " where p.userId = :userId ";

            $stmt = $this->conn->prepare("SELECT count(p.ProductId) as total FROM Products p $userClausule");
        } else {
            if (isset($userId) && $userId != null)
                $userClausule = " where p.userId = :userId ";

            $stmt = $this->conn->prepare(
                "SELECT count(p.ProductId) as total FROM Products p
                 WHERE  (p.title like :search or p.description like :search or p.Sku like :search) $userClausule "
            );
            $stmt->bindValue(":search", '%' . $search . '%');
        }
        if (isset($userId) && $userId != null)
            $stmt->bindValue(":userId", $userId);
        $stmt->execute();
        $total = $stmt->fetch();
        return intval($total["total"]);
    }

    public function getAll($page, $search, $userId, $pageSize, $site)
    {
        if (!isset($pageSize))
            $pageSize = 5;

        $skipNumber = 0;

        if (!is_null($page) && $page > 0)
            $skipNumber = $pageSize * ($page - 1);

        $stmt = null;
        $total = $this->totalOfProducts($search, $userId);
        $userClausule = "";
        if (is_null($search) ||  $search === "") {
            if (isset($userId) && $userId != null)
                $userClausule = " where p.userId = :userId ";

            $stmt = $this->conn->prepare(
                "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, 
                            p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName,
                            p.UserId, u.Name as Seller
                            FROM Products p
                    inner join users u on p.userid = u.userid
                    left join (
                        select pi.ProductId, pi.filename as filename
                        from ProductsImages pi     
                    )
                    as Image on p.ProductId = Image.ProductId $userClausule
                    group by p.productid 
                    order by p.title
                    limit :pageSize OFFSET :skipNumber "
            );
        } else {
            if (isset($userId) && $userId != null)
                $userClausule = " and p.userId = :userId ";

            $stmt = $this->conn->prepare(
                "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, 
                            p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName,
                            p.UserId, u.Name as Seller
                            FROM Products p
                    inner join users u on p.userid = u.userid
                    left join (
                        select pi.ProductId, pi.filename as filename
                        from ProductsImages pi     
                    )
                    as Image on p.ProductId = Image.ProductId 
                    where 
                        (p.title like :search or
                        p.description like :search or
                        p.Sku like :search) $userClausule 
                    group by productid 
                    order by p.title 
                    limit :pageSize OFFSET :skipNumber "
            );
            $stmt->bindValue(":search", '%' . $search . '%');
        }

        if (isset($userId) && $userId != null)
            $stmt->bindValue(':userId',  $userId);

        $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
        $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
        $stmt->execute();
        $produtosResult = $stmt->fetchAll();

        $numberOfPages = ceil($total / $pageSize);
        $hasPreviousPage = false;
        if ($numberOfPages > 1 && $page > 1)
            $hasPreviousPage = true;

        $hasNextPage = false;
        if ($numberOfPages > intval($page))
            $hasNextPage = true;
        if (!isset($site)) {
            $site = false;
        }

        return new PaginatedResults(
            $produtosResult,
            $total,
            count($produtosResult),
            $hasPreviousPage,
            $hasNextPage,
            $page,
            $numberOfPages,
            $site ? "/pesquisa?p=" : "/admin/produto?p="
        );
    }

    public function add($product)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO products(Title, Description, Price, CreatedAt, CreatedBy, Offer, Stock, Sku,UserId) 
                values (:title, :desc, :price, now(), :createdBy, :offer, :stock,:sku,:userId);"
        );

        $stmt->bindValue(":title", $product->getTitle());
        $stmt->bindValue(":desc", $product->getDescription());
        $stmt->bindValue(":price", $product->getPrice());
        $stmt->bindValue(":createdBy", $product->getCreatedBy());
        $stmt->bindValue(":offer", $product->getOffer() == 'true' ? 1 : 0, PDO::PARAM_BOOL);
        $stmt->bindValue(":stock", $product->getStock());
        $stmt->bindValue(":sku", $product->getSku());
        $stmt->bindValue(":userId", $product->getUserId());
        if (!$stmt->execute()) {
            return null;
        }

        return $this->conn->lastInsertId();
    }

    public function addImages($productId, $imagesNames)
    {
        foreach ($imagesNames as $image) {
            $stmt = $this->conn->prepare(
                "INSERT INTO productsimages(ProductId, FileName) 
                    values (:ProductId, :FileName);"
            );
            $stmt->bindValue(":ProductId", $productId);
            $stmt->bindValue(":FileName", $image);
            $stmt->execute();
        }
    }

    public function removeAllImages($productId)
    {
        $stmt = $this->conn->prepare("delete from productsimages where productid = :id");
        $stmt->bindValue(':id', $productId);
        $stmt->execute();
    }

    public function remove($id)
    {
        $this->removeAllImages($id);

        //removendo produtos...
        $stmt = $this->conn->prepare("delete from products where productid = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return true;
    }

    public function update($product)
    {
        $stmt = $this->conn->prepare(
            "UPDATE Products set 
                title = :title,
                description = :description,
                offer = :offer,
                stock = :stock,
                sku = :sku,
                userId = :userId,
                price = :price
                where ProductId = :productId"
        );

        $stmt->bindValue(":title", $product->getTitle());
        $stmt->bindValue(":description", $product->getDescription());
        $stmt->bindValue(":offer", $product->getOffer() == 'true' ? 1 : 0, PDO::PARAM_BOOL);
        $stmt->bindValue(":stock", $product->getStock());
        $stmt->bindValue(":sku", $product->getSku());
        $stmt->bindValue(":productId", $product->getId());
        $stmt->bindValue(":price", $product->getPrice());
        $stmt->bindValue(":userId", $product->getUserId());

        $stmt->execute();
    }

    public function getCurrentStock($productId)
    {
        $stmt = $this->conn->prepare(
            "SELECT Stock FROM Products
                WHERE ProductId = :ProductId"
        );
        $stmt->bindValue(":ProductId", $productId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stock = 0;
        if ($row) {
            $stock = $row['Stock'];
        }
        return $stock;
    }

    function getById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.ProductId, p.Title, p.Description, p.Price, 
                        p.CreatedAt, p.CreatedBy, p.Offer, p.Stock, p.Sku,
                         p.UserId  , u.name as Seller, Image.filename as ImageFileName
                FROM Products p
                left join (
                    select pi.ProductId, pi.filename as filename
                    from ProductsImages pi     
                )
                as Image on p.ProductId = Image.ProductId 
                inner join users u on p.userid = u.userid
                WHERE p.ProductId = :ProductId"
        );
        $stmt->bindValue(":ProductId", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $product = null;

        if ($row) {
            $product = new Product(
                $row['ProductId'],
                $row['Title'],
                $row['Price'],
                $row['Description'],
                $row['CreatedAt'],
                $row['CreatedBy'],
                $row['Offer'],
                $row['Stock'],
                $row['Sku'],
                $row['UserId'],
                $row["Seller"]
            );
            $product->setDefaultImage($row["ImageFileName"]);

            $stmt = $this->conn->prepare(
                "select * from productsimages where productid = :ProductId;"
            );
            $stmt->bindValue(":ProductId", $id);
            $stmt->execute();
            $images = $stmt->fetchAll();
            $product->setImages($images);
        }
        return $product;
    }

    public function decreaseStockByOrderItens($orderItens)
    {
        if (isset($orderItens)) {
            foreach ($orderItens as $item) {
                $currentQtd = $this->getCurrentStock($item->getProductId());
                $newQtd = $currentQtd - $item->getQtd();
                $stmt = $this->conn->prepare(
                    "UPDATE products
                            set Stock = :newQtd
                        WHERE productId = :productId; "
                );

                $stmt->bindValue(":newQtd", $newQtd);
                $stmt->bindValue(":productId", $item->getProductId());

                if (!$stmt->execute()) {
                    return null;
                }
            }
        }
    }

    public function getAllByUserIdSeller($userId)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, 
                        p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName,
                        p.UserId, u.Name as Seller
                        FROM Products p
                inner join users u on p.userid = u.userid
                left join (
                    select pi.ProductId, pi.filename as filename
                    from ProductsImages pi     
                )
                as Image on p.ProductId = Image.ProductId 
                where p.userId = :userId 
                group by p.productid 
                order by p.title "
        );

        if (isset($userId) && $userId != null)
            $stmt->bindValue(':userId',  $userId);

        $stmt->execute();
        $produtosResult = $stmt->fetchAll();

        $products = array();
        foreach ($produtosResult as $row) {
            $prod = new Product(
                $row['ProductId'],
                $row['Title'],
                $row['Price'],
                $row['Description'],
                $row['CreatedAt'],
                $row['CreatedBy'],
                $row['Offer'],
                $row['Stock'],
                $row['Sku'],
                $row['UserId'],
                $row["Seller"]
            );
            $prod->setDefaultImage($row["ImageFileName"]);

            $products[] = $prod;
        }
        return $products;
    }

    public function getAllSimilarProducts($productId)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, 
                    p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName, p.UserId 
            FROM Products p
            left join (
                select pi.ProductId, pi.filename as filename
                from ProductsImages pi     
            )
            as Image on p.ProductId = Image.ProductId 
            where p.productId in (SELECT childproductid from similarproducts where parentproductid = :productId) 
            group by p.productid 
            order by p.title "
        );

        $stmt->bindValue(':productId',  $productId);

        $stmt->execute();
        $produtosResult = $stmt->fetchAll();

        $products = array();
        foreach ($produtosResult as $row) {
            $prod = new Product(
                $row['ProductId'],
                $row['Title'],
                $row['Price'],
                $row['Description'],
                $row['CreatedAt'],
                $row['CreatedBy'],
                $row['Offer'],
                $row['Stock'],
                $row['Sku'],
                $row['UserId'],
                ''
            );
            $prod->setDefaultImage($row["ImageFileName"]);

            $products[] = $prod;
        }
        return $products;
    }

    public function totalOfSimilarProducts($search, $productId)
    {
        $stmt = null;
        if (is_null($search) ||  $search === "") {
            $stmt = $this->conn->prepare("SELECT count(childproductid) as total from similarproducts where parentproductid = :productId");
        } else {
            $stmt = $this->conn->prepare(
                "SELECT count(p.ProductId) as total FROM Products p
                 WHERE (p.title like :search orp.description like :search or p.Sku like :search) and p.ProductId in (SELECT childproductid from similarproducts where parentproductid = :productId) "
            );
            $stmt->bindValue(":search", '%' . $search . '%');
        }
        $stmt->bindValue(':productId',  $productId);
        $stmt->execute();
        $total = $stmt->fetch();
        return intval($total["total"]);
    }

    public function getAllSimilarProductsPaginated($productId, $page, $search, $pageSize)
    {
        if (!isset($pageSize))
            $pageSize = 5;

        $skipNumber = 0;

        if (!is_null($page) && $page > 0)
            $skipNumber = $pageSize * ($page - 1);

        $stmt = null;
        $total = $this->totalOfSimilarProducts($search, $productId);

        if (is_null($search) ||  $search === "") {
            $stmt = $this->conn->prepare(
                "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName, p.UserId, u.Name as Seller
                 FROM Products p
                 LEFT JOIN (
                    select pi.ProductId, pi.filename as filename from ProductsImages pi     
                ) as Image on p.ProductId = Image.ProductId 
                where p.ProductId in (SELECT childproductid from similarproducts where parentproductid = :productId)
                GROUP BY p.productid 
                ORDER BY p.title
                limit :pageSize OFFSET :skipNumber "
            );
        } else {
            $stmt = $this->conn->prepare(
                "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName, p.UserId, u.Name as Seller
                 FROM Products p
                 LEFT JOIN (
                        select pi.ProductId, pi.filename as filename from ProductsImages pi     
                 ) as Image on p.ProductId = Image.ProductId 
                 WHERE p.ProductId in (SELECT childproductid from similarproducts where parentproductid = :productId) AND (p.title like :search or p.description like :search or p.Sku like :search) 
                 GROUP BY productid 
                 ORDER BY p.title 
                 LIMIT :pageSize OFFSET :skipNumber "
            );
            $stmt->bindValue(":search", '%' . $search . '%');
        }

        $stmt->bindValue(":productId", intval(trim($productId)), PDO::PARAM_INT);
        $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
        $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
        $stmt->execute();

        $produtosResult = $stmt->fetchAll();


        $numberOfPages = ceil($total / $pageSize);
        $hasPreviousPage = false;
        if ($numberOfPages > 1 && $page > 1)
            $hasPreviousPage = true;

        $hasNextPage = false;
        if ($numberOfPages > intval($page))
            $hasNextPage = true;

        return new PaginatedResults(
            $produtosResult,
            $total,
            count($produtosResult),
            $hasPreviousPage,
            $hasNextPage,
            $page,
            $numberOfPages,
            "/admin/produto?p="
        );
    }

    public function totalOfPossibleChoicesForSimilarProducts($search, $userId, $currentProductId)
    {
        $stmt = null;
        $userClausule = "";
        if (is_null($search) ||  $search === "") {
            if (isset($userId) && $userId != null)
                $userClausule = " and p.userId = :userId ";

            $stmt = $this->conn->prepare("SELECT count(p.ProductId) as total FROM Products p where p.productId <> :currentProductId $userClausule");
        } else {
            if (isset($userId) && $userId != null)
                $userClausule = " and p.userId = :userId ";

            $stmt = $this->conn->prepare(
                "SELECT count(p.ProductId) as total FROM Products p
                 WHERE  p.productId <> :currentProductId and (p.title like :search or p.description like :search or p.Sku like :search) $userClausule "
            );
            $stmt->bindValue(":search", '%' . $search . '%');
        }
        if (isset($userId) && $userId != null)
            $stmt->bindValue(":userId", $userId);

        $stmt->bindValue(":currentProductId", $currentProductId);
        $stmt->execute();
        $total = $stmt->fetch();
        return intval($total["total"]);
    }

    public function getPossibleChoicesForSimilarProducts($page, $search, $userId, $pageSize, $currentProductId)
    {
        $pageSize = (!isset($pageSize)) ? 5 : $pageSize;
        $skipNumber = (!is_null($page) && $page > 0) ?  $pageSize * ($page - 1) : 0;
        $stmt = null;
        $total = $this->totalOfPossibleChoicesForSimilarProducts($search, $userId, $currentProductId);
        $userClausule = "";

        if (is_null($search) ||  $search === "") {
            if (isset($userId) && $userId != null)
                $userClausule = " where p.userId = :userId ";

            $stmt = $this->conn->prepare(
                "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, 
                        p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName,
                        p.UserId, u.Name as Seller
                        FROM Products p
                inner join users u on p.userid = u.userid
                left join (
                    select pi.ProductId, pi.filename as filename
                    from ProductsImages pi     
                )
                as Image on p.ProductId = Image.ProductId 
                WHERE p.productid  <> :currentProductId $userClausule
                group by p.productid 
                order by p.title
                limit :pageSize OFFSET :skipNumber "
            );
        } else {
            if (isset($userId) && $userId != null)
                $userClausule = " and p.userId = :userId ";

            $stmt = $this->conn->prepare(
                "SELECT p.ProductId, p.Title, p.Price, p.Description, p.CreatedAt, 
                        p.CreatedBy, p.Offer, p.Stock, p.Sku, Image.filename as ImageFileName,
                        p.UserId, u.Name as Seller
                FROM Products p
                inner join users u on p.userid = u.userid
                left join (
                    select pi.ProductId, pi.filename as filename
                    from ProductsImages pi     
                )
                as Image on p.ProductId = Image.ProductId 
                where p.productid  <> :currentProductId AND
                    (p.title like :search or
                    p.description like :search or
                    p.Sku like :search) $userClausule 
                group by productid 
                order by p.title 
                limit :pageSize OFFSET :skipNumber "
            );
            $stmt->bindValue(":search", '%' . $search . '%');
        }

        if (isset($userId) && $userId != null)
            $stmt->bindValue(':userId',  $userId);

        $stmt->bindValue(':currentProductId', intval(trim($currentProductId)), PDO::PARAM_INT);
        $stmt->bindValue(':pageSize', intval(trim($pageSize)), PDO::PARAM_INT);
        $stmt->bindValue(':skipNumber', intval(trim($skipNumber)), PDO::PARAM_INT);
        $stmt->execute();

        $produtosResult = $stmt->fetchAll();
        $numberOfPages = ceil($total / $pageSize);
        $hasPreviousPage =  ($numberOfPages > 1 && $page > 1) ? true : false;
        $hasNextPage = ($numberOfPages > intval($page)) ? true : false;

        return new PaginatedResults(
            $produtosResult,
            $total,
            count($produtosResult),
            $hasPreviousPage,
            $hasNextPage,
            $page,
            $numberOfPages,
            "/admin/produto?p="
        );
    }
}
