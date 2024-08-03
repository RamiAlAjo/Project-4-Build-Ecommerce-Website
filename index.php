<?php
// Start the session
session_start();

// Include the configuration file for database connection
include 'config.php';

// Initialize cart session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Initialize message array
$messages = array();

// Handle adding to wishlist
if (isset($_POST['add_to_wishlist'])) {
    // Fetch the product details from POST request
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the wishlist or cart
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE name = ? AND user_id = ?");
    $stmt->bind_param("si", $product_name, $user_id);
    $stmt->execute();
    $check_wishlist_numbers = $stmt->get_result();

    $stmt = $conn->prepare("SELECT * FROM cart WHERE name = ? AND user_id = ?");
    $stmt->bind_param("si", $product_name, $user_id);
    $stmt->execute();
    $check_cart_numbers = $stmt->get_result();

    if ($check_wishlist_numbers->num_rows > 0) {
        $messages[] = 'Already added to wishlist';
    } elseif ($check_cart_numbers->num_rows > 0) {
        $messages[] = 'Already added to cart';
    } else {
        // Add the product to the wishlist
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $product_id, $product_name, $product_price, $product_image);
        if ($stmt->execute()) {
            $messages[] = 'Product added to wishlist';
        } else {
            $messages[] = 'Failed to add product to wishlist';
        }
    }
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    // Fetch the product details from POST request
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;

    // Check if the product is already in the cart
    $item_exists = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            // Update quantity if product already exists in the cart
            $_SESSION['cart'][$key]['product_quantity'] += $product_quantity;
            $item_exists = true;
            break;
        }
    }

    // Add new item if it does not exist in the cart
    if (!$item_exists) {
        $_SESSION['cart'][] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'product_quantity' => $product_quantity
        );
    }

    $messages[] = 'Product added to cart';
}

// Fetch cart items to display in the header
$cart_items = $_SESSION['cart'];

// Include the header
include 'header.php';
?>


<!-- Slider/Intro Section Start -->
<!-- Slider/Intro Section Start -->
<div class="intro11-slider-wrap section">
    <div class="intro11-slider swiper-container">
        <div class="swiper-wrapper">
            <div class="intro11-section swiper-slide slide-1 slide-bg-1 bg-position">
                <!-- Intro Content Start -->
                <div class="intro11-content text-left">
                    <h3 class="title-slider text-uppercase">Top Trend</h3>
                    <h2 class="title">2022 Flower Trend</h2>
                    <p class="desc-content">Lorem ipsum dolor sit amet, pri autem nemore bonorum te. Autem fierent ullamcorper ius no, nec ea quodsi invenire.</p>
                    <a href="product-details.php" class="btn flosun-button secondary-btn theme-color rounded-0">Shop Now</a>
                </div>
                <!-- Intro Content End -->
            </div>
            <div class="intro11-section swiper-slide slide-2 slide-bg-1 bg-position">
                <!-- Intro Content Start -->
                <div class="intro11-content text-left">
                    <h3 class="title-slider black-slider-title text-uppercase">Collection</h3>
                    <h2 class="title">Flowers and Candle <br> Birthday Gift</h2>
                    <p class="desc-content">Lorem ipsum dolor sit amet, pri autem nemore bonorum te. Autem fierent ullamcorper ius no, nec ea quodsi invenire.</p>
                    <a href="product-details.php" class="btn flosun-button secondary-btn rounded-0">Shop Now</a>
                </div>
                <!-- Intro Content End -->
            </div>
        </div>
        <!-- Slider Navigation -->
        <div class="home1-slider-prev swiper-button-prev main-slider-nav"><i class="lnr lnr-arrow-left"></i></div>
        <div class="home1-slider-next swiper-button-next main-slider-nav"><i class="lnr lnr-arrow-right"></i></div>
        <!-- Slider pagination -->
        <div class="swiper-pagination"></div>
    </div>
</div>
<!-- Slider/Intro Section End -->

<!-- Categories Area Start -->


<!-- Product Area Start -->
<!-- Example: Product Area Start -->
<div class="product-area mt-text-2">
    <div class="container custom-area-2 overflow-hidden">
        <div class="row">
            <div class="col-12 col-custom">
                <div class="section-title text-center mb-30">
                    <span class="section-title-1">Wonderful gift</span>
                    <h3 class="section-title-3">Featured Products</h3>
                </div>
            </div>
        </div>
        <div class="row product-row">
            <div class="col-12 col-custom">
                <div class="product-slider swiper-container anime-element-multi">
                    <div class="swiper-wrapper">
                        <?php
                        // Fetch only 12 products
                        $query = "SELECT * FROM products LIMIT 12";
                        $result = mysqli_query($conn, $query);
                        while ($product = mysqli_fetch_assoc($result)): ?>
                        <!-- Single Product Start -->
                        <div class="single-item swiper-slide">
                            <div class="single-product position-relative mb-30">
                                <div class="product-image">
                                    <a class="d-block" href="product-details.php?id=<?php echo $product['id']; ?>">
                                        <img src="uploaded_img/<?php echo $product['path']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image-1 w-100">
                                        <img src="uploaded_img/<?php echo $product['path']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image-2 position-absolute w-100">
                                    </a>
                                    <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                                        <span class="onsale">Sale!</span>
                                    <?php endif; ?>
                                    <div class="add-action d-flex flex-column position-absolute">
                                        <a href="compare.html" title="Compare">
                                            <i class="lnr lnr-sync" data-toggle="tooltip" data-placement="left" title="Compare"></i>
                                        </a>
                                        <a href="wishlist.html" title="Add To Wishlist">
                                            <i class="lnr lnr-heart" data-toggle="tooltip" data-placement="left" title="Wishlist"></i>
                                        </a>
                                        <a href="#exampleModalCenter" title="Quick View" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                            <i class="lnr lnr-eye" data-toggle="tooltip" data-placement="left" title="Quick View"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <div class="product-title">
                                        <h4 class="title-2"><a href="product-details.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h4>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </div>
                                    <div class="price-box">
                                        <?php
                                        $discounted_price = $product['price'] - ($product['price'] * $product['discount'] / 100);
                                        ?>
                                        <span class="regular-price">$<?php echo number_format($discounted_price, 2); ?></span>
                                        <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                                            <span class="old-price"><del>$<?php echo number_format($product['price'], 2); ?></del></span>
                                        <?php endif; ?>
                                    </div>
                                    <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $discounted_price; ?>">
                                        <input type="hidden" name="product_image" value="uploaded_img/<?php echo $product['path']; ?>">
                                        <button type="submit" name="add_to_cart" class="btn product-cart">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Single Product End -->
                        <?php endwhile; ?>
                    </div>
                    <!-- Slider pagination -->
                    <div class="home1-slider-prev swiper-button-prev main-slider-nav"><i class="lnr lnr-arrow-left"></i></div>
                    <div class="home1-slider-next swiper-button-next main-slider-nav"><i class="lnr lnr-arrow-right"></i></div>
                    <div class="swiper-pagination default-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product Area End -->


</div>
<!-- Product Area End -->

<!-- Product Area End -->

<!-- Product Countdown Area Start Here -->
<div class="product-countdown-area mt-text-3">
    <div class="container custom-area">
        <div class="row">
            <!-- Section Title Start -->
            <div class="col-12 col-custom">
                <div class="section-title text-center mb-30">
                    <h3 class="section-title-3">Deal of The WEEEK</h3>
                </div>
            </div>
            <!-- Section Title End -->
        </div>
        <div class="row">
            <!-- Countdown Start -->
            <div class="col-12 col-custom">
                <div class="countdown-area">
                    <div class="countdown-wrapper d-flex justify-content-center" id="countdown"></div>
                </div>
            </div>
            <!-- Countdown End -->
        </div>
        <div class="row product-row">
    <div class="col-12 col-custom">
        <div class="item-carousel-2 swiper-container anime-element-multi product-area">
            <div class="swiper-wrapper">
                <?php
                // Fetch only discounted products
                $query = "SELECT * FROM products WHERE discount > 0 LIMIT 8";
                $result = mysqli_query($conn, $query);
                while ($product = mysqli_fetch_assoc($result)): ?>
                <!-- Single Product Start -->
                <div class="single-item swiper-slide">
                    <div class="single-product position-relative mb-30">
                        <div class="product-image">
                            <a class="d-block" href="product-details.php?id=<?php echo $product['id']; ?>">
                                <img src="uploaded_img/<?php echo $product['path']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image-1 w-100">
                                <img src="uploaded_img/<?php echo $product['path']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image-2 position-absolute w-100">
                            </a>
                            <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                                <span class="onsale">Sale!</span>
                            <?php endif; ?>
                            <div class="add-action d-flex flex-column position-absolute">
                                <a href="compare.html" title="Compare">
                                    <i class="lnr lnr-sync" data-toggle="tooltip" data-placement="left" title="Compare"></i>
                                </a>
                                <a href="wishlist.html" title="Add To Wishlist">
                                    <i class="lnr lnr-heart" data-toggle="tooltip" data-placement="left" title="Wishlist"></i>
                                </a>
                                <a href="#exampleModalCenter" title="Quick View" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                    <i class="lnr lnr-eye" data-toggle="tooltip" data-placement="left" title="Quick View"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-title">
                                <h4 class="title-2"><a href="product-details.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h4>
                            </div>
                            <div class="product-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <div class="price-box">
                                <?php
                                $discounted_price = $product['price'] - ($product['price'] * $product['discount'] / 100);
                                ?>
                                <span class="regular-price">$<?php echo number_format($discounted_price, 2); ?></span>
                                <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                                    <span class="old-price"><del>$<?php echo number_format($product['price'], 2); ?></del></span>
                                <?php endif; ?>
                            </div>
                            <!-- Add to Cart Form -->
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo number_format($discounted_price, 2); ?>">
                                <input type="hidden" name="product_image" value="uploaded_img/<?php echo htmlspecialchars($product['path']); ?>">
                                <button type="submit" name="add_to_cart" class="btn product-cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Single Product End -->
                <?php endwhile; ?>
            </div>
            <!-- Slider pagination -->
            <div class="home1-slider-prev swiper-button-prev main-slider-nav"><i class="lnr lnr-arrow-left"></i></div>
                    <div class="home1-slider-next swiper-button-next main-slider-nav"><i class="lnr lnr-arrow-right"></i></div>
                    <div class="swiper-pagination default-pagination"></div>
        </div>
    </div>
</div>

    </div>
</div>
<!-- Product Countdown Area End Here -->

<!-- Include Countdown Library -->
<script src="https://cdn.jsdelivr.net/npm/countdown@2.6.0/countdown.min.js"></script>

<!-- Countdown JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var endDate = new Date('2024/08/10'); // Set the target date here
    var countdownElement = document.getElementById('countdown');

    countdown(endDate, function(ts) {
        countdownElement.innerHTML = `
            <div class="countdown-item">
                <span class="countdown-number">${ts.days}</span>
                <span class="countdown-label">Days</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number">${ts.hours}</span>
                <span class="countdown-label">Hours</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number">${ts.minutes}</span>
                <span class="countdown-label">Minutes</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-number">${ts.seconds}</span>
                <span class="countdown-label">Seconds</span>
            </div>
        `;
    });
});

</script>
<!-- Product Countdown Area End Here -->

<?php
include 'footer.php';
?>

<!-- Scroll to Top Start -->
<a class="scroll-to-top" href="#">
    <i class="lnr lnr-arrow-up"></i>
</a>
<!-- Scroll to Top End -->
<?php
include 'aside.php';
?>
<?php
include 'script.php';
?>

</body>
</html>