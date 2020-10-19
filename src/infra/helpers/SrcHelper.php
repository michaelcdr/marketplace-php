<?php

namespace infra\helpers;

class SrcHelper
{
    public static function getCarouselSrc()
    {
        return "/assets/img/carousel/";
    }

    public static function getDefaultSearchSrc()
    {
        return "/assets/js/models/";
    }

    public static function getProductImg()
    {
        return "/assets/img/products/";
    }

    public static function getCategoryImg()
    {
        return "/assets/img/categories/";
    }

    public static function getProductAdminJs(){
        return "/assets/js/admin/product/";
    }
}
