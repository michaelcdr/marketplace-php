<?php

namespace domain\inputmodels;

class Rating
{
    private $productId;
    private $rating;
    private $recommended;
    private $title;
    private $description;
    private $errors;
    private $approved;
    private $userId;

    public function __construct($productId, $rating, $recommended, $title, $description, $userId, $approved)
    {
        $this->productId = $productId;
        $this->rating = $rating;
        $this->recommended = $recommended;
        $this->title = $title;
        $this->description = $description;
        $this->approved = $approved;
        $this->userId = $userId;
        $this->errors = array();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        if (is_null($this->getTitle()) || $this->getTitle() === "")
            $this->errors['Title'] = 'Informe o título.';

        if (is_null($this->getDescription()) || $this->getDescription() === "")
            $this->errors['Description'] = 'Informe o descritivo.';

        return count($this->errors) === 0;
    }

    /**
     * Get the value of productId
     */ 
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Get the value of rating
     */ 
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Get the value of recommended
     */ 
    public function getRecommended()
    {
        return $this->recommended;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the value of userId
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get the value of approved
     */ 
    public function getApproved()
    {
        return $this->approved;
    }
}