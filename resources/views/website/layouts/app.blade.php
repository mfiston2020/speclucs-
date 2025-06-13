<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blueberry - Multi Purpose eCommerce Template.">
    <meta name="keywords"
        content="eCommerce, mart, apparel, bootstrap 5, catalog, fashion, html, multipurpose, online store, shop, store, template">
    <title>{{config('app.name')}} - @yield('title')</title>

    <!-- Site Favicon -->
    <link rel="icon" href="assets/img/favicon/favicon.png')}}" type="image/x-icon">

    <!-- Css All Plugins Files -->
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/remixicon.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/aos.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/slick.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/animate.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/jquery-range-ui.css')}}">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('website/assets/css/style.css')}}">
</head>

<body>

    <!-- Loader -->
    <div class="bb-loader">
        <img src="{{ asset('website/assets/img/logo/loader.png')}}" alt="loader">
        <span class="loader"></span>
    </div>

    <!-- Header -->
    @include('website.layouts.navigation')

    <div class="bb-mobile-menu-overlay"></div>

    <div id="bb-mobile-menu" class="bb-mobile-menu">
        <div class="bb-menu-title">
            <span class="menu_title">My Menu</span>
            <button type="button" class="bb-close-menu">×</button>
        </div>
        <div class="bb-menu-inner">
            <div class="bb-menu-content">
                <ul>
                    <li>
                        <a href="javascript:void(0)">Home</a>
                        <ul class="sub-menu">
                            <li><a href="index-2.html">Grocery</a></li>
                            <li><a href="demo-2.html">Fashion</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)">Categories</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="javascript:void(0)">Classic</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-left-sidebar-col-3.html">Left sidebar 3 column</a></li>
                                    <li><a href="shop-left-sidebar-col-4.html">Left sidebar 4 column</a></li>
                                    <li><a href="shop-right-sidebar-col-3.html">Right sidebar 3 column</a></li>
                                    <li><a href="shop-right-sidebar-col-4.html">Right sidebar 4 column</a></li>
                                    <li><a href="shop-full-width.html">Full width 4 column</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Banner</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-banner-left-sidebar-col-3.html">Left sidebar 3 column</a></li>
                                    <li><a href="shop-banner-left-sidebar-col-4.html">Left sidebar 4 column</a></li>
                                    <li><a href="shop-banner-right-sidebar-col-3.html">Right sidebar 3 column</a>
                                    </li>
                                    <li><a href="shop-banner-right-sidebar-col-4.html">Right sidebar 4 column</a>
                                    </li>
                                    <li><a href="shop-banner-full-width.html">Full width 4 column</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Columns</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-full-width-col-3.html">3 Columns full width</a></li>
                                    <li><a href="shop-full-width-col-4.html">4 Columns full width</a></li>
                                    <li><a href="shop-full-width-col-5.html">5 Columns full width</a></li>
                                    <li><a href="shop-full-width-col-6.html">6 Columns full width</a></li>
                                    <li><a href="shop-banner-full-width-col-3.html">Banner 3 Columns</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)">List</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-list-left-sidebar.html">Shop left sidebar</a></li>
                                    <li><a href="shop-list-right-sidebar.html">Shop right sidebar</a></li>
                                    <li><a href="shop-list-banner-left-sidebar.html">Banner left sidebar</a></li>
                                    <li><a href="shop-list-banner-right-sidebar.html">Banner right sidebar</a></li>
                                    <li><a href="shop-list-full-col-2.html">Full width 2 columns</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)">Products</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="javascript:void(0)">Product page</a>
                                <ul class="sub-menu">
                                    <li><a href="product-left-sidebar.html">Product left sidebar</a></li>
                                    <li><a href="product-right-sidebar.html">Product right sidebar</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Product Accordion</a>
                                <ul class="sub-menu">
                                    <li><a href="product-accordion-left-sidebar.html">left sidebar</a></li>
                                    <li><a href="product-accordion-right-sidebar.html">right sidebar</a></li>
                                </ul>
                            </li>
                            <li><a href="product-full-width.html">Product full width</a></li>
                            <li><a href="product-accordion-full-width.html">accordion full width</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)">Pages</a>
                        <ul class="sub-menu">
                            <li><a href="about-us.html">About Us</a></li>
                            <li><a href="contact-us.html">Contact Us</a></li>
                            <li><a href="cart.html">Cart</a></li>
                            <li><a href="checkout.html">Checkout</a></li>
                            <li><a href="compare.html">Compare</a></li>
                            <li><a href="faq.html">Faq</a></li>
                            <li><a href="login.html">Login</a></li>
                            <li><a href="register.html">Register</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)">Blog</a>
                        <ul class="sub-menu">
                            <li><a href="blog-left-sidebar.html">Left Sidebar</a></li>
                            <li><a href="blog-right-sidebar.html">Right Sidebar</a></li>
                            <li><a href="blog-full-width.html">Full Width</a></li>
                            <li><a href="blog-detail-left-sidebar.html">Detail Left Sidebar</a></li>
                            <li><a href="blog-detail-right-sidebar.html">Detail Right Sidebar</a></li>
                            <li><a href="blog-detail-full-width.html">Detail Full Width</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="header-res-lan-curr">
                <!-- Social Start -->
                <div class="header-res-social">
                    <div class="header-top-social">
                        <ul class="mb-0">
                            <li class="list-inline-item">
                                <a href="#"><i class="ri-facebook-fill"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#"><i class="ri-twitter-fill"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#"><i class="ri-instagram-line"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#"><i class="ri-linkedin-fill"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Social End -->
            </div>
        </div>
    </div>

        @yield('contents')

    <!-- Footer -->
    @include('website.layouts.footer')

    <!-- Cart sidebar -->
    <div class="bb-side-cart-overlay"></div>
    
    <div class="bb-side-cart">
        <div class="row h-full">
            <div class="col-md-5 col-12 d-none-767">
                <div class="bb-top-contact">
                    <div class="bb-cart-title">
                        <h4>Related Items</h4>
                    </div>
                </div>
                <div class="bb-cart-box mb-minus-24 cart-related bb-border-right">
                    <div class="bb-deal-card-2 mb-24">
                        <div class="bb-pro-box-2">
                            <div class="bb-pro-img">
                                <span class="flags">
                                    <span>Hot</span>
                                </span>
                                <a href="javascript:void(0)">
                                    <div class="inner-img">
                                        <img class="main-img" src="{{ asset('website/assets/img/product/26.jpg')}}" alt="product-2">
                                        <img class="hover-img" src="{{ asset('website/assets/img/product/26.jpg')}}" alt="product-2">
                                    </div>
                                </a>
                                <ul class="bb-pro-actions">
                                    <li class="bb-btn-group">
                                        <a href="javascript:void(0)" title="Wishlist">
                                            <i class="ri-heart-line"></i>
                                        </a>
                                    </li>
                                    <li class="bb-btn-group">
                                        <a href="javascript:void(0)" data-link-action="quickview" title="Quick View"
                                            data-bs-toggle="modal" data-bs-target="#bry_quickview_modal">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </li>
                                    <li class="bb-btn-group">
                                        <a href="compare.html" title="Compare">
                                            <i class="ri-repeat-line"></i>
                                        </a>
                                    </li>
                                    <li class="bb-btn-group">
                                        <a href="javascript:void(0)" title="Add To Cart">
                                            <i class="ri-shopping-bag-4-line"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bb-pro-contact">
                                <div class="bb-pro-subtitle">
                                    <a href="shop-left-sidebar-col-3.html">Perfumes</a>
                                    <span class="bb-pro-rating">
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-line"></i>
                                    </span>
                                </div>
                                <h4 class="bb-pro-title"><a href="product-left-sidebar.html">Long lasting perfumes for women</a></h4>
                                <div class="bb-price">
                                    <div class="inner-price">
                                        <span class="new-price">$15</span>
                                        <span class="item-left">3 Left</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bb-cart-banner mb-24">
                        <div class="banner">
                            <img src="{{ asset('website/assets/img/category/cart-banner-2.jpg')}}" alt="cart-banner">
                            <div class="detail">
                                <h4>Naturals</h4>
                                <h3>Cosmetics</h3>
                                <a href="shop-left-sidebar-col-3.html">Buy Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-12">
                <div class="bb-inner-cart">
                    <div class="bb-top-contact">
                        <div class="bb-cart-title">
                            <h4>My cart</h4>
                            <a href="javascript:void(0)" class="bb-cart-close" title="Close Cart"></a>
                        </div>
                    </div>
                    <div class="bb-cart-box item">
                        <ul class="bb-cart-items">
                            <li class="cart-sidebar-list">
                                <a href="javascript:void(0)" class="cart-remove-item"><i class="ri-close-line"></i></a>
                                <a href="javascript:void(0)" class="bb-cart-pro-img">
                                    <img src="{{ asset('website/assets/img/product/11.jpg')}}" alt="product-img-1">
                                </a>
                                <div class="bb-cart-contact">
                                    <a href="product-left-sidebar.html" class="bb-cart-sub-title">Sports T-shirt for men</a>
                                    <span class="cart-price">
                                        <span class="new-price">$99</span>
                                    </span>
                                    <div class="qty-plus-minus">
                                        <input class="qty-input" type="text" name="bb-qtybtn" value="1">
                                    </div>
                                </div>
                            </li>
                            <li class="cart-sidebar-list">
                                <a href="javascript:void(0)" class="cart-remove-item"><i class="ri-close-line"></i></a>
                                <a href="javascript:void(0)" class="bb-cart-pro-img">
                                    <img src="{{ asset('website/assets/img/product/17.jpg')}}" alt="product-img-2">
                                </a>
                                <div class="bb-cart-contact">
                                    <a href="product-left-sidebar.html" class="bb-cart-sub-title">Leather sandals for men</a>
                                    <span class="cart-price">
                                        <span class="new-price">$25</span>
                                    </span>
                                    <div class="qty-plus-minus">
                                        <input class="qty-input" type="text" name="bb-qtybtn" value="1">
                                    </div>
                                </div>
                            </li>
                            <li class="cart-sidebar-list">
                                <a href="javascript:void(0)" class="cart-remove-item"><i class="ri-close-line"></i></a>
                                <a href="javascript:void(0)" class="bb-cart-pro-img">
                                    <img src="{{ asset('website/assets/img/product/24.jpg')}}" alt="product-img-3">
                                </a>
                                <div class="bb-cart-contact">
                                    <a href="product-left-sidebar.html" class="bb-cart-sub-title">Handbag Purses</a>
                                    <span class="cart-price">
                                        <span class="new-price">$151</span>
                                    </span>
                                    <div class="qty-plus-minus">
                                        <input class="qty-input" type="text" name="bb-qtybtn" value="1">
                                    </div>
                                </div>
                            </li>
                            <li class="cart-sidebar-list">
                                <a href="javascript:void(0)" class="cart-remove-item"><i class="ri-close-line"></i></a>
                                <a href="javascript:void(0)" class="bb-cart-pro-img">
                                    <img src="{{ asset('website/assets/img/product/6.jpg')}}" alt="product-img-3">
                                </a>
                                <div class="bb-cart-contact">
                                    <a href="product-left-sidebar.html" class="bb-cart-sub-title">Half Sleeve T-shirt</a>
                                    <span class="cart-price">
                                        <span class="new-price">$25</span>
                                    </span>
                                    <div class="qty-plus-minus">
                                        <input class="qty-input" type="text" name="bb-qtybtn" value="1">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="bb-bottom-cart">
                        <div class="cart-sub-total">
                            <table class="table cart-table">
                                <tbody>
                                    <tr>
                                        <td class="title">Sub-Total :</td>
                                        <td class="price">$300.00</td>
                                    </tr>
                                    <tr>
                                        <td class="title">VAT (20%) :</td>
                                        <td class="price">$60.00</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Total :</td>
                                        <td class="price">$360.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="cart-btn">
                            <a href="cart.html" class="bb-btn-1">View Cart</a>
                            <a href="checkout.html" class="bb-btn-2">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Popup -->
    <div class="bb-category-sidebar">
        <div class="bb-category-overlay"></div>
        <div class="category-sidebar">
            <button type="button" class="bb-category-close" title="Close"></button>
            <div class="container-fluid">
                <div class="row mb-minus-24">
                    <div class="col-12">
                        <div class="bb-category-tags">
                            <div class="sub-title">
                                <h4>keywords</h4>
                            </div>
                            <div class="bb-tags">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)">Clothes</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Fruits</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Snacks</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Dairy</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Seafood</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Toys</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">perfume</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">jewelry</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Bags</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="sub-title">
                                    <h4>Explore Categories</h4>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-24">
                                <div class="bb-category-box category-items-1">
                                    <div class="category-image">
                                        <img src="{{ asset('website/assets/img/category/1.svg')}}" alt="category">
                                    </div>
                                    <div class="category-sub-contact">
                                        <h5><a href="shop-left-sidebar-col-3.html">vegetables</a></h5>
                                        <p>485 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-24">
                                <div class="bb-category-box category-items-2">
                                    <div class="category-image">
                                        <img src="{{ asset('website/assets/img/category/2.svg')}}" alt="category">
                                    </div>
                                    <div class="category-sub-contact">
                                        <h5><a href="shop-left-sidebar-col-3.html">Fruits</a></h5>
                                        <p>291 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-24">
                                <div class="bb-category-box category-items-3">
                                    <div class="category-image">
                                        <img src="{{ asset('website/assets/img/category/3.svg')}}" alt="category">
                                    </div>
                                    <div class="category-sub-contact">
                                        <h5><a href="shop-left-sidebar-col-3.html">Cold Drinks</a></h5>
                                        <p>49 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-24">
                                <div class="bb-category-box category-items-4">
                                    <div class="category-image">
                                        <img src="{{ asset('website/assets/img/category/4.svg')}}" alt="category">
                                    </div>
                                    <div class="category-sub-contact">
                                        <h5><a href="shop-left-sidebar-col-3.html">Bakery</a></h5>
                                        <p>08 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-24">
                                <div class="bb-category-box category-items-2">
                                    <div class="category-image">
                                        <img src="{{ asset('website/assets/img/category/5.svg')}}" alt="category">
                                    </div>
                                    <div class="category-sub-contact">
                                        <h5><a href="shop-left-sidebar-col-3.html">Fast Food</a></h5>
                                        <p>291 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-24">
                                <div class="bb-category-box category-items-3">
                                    <div class="category-image">
                                        <img src="{{ asset('website/assets/img/category/6.svg')}}" alt="category">
                                    </div>
                                    <div class="category-sub-contact">
                                        <h5><a href="shop-left-sidebar-col-3.html">Snacks</a></h5>
                                        <p>49 items</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="sub-title">
                                    <h4>Related products</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-24">
                                <div class="bb-category-cart">
                                    <a href="javascript:void(0)" class="pro-img">
                                        <img src="{{ asset('website/assets/img/product/1.jpg')}}" alt="new-product-1">
                                    </a>
                                    <div class="side-contact">
                                        <h4 class="bb-pro-title"><a href="product-left-sidebar.html">Ground Nuts Oil
                                                Pack</a></h4>
                                        <span class="bb-pro-rating">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-line"></i>
                                        </span>
                                        <div class="inner-price">
                                            <span class="new-price">$15</span>
                                            <span class="old-price">$22</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-24">
                                <div class="bb-category-cart">
                                    <a href="javascript:void(0)" class="pro-img">
                                        <img src="{{ asset('website/assets/img/product/2.jpg')}}" alt="new-product-2">
                                    </a>
                                    <div class="side-contact">
                                        <h4 class="bb-pro-title"><a href="product-left-sidebar.html">Organic Litchi
                                                Juice</a>
                                        </h4>
                                        <span class="bb-pro-rating">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-line"></i>
                                        </span>
                                        <div class="inner-price">
                                            <span class="new-price">$25</span>
                                            <span class="old-price">$30</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-24">
                                <div class="bb-category-cart">
                                    <a href="javascript:void(0)" class="pro-img">
                                        <img src="{{ asset('website/assets/img/product/3.jpg')}}" alt="new-product-3">
                                    </a>
                                    <div class="side-contact">
                                        <h4 class="bb-pro-title"><a href="product-left-sidebar.html">Spicy Banana
                                                Chips</a></h4>
                                        <span class="bb-pro-rating">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-line"></i>
                                        </span>
                                        <div class="inner-price">
                                            <span class="new-price">$01</span>
                                            <span class="old-price">$02</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-24">
                                <div class="bb-category-cart">
                                    <a href="javascript:void(0)" class="pro-img">
                                        <img src="{{ asset('website/assets/img/product/4.jpg')}}" alt="new-product-4">
                                    </a>
                                    <div class="side-contact">
                                        <h4 class="bb-pro-title"><a href="product-left-sidebar.html">Spicy Potato
                                                Chips</a></h4>
                                        <span class="bb-pro-rating">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-line"></i>
                                        </span>
                                        <div class="inner-price">
                                            <span class="new-price">$25</span>
                                            <span class="old-price">$35</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-24">
                                <div class="bb-category-cart">
                                    <a href="javascript:void(0)" class="pro-img">
                                        <img src="{{ asset('website/assets/img/product/5.jpg')}}" alt="new-product-5">
                                    </a>
                                    <div class="side-contact">
                                        <h4 class="bb-pro-title"><a href="product-left-sidebar.html">Black Pepper
                                                Spice</a>
                                        </h4>
                                        <span class="bb-pro-rating">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-line"></i>
                                        </span>
                                        <div class="inner-price">
                                            <span class="new-price">$32</span>
                                            <span class="old-price">$35</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-24">
                                <div class="bb-category-cart">
                                    <a href="javascript:void(0)" class="pro-img">
                                        <img src="{{ asset('website/assets/img/product/6.jpg')}}" alt="new-product-6">
                                    </a>
                                    <div class="side-contact">
                                        <h4 class="bb-pro-title"><a href="product-left-sidebar.html">Small Chili
                                                Spice</a>
                                        </h4>
                                        <span class="bb-pro-rating">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-line"></i>
                                        </span>
                                        <div class="inner-price">
                                            <span class="new-price">$41</span>
                                            <span class="old-price">$45</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Modal -->
    <div class="bb-popnews-bg"></div>

    <div class="bb-popnews-box">
        <div class="bb-popnews-close" title="Close"></div>
        <div class="row">
            <div class="col-md-6 col-12">
                <img src="{{ asset('website/assets/img/newsletter/newsletter-2.jpg')}}" alt="newsletter">
            </div>
            <div class="col-md-6 col-12">
                <div class="bb-popnews-box-content">
                    <h2>Newsletter.</h2>
                    <p>Subscribe the BlueBerry to get in touch and get the future update.</p>
                    <form class="bb-popnews-form" action="#" method="post">
                        <input type="email" name="newsemail" placeholder="Email Address" required>
                        <button type="button" class="bb-btn-2" name="subscribe">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Plugins -->
    <script src="{{ asset('website/assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/jquery.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/jquery.zoom.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/aos.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/swiper-bundle.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/smoothscroll.min.js')}}"></script>
    {{-- <script src="{{ asset('website/assets/js/vendor/countdownTimer.js')}}"></script> --}}
    <script src="{{ asset('website/assets/js/vendor/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/slick.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/jquery-range-ui.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/tilt.jquery.min.js')}}"></script>

    <!-- main-js -->
    <script src="{{ asset('website/assets/js/main.js')}}"></script>
</body>
</html>