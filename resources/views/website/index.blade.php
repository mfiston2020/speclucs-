@extends('website.layouts.app')

@section('contents')
    <!-- Category -->
    <section class="section-category-2 padding-tb-30 next">
        <div class="container">
            <div class="bb-cat-2">
                <div class="bb-category-block-2 owl-carousel">
                    
                    @foreach ($categories as $category)
                        <div class="bb-category-box" data-aos="flip-left" data-aos-duration="1000" data-aos-delay="200">
                            <div class="category-detail">
                                <div class="category-image">
                                    <img src="{{ asset('website/assets/img/category/4.jpg')}}" alt="category">
                                </div>
                                <div class="category-sub-contact">
                                    <h5><a href="shop-left-sidebar-col-3.html">{{$category->name}}</a></h5>
                                    <p>{{ number_format($category->products_count) }} items</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

    <!-- Hero -->
    <section class="section-hero-2 margin-b-50">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="hero-slider-2 swiper-container">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide slide-2">
                                <div class="row">
                                    <div class="col-md-12 col-lg-8">
                                        <div class="hero-img">
                                            <img src="{{ asset('website/assets/img/hero/3.jpg') }}" alt="hero">
                                            <p>Flat 20% Off</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="hero-contact">
                                            <div class="hero-detail">
                                                <h2>Explore Best <span>Hard Cases</span><br></h2>
                                                <p>Flawless looks, endless confidence. Fashion that feels like you.</p>
                                                <a href="shop-left-sidebar-col-3.html" class="bb-btn-1">Shop Now</a>
                                            </div>
                                            <div class="cat-card">
                                                <ul>
                                                    <li>
                                                        <img src="{{ asset('website/assets/img/hero/8.jpg')}}" alt="hero">
                                                        <div class="detail">
                                                            <a href="shop-left-sidebar-col-3.html">Baby Suit</a>
                                                            <p>$19</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <img src="{{ asset('website/assets/img/hero/9.jpg')}}" alt="hero">
                                                        <div class="detail">
                                                            <a href="shop-left-sidebar-col-3.html">Kids Shoe</a>
                                                            <p>$42</p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide slide-2">
                                <div class="row">
                                    <div class="col-md-12 col-lg-8">
                                        <div class="hero-img">
                                            <img src="{{ asset('website/assets/img/hero/2.jpg')}}" alt="hero">
                                            <p>Flat 50% Off</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="hero-contact">
                                            <div class="hero-detail">
                                                <h2>Explore <span>Frames</span><br></h2>
                                                <p>Flawless looks, endless confidence. Fashion that feels like you.</p>
                                                <a href="shop-left-sidebar-col-3.html" class="bb-btn-1">Shop Now</a>
                                            </div>
                                            <div class="cat-card">
                                                <ul>
                                                    <li>
                                                        <img src="{{ asset('website/assets/img/hero/6.jpg')}}" alt="hero">
                                                        <div class="detail">
                                                            <a href="shop-left-sidebar-col-3.html">Heels</a>
                                                            <p>$99</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <img src="{{ asset('website/assets/img/hero/7.jpg')}}" alt="hero">
                                                        <div class="detail">
                                                            <a href="shop-left-sidebar-col-3.html">Purses</a>
                                                            <p>$487</p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide slide-1">
                                <div class="row">
                                    <div class="col-md-12 col-lg-8">
                                        <div class="hero-img">
                                            <img src="{{ asset('website/assets/img/hero/1.jpg')}}" alt="hero">
                                            <p>Flat 30% Off</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="hero-contact">
                                            <div class="hero-detail">
                                                <h2>Explore <span>Sunglasses</span><br></h2>
                                                <p>Bold choices, timeless appeal. Stand out with every step.</p>
                                                <a href="shop-left-sidebar-col-3.html" class="bb-btn-1">Shop Now</a>
                                            </div>
                                            <div class="cat-card">
                                                <ul>
                                                    <li>
                                                        <img src="{{ asset('website/assets/img/hero/4.jpg')}}" alt="hero">
                                                        <div class="detail">
                                                            <a href="shop-left-sidebar-col-3.html">Shoes</a>
                                                            <p>$55</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <img src="{{ asset('website/assets/img/hero/5.jpg')}}" alt="hero">
                                                        <div class="detail">
                                                            <a href="shop-left-sidebar-col-3.html">Watches</a>
                                                            <p>$145</p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination swiper-pagination-white"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Product tab Area -->
    <section class="section-product-tabs padding-tb-50">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title bb-deal" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                        <div class="section-detail">
                            <h2 class="bb-title">New <span>Arrivals</span></h2>
                            <p>Shop online for new arrivals and get free shipping!</p>
                        </div>
                        <div class="bb-pro-tab">
                            <ul class="bb-pro-tab-nav nav">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#all">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#clothes">Clothes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#footwear">Footwear</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#accessories">accessories</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-minus-24">
                <div class="col">
                    <div class="tab-content">
                        <!-- 1st Product tab start -->
                        <div class="tab-pane fade show active" id="all">
                            <div class="row">
                                @foreach ($products as $product)
                                    <div class="col-xl-3 col-md-4 col-6 mb-24 bb-product-box-2" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                                        <a href="product-left-sidebar.html">
                                            <div class="bb-pro-box-2">
                                                <div class="bb-pro-img">
                                                    {{-- <span class="flags">
                                                        <span>New</span>
                                                    </span> --}}
                                                    <a href="javascript:void(0)">
                                                        <div class="inner-img" style="height: 15rem;">
                                                            <center>
                                                                <img class="main-img" src="{{ Storage::disk('product_picture')->url($product->picture)}}" alt="{{$product->product_name}}">
                                                                <img class="hover-img" src="{{ Storage::disk('product_picture')->url($product->picture)}}" alt="{{$product->product_name}}">
                                                            </center>
                                                        </div>
                                                    </a>
                                                    <ul class="bb-pro-actions">
                                                        <li class="bb-btn-group">
                                                            <a href="javascript:void(0)" title="Wishlist">
                                                                <i class="ri-heart-line"></i>
                                                            </a>
                                                        </li>
                                                        <li class="bb-btn-group">
                                                            <a href="javascript:void(0)" data-link-action="quickview"
                                                                title="Quick View" data-bs-toggle="modal"
                                                                data-bs-target="#bry_quickview_modal">
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
                                                        <a href="/">{{$product->product_name}}</a>
                                                        <span class="bb-pro-rating">
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-line"></i>
                                                            <i class="ri-star-line"></i>
                                                        </span>
                                                    </div>
                                                    <h4 class="bb-pro-title"><a href="product-left-sidebar.html">{{$product->product_name}}</a></h4>
                                                    <div class="bb-price">
                                                        <div class="inner-price">
                                                            <span class="new-price">{{ format_money( $product->price )}}</span>
                                                            {{-- <span class="old-price">$30</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="bb-variations">
                                                        <div class="bb-colors">
                                                            <ul class="item-color">
                                                                <li class="bb-color active" style="background-color: #a98976;"></li>
                                                                <li class="bb-color" style="background-color: #292828;"></li>
                                                            </ul>
                                                        </div>
                                                        <div class="bb-sizes">
                                                            <ul class="item-size">
                                                                <li class="bb-size active">4 ft</li>
                                                                <li class="bb-size">3 ft</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Instagram -->
    <section class="section-instagram padding-tb-50">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bb-title">
                        <h3>#Insta</h3>
                    </div>
                    <div class="bb-instagram-slider owl-carousel">
                        <div class="bb-instagram-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                            <div class="instagram-img">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('website/assets/img/instagram/7.jpg')}}" alt="instagram-1">
                                </a>
                            </div>
                        </div>
                        <div class="bb-instagram-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                            <div class="instagram-img">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('website/assets/img/instagram/8.jpg')}}" alt="instagram-2">
                                </a>
                            </div>
                        </div>
                        <div class="bb-instagram-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                            <div class="instagram-img">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('website/assets/img/instagram/9.jpg')}}" alt="instagram-3">
                                </a>
                            </div>
                        </div>
                        <div class="bb-instagram-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500">
                            <div class="instagram-img">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('website/assets/img/instagram/10.jpg')}}" alt="instagram-4">
                                </a>
                            </div>
                        </div>
                        <div class="bb-instagram-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                            <div class="instagram-img">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('website/assets/img/instagram/11.jpg')}}" alt="instagram-5">
                                </a>
                            </div>
                        </div>
                        <div class="bb-instagram-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="700">
                            <div class="instagram-img">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('website/assets/img/instagram/12.jpg')}}" alt="instagram-6">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection