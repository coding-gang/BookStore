<!DOCTYPE html>
<html class="no-js" lang="en-US">

@include('app.layouts.head')

<body>
<!-- app -->
<div id="app">
@include('app.layouts.header')
@yield('slider')
<!-- Banner-Layer -->
@yield('banner')
<!-- Banner-Layer /- -->
    <!-- Page-Wrapper -->
@yield('pageWrapper')
<!-- Page-Wrapper /- -->
<section class="section-maker">
     @yield('content')
</section>
@include('app.layouts.footer')
    <!-- Dummy Selectbox -->
    <div class="select-dummy-wrapper">
        <select id="compute-select">
            <option id="compute-option">All</option>
        </select>
    </div>
    <!-- Dummy Selectbox /- -->
    <!-- Responsive-Search -->
    <div class="responsive-search-wrapper">
        <button type="button" class="button ion ion-md-close" id="responsive-search-close-button"></button>
        <div class="responsive-search-container">
            <div class="container">
                <p>Start typing and press Enter to search</p>
                <form class="responsive-search-form">
                    <label class="sr-only" for="search-text">Search</label>
                    <input id="search-text" type="text" class="responsive-search-field" placeholder="PLEASE SEARCH">
                    <i class="fas fa-search"></i>
                </form>
            </div>
        </div>
    </div>
    <!-- Responsive-Search /- -->
    <!-- Newsletter-Modal -->
    <div id="newsletter-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="button dismiss-button ion ion-ios-close" data-dismiss="modal"></button>
                <div class="modal-body u-s-p-x-0">
                    <div class="row align-items-center u-s-m-x-0">
                        <div class="col-lg-6 col-md-6 col-sm-12 u-s-p-x-0">
                            <div class="newsletter-image">
                                <a href="shop-v1-root-category.html" class="banner-hover effect-dark-opacity">
                                    <img class="img-fluid" src="{{asset('assets/webApp/images/banners/photo-1603656951468-c1b89670e4c3.jpg')}}" alt="Newsletter Image">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="newsletter-wrapper">
                                <h1>New to
                                    <span>Groover</span> ?
                                    <br>Subscribe Newsletter</h1>
                                <h5>Get latest product update...</h5>
                                <form>
                                    <div class="u-s-m-b-35">
                                        <input type="text" class="newsletter-textfield" placeholder="Enter Your Email">
                                    </div>
                                    <div class="u-s-m-b-35">
                                        <button class="button button-primary d-block w-100">Subscribe</button>
                                    </div>
                                </form>
                                <h6>Be the first for getting special deals and offers, Send directly to your inbox.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter-Modal /- -->
    <!-- Quick-view-Modal -->
    <div id="quick-view" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="button dismiss-button ion ion-ios-close" data-dismiss="modal"></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Product-zoom-area -->
                            <div class="zoom-area zoom-area-modal">

                            </div>
                            <!-- Product-zoom-area /- -->
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Product-details -->
                            <div class="all-information-wrapper">
                                <div class="section-1-title-breadcrumb-rating">
                                    <div class="product-title product-title-modal">

                                    </div>
                                    <ul class="bread-crumb bread-crumb-modal ">

                                    </ul>
                                    <div class="product-rating">
                                        <div class='star' title="4.5 out of 5 - based on 23 Reviews">
                                            <span style='width:67px'></span>
                                        </div>
                                        <span>(23)</span>
                                    </div>
                                </div>
                                <div class="section-2-short-description u-s-p-y-14">
                                    <h6 class="information-heading u-s-m-b-8">Mô tả:</h6>
                                    <p class="description-book description-book-modal ">
                                    </p>
                                </div>
                                <div class="section-3-price-original-discount u-s-p-y-14">
                                    <div class="price price-modal">
                                        <h4></h4>
                                    </div>
                                    <div class="original-price original-price-modal">
                                        <span>Giá gốc:</span>
                                        <span></span>
                                    </div>
                                    <div class="discount-price discount-price-modal">
                                        <span>Giảm giá:</span>
                                        <span></span>
                                    </div>
                                </div>
                                <div class="section-4-sku-information u-s-p-y-14">
                                    <h6 class="information-heading u-s-m-b-8">Thông tin:</h6>
                                    <div class="availability">
                                        <span>Tình trạng:</span>
                                        <span>Còn hàng</span>
                                    </div>
                                </div>
                                <div class="section-5-product-variants u-s-p-y-14">
                                    <h6 class="information-heading u-s-m-b-8">Sản phẩm:</h6>
                                    <div class="color u-s-m-b-11">
                                        <span>Hình thức:</span>
                                        <span class="formality formality-modal"></span>
                                        </div>
                                    <div class="color u-s-m-b-11">
                                        <span>Tác giả:</span>
                                        <span class="book-author book-author-modal"></span>
                                    </div>
                                    <div class="color u-s-m-b-11">
                                        <span>Nhà xuất bản:</span>
                                        <span class="book-publisher book-publisher-modal"></span>
                                    </div>
                                    </div>

                                </div>
                                <div class="section-6-social-media-quantity-actions u-s-p-y-14">
                                    <form action="#" class="post-form">

                                        <div class="quantity-wrapper u-s-m-b-22">
                                            <span>Quantity:</span>
                                            <div class="quantity">
                                                <input type="text" class="quantity-text-field quantity-text-field-modal" value="1" onkeypress="javascript:return isNumber(event)">
                                                <a class="plus-a" data-max="1000">&#43;</a>
                                                <a class="minus-a" data-min="1">&#45;</a>
                                            </div>
                                        </div>
                                        <div>
                                            <button data-value="" onclick="addToCart(this)" class="button btn-addToCart-Modal button-outline-secondary" type="button">Add to cart</button>
                                            <button class="button button-outline-secondary far fa-heart u-s-m-l-6"></button>
                                            <button class="button button-outline-secondary far fa-envelope u-s-m-l-6"></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Product-details /- -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick-view-Modal /- -->


<!-- Site-Priorities -->
<section class="app-priority">
    <div class="container">
        <div class="priority-wrapper u-s-p-b-80">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="single-item-wrapper">
                        <div class="single-item-icon">
                            <i class="ion ion-md-star"></i>
                        </div>
                        <h2>
                            Great Value
                        </h2>
                        <p>We offer competitive prices on our 100 million plus product range</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="single-item-wrapper">
                        <div class="single-item-icon">
                            <i class="ion ion-md-cash"></i>
                        </div>
                        <h2>
                            Shop with Confidence
                        </h2>
                        <p>Our Protection covers your purchase from click to delivery</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="single-item-wrapper">
                        <div class="single-item-icon">
                            <i class="ion ion-ios-card"></i>
                        </div>
                        <h2>
                            Safe Payment
                        </h2>
                        <p>Pay with the world’s most popular and secure payment methods</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="single-item-wrapper">
                        <div class="single-item-icon">
                            <i class="ion ion-md-contacts"></i>
                        </div>
                        <h2>
                            24/7 Help Center
                        </h2>
                        <p>Round-the-clock assistance for a smooth shopping experience</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>

@include('app.layouts.script')
@yield('script')
</body>
</html>
