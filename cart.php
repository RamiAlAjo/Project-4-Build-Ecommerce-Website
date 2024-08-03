<?php
include 'config.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle delete request
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    if (isset($_SESSION['cart'][$delete_id])) {
        unset($_SESSION['cart'][$delete_id]);
    }
    header('Location: cart.php');
    exit();
}

// Handle delete all request
if (isset($_GET['delete_all'])) {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit();
}

// Handle update quantity
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = intval($_POST['cart_quantity']);
    if (isset($_SESSION['cart'][$cart_id])) {
        $_SESSION['cart'][$cart_id]['product_quantity'] = $quantity;
    }
}

// Handle coupon
$coupon_discount = 0;
if (isset($_POST['apply_coupon'])) {
    $coupon_code = $_POST['coupon_code'];
    if ($coupon_code == "DISCOUNT10") {
        $coupon_discount = 10; // 10% discount
    }
}

// Initialize cart items
$cart_items = $_SESSION['cart'];
$grand_total = 0;
foreach ($cart_items as $item) {
    $grand_total += $item['product_price'] * $item['product_quantity'];
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>FloSun - Flower Shop HTML5 Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
</head>
<body>
    <div class="breadcrumbs-area position-relative">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="breadcrumb-content position-relative section-content">
                        <h3 class="title-3">Shopping Cart</h3>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li>Shopping Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End Here -->
    <!-- cart main wrapper start -->
    <div class="cart-main-wrapper mt-no-text">
        <div class="container custom-area">
            <div class="row">
                <div class="col-lg-12 col-custom">
                    <!-- Cart Table Area -->
                    <div class="cart-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="pro-thumbnail">Image</th>
                                    <th class="pro-title">Product</th>
                                    <th class="pro-price">Price</th>
                                    <th class="pro-quantity">Quantity</th>
                                    <th class="pro-subtotal">Total</th>
                                    <th class="pro-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item_id => $item): ?>
                                <tr>
                                    <td class="pro-thumbnail">
                                        <a href="#"><img class="img-fluid" src="<?php echo htmlspecialchars($item['product_image']); ?>" alt="Product" /></a>
                                    </td>
                                    <td class="pro-title">
                                        <a href="#"><?php echo htmlspecialchars($item['product_name']); ?></a>
                                    </td>
                                    <td class="pro-price"><span>$<?php echo number_format($item['product_price'], 2); ?></span></td>
                                    <td class="pro-quantity">
                                        <form action="" method="post">
                                            <div class="quantity">
                                                <div class="cart-plus-minus">
                                                    <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($item_id); ?>">
                                                    <input class="cart-plus-minus-box" name="cart_quantity" value="<?php echo intval($item['product_quantity']); ?>" type="number" min="1">
                                                </div>
                                                <button type="submit" name="update_quantity" class="btn flosun-button primary-btn rounded-0 black-btn">Update</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="pro-subtotal"><span>$<?php echo number_format($item['product_price'] * $item['product_quantity'], 2); ?></span></td>
                                    <td class="pro-remove"><a href="cart.php?delete=<?php echo urlencode($item_id); ?>"><i class="lnr lnr-trash"></i></a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Cart Update Option -->
                    <div class="cart-update-option d-block d-md-flex justify-content-between">
                        <div class="apply-coupon-wrapper">
                            <form action="cart.php" method="post" class="d-block d-md-flex">
                                <input type="text" name="coupon_code" placeholder="Enter Your Coupon Code" required />
                                <button type="submit" name="apply_coupon" class="btn flosun-button primary-btn rounded-0 black-btn">Apply Coupon</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 ml-auto col-custom">
                    <!-- Cart Calculation Area -->
                    <div class="cart-calculator-wrapper">
                        <div class="cart-calculate-items">
                            <h3>Cart Totals</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>Sub Total</td>
                                        <td>$<?php echo number_format($grand_total, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td>$<?php echo number_format($grand_total * ($coupon_discount / 100), 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td class="total-amount">$<?php echo number_format($grand_total - ($grand_total * ($coupon_discount / 100)), 2); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <a href="checkout.php" class="btn flosun-button primary-btn rounded-0 black-btn w-100">Proceed To Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cart main wrapper end -->
    <!-- Footer Area Start -->
    <!-- Footer Area End -->

    <!-- JS -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <script src="assets/js/plugins/jquery.ajaxchimp.min.js"></script>
    <script src="assets/js/plugins/jquery-ui.min.js"></script>
    <script src="assets/js/plugins/jquery.countdown.min.js"></script>
    <script src="assets/js/plugins/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
