<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>FloSun - Flower Shop HTML5 Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font.awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/linearicons.min.css">
    <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/plugins/animate.min.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/plugins/nice-select.min.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<body>

    <!-- Header Area Start Here -->
    <header class="main-header-area">
        <div class="main-header header-transparent header-sticky">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-2 col-xl-2 col-md-6 col-6 col-custom">
                        <div class="header-logo d-flex align-items-center">
                            <a href="index.php">
                                <img class="img-full" src="assets/images/logo/logo.png" alt="Header Logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8 d-none d-lg-flex justify-content-center col-custom">
                        <nav class="main-nav d-none d-lg-flex">
                            <ul class="nav">
                                <li><a class="active" href="index.php">Home</a></li>
                                <li><a href="shop.PHP">Shop</a></li>
                                <li><a href="login.php">Login/Signup</a></li>
                                <li><a href="about-us.html">About Us</a></li>
                                <li><a href="contact-us.html">Contact Us</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-2 col-md-6 col-6 col-custom">
                        <div class="header-right-area main-nav">
                            <ul class="nav">
                                <li class="minicart-wrap">
                                    <a href="#" class="minicart-btn toolbar-btn">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span class="cart-item_count"><?php echo count($cart_items); ?></span>
                                    </a>
                                   <!-- Cart Item Wrapper -->
                                   <div class="cart-item-wrapper dropdown-sidemenu dropdown-hover-2">
    <!-- This wrapper will be scrollable if items exceed the height -->
    <?php if (!empty($cart_items)): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="single-cart-item">
                <div class="cart-img">
                    <a href="cart.php"><img src="<?php echo htmlspecialchars($item['product_image']); ?>" alt=""></a>
                </div>
                <div class="cart-text">
                    <h5 class="title"><a href="cart.php"><?php echo htmlspecialchars($item['product_name']); ?></a></h5>
                    <div class="cart-text-btn">
                        <div class="cart-qty">
                            <span><?php echo htmlspecialchars($item['product_quantity']); ?>×</span>
                            <span class="cart-price">$<?php echo htmlspecialchars($item['product_price']); ?></span>
                        </div>
                        <form method="post" action="">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['product_id']); ?>">
                            <button type="submit" name="remove_item" class="remove-icon"><i class="ion-trash-b"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="cart-price-total d-flex justify-content-between">
            <h5>Total :</h5>
            <h5>$<?php echo array_sum(array_map(function($item) { return $item['product_price'] * $item['product_quantity']; }, $cart_items)); ?></h5>
        </div>
        <div class="cart-links d-flex justify-content-between">
            <a class="btn product-cart button-icon flosun-button dark-btn" href="cart.php">View cart</a>
            <a class="btn flosun-button secondary-btn rounded-0" href="checkout.php">Checkout</a>
        </div>
    <?php else: ?>
        <p>No items in cart</p>
    <?php endif; ?>
</div>


                                </li>
                                <li class="sidemenu-wrap">
                                    <a href="#"><i class="fa fa-search"></i></a>
                                    <ul class="dropdown-sidemenu dropdown-hover-2 dropdown-search">
                                        <li>
                                            <form action="#">
                                                <input name="search" id="search" placeholder="Search" type="text">
                                                <button type="submit"><i class="fa fa-search"></i></button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                <li class="account-menu-wrap d-none d-lg-flex">
                                    <a href="#" class="off-canvas-menu-btn">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                </li>
                                <li class="mobile-menu-btn d-lg-none">
                                    <a class="off-canvas-btn" href="#">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header Area End Here -->

    <!-- Additional content can be included here -->


