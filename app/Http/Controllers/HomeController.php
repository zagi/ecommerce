<?php

namespace App\Http\Controllers;

use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Repositories\SlideRepository;
use Modules\Block\Repositories\BlockRepository;

class HomeController extends Controller
{
    /**
     *
     *
     */
    protected $products;

    protected $slides;

    public function HomeController(ProductRepository $products, SlideRepository $slides, BlockRepository $blocks)
    {
        $this->products = $products;
        $this->slides = $slides;
        $this->blocks = $blocks;
    }

    public function index()
    {
        $slides     = $this->slider();
        $recent     = $this->recent();
        $featured   = $this->featured();
        $blocks     = $this->blocks();
    }

    public function slider($limit = 4)
    {
        return $this->slides->getSlides($limit);
    }

    public function recent($limit = 4)
    {
        return $this->products->getRecent($limit);
    }

    public function featured($limit = 3)
    {
        return $this->products->getFeatured($limit);
    }

    public function blocks($region = 'hp_top')
    {
        return $this->block->getBlocks($region);
    }

}
