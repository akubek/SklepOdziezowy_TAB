<?php

class HomeController {
    private $categoryManager;
    private $productManager;

    // Przekazujemy productManager na wypadek, gdybyś chciał pokazać produkty na głównej
    public function __construct($categoryManager, $productManager) {
        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
    }

    public function index() {
        $mainCategories = $this->categoryManager->getSubCategories(1);
        $latestProducts = $this->productManager->getLatestProducts(4);
        require BASE_PATH . '/views/home.php';
    }
}
