<?php
@include 'config.php';
session_start();

// Initialize cart session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$cart_items = $_SESSION['cart'];
$message = array();

// Function to execute queries and handle errors
function executeQuery($conn, $query) {
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return $result;
}

// Handle Add to Wishlist
if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $user_id = $_SESSION['user_id'];

    $check_wishlist_numbers = executeQuery($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'");
    $check_cart_numbers = executeQuery($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");

    if (mysqli_num_rows($check_wishlist_numbers) > 0) {
        $message[] = 'Already added to wishlist';
    } elseif (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Already added to cart';
    } else {
        executeQuery($conn, "INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')");
        $message[] = 'Product added to wishlist';
    }
}

// Handle Add to Cart
// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = intval($_POST['product_quantity']); // Ensure it's an integer

    // Check if the item already exists in the cart
    $item_exists = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $_SESSION['cart'][$key]['product_quantity'] += $product_quantity;
            $item_exists = true;
            break;
        }
    }

    if (!$item_exists) {
        $_SESSION['cart'][] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'product_quantity' => $product_quantity
        );
    }

    $message[] = 'Product added to cart';
}

// Handle Comment Submission
if (isset($_POST['submit_comment'])) {
    if (isset($_SESSION['user_id'])) {
        $product_id = $_POST['product_id'];
        $comment = $_POST['comment'];
        $user_id = $_SESSION['user_id'];

        executeQuery($conn, "INSERT INTO `comments` (product_id, user_id, comment, created_at) VALUES ('$product_id', '$user_id', '$comment', NOW())");
        $message[] = 'Comment added successfully';
    } else {
        $message[] = 'You need to be logged in to add a comment';
    }
}

// Fetch comments for the product
if (isset($_GET['id'])) {
    $pid = $_GET['id'];
    $comments = mysqli_query($conn, "SELECT comments.*, users.email FROM comments JOIN users ON comments.user_id = users.user_id WHERE comments.product_id = '$pid' ORDER BY comments.created_at DESC");
}
@include 'header.php';
?>
<br>
<br>
<br>
<!-- Single Product Main Area Start -->
<div class="single-product-main-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-8">
                <?php  
                if (isset($_GET['id'])) {
                    $pid = $_GET['id'];
                    $select_products_query = "SELECT * FROM `products` WHERE id = '$pid'";
                    $select_products = mysqli_query($conn, $select_products_query);
            
                    if ($select_products->num_rows > 0) {
                        while ($fetch_products = $select_products->fetch_assoc()) { ?>
                       <form action="" method="POST">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($fetch_products['id']); ?>">
    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['name']); ?>">
    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_products['price']); ?>">
    <input type="hidden" name="product_image" value="uploaded_img/<?php echo htmlspecialchars($fetch_products['path']); ?>">
    <div class="product-details-img">
        
            <a class="w-70" href="uploaded_img/<?php echo htmlspecialchars($fetch_products['path']); ?>">
                <img class="w-70" src="uploaded_img/<?php echo htmlspecialchars($fetch_products['path']); ?>" alt="Product">
            </a>
            <!-- Additional slides if needed -->
        </div>
    </div>
    <div class="col-lg-7 mt-5">
        <div class="product-summery">
            <div class="product-head mb-3">
                <h2 class="product-title"><?php echo htmlspecialchars($fetch_products['name']); ?></h2>
            </div>
            <div class="price-box mb-2">
                <span class="regular-price"><?php echo htmlspecialchars($fetch_products['price']); ?></span>
                <!-- Add old price if needed -->
            </div>
            <div class="product-rating mb-3">
                <!-- Add rating functionality if needed -->
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
            </div>
            <div class="sku mb-3">
                <span>SKU: 12345</span>
            </div>
            <p class="desc-content mb-5"><?php echo htmlspecialchars($fetch_products['description']); ?></p>
            <div class="quantity-with_btn mb-5">
                <div class="quantity">
                    <div class="cart-plus-minus">
                        <input class="cart-plus-minus-box" name="product_quantity" value="1" type="number" min="1">
                        <div class="dec qtybutton">-</div>
                        <div class="inc qtybutton">+</div>
                    </div>
                </div>
                <div class="add-to_cart">
                    <button type="submit" name="add_to_cart" class="btn product-cart button-icon flosun-button dark-btn">Add to cart</button>
                    <button type="submit" name="add_to_wishlist" class="btn flosun-button secondary-btn secondary-border rounded-0">Add to wishlist</button>
                </div>
            </div>
        </div>
    </div>
</form>
                        <?php
                        }
                    } else {
                        echo '<p class="empty">No product details available!</p>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="row mt-no-text">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li>
                        <a style="background : #000000;color: #ffffff; border-radius: 0%; font-size: 12px; height: 40px; line-height: 40px; padding: 0 10px;" class="nav-link text-uppercase" id="profile-tab" data-bs-toggle="tab" href="#connect-2" role="tab" aria-selected="true">Reviews</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="connect-2" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="product_tab_content border p-3">
                            <div class="review_address_inner">
                                <?php
                                if (isset($comments) && $comments->num_rows > 0) {
                                    while ($fetch_comments = $comments->fetch_assoc()) { ?>
                                        <div class="pro_review mb-5">
                                            <div class="review_thumb">
                                                <img alt="review images" src="assets/images/review/1.jpg">
                                            </div>
                                            <div class="review_details">
                                                <div class="review_info mb-2">
                                                    <div class="product-rating mb-2">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <h5><?php echo htmlspecialchars($fetch_comments['email']); ?><span><?php echo htmlspecialchars($fetch_comments['created_at']); ?></span></h5>
                                                </div>
                                                <p><?php echo htmlspecialchars($fetch_comments['comment']); ?></p>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    echo '<p class="empty">No reviews yet!</p>';
                                }
                                ?>
                            </div>
                            <div class="rating_wrap">
                                <h5 class="rating-title-1 font-weight-bold mb-2">Add a review</h5>
                                <p class="mb-2">Your email address will not be published. Required fields are marked *</p>
                                <h6 class="rating-title-2 mb-2">Your Rating</h6>
                                <div class="rating_list mb-4">
                                    <div class="review_info">
                                        <div class="product-rating mb-3">
                                            <!-- Add rating functionality if needed -->
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="comments-area comments-reply-area">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="" method="POST" class="comment-form-area">
                                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($pid); ?>">
                                            <div class="row comment-input">
                                                <div class="col-md-12 comment-form-comment mb-3">
                                                    <label>Comment</label>
                                                    <textarea class="comment-notes" required="required" name="comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="comment-form-submit">
                                                <button type="submit" name="submit_comment" class="btn flosun-button secondary-btn rounded-0">Submit</button>
                                            </div>
                                        </form>
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
                            </div>
<a class="scroll-to-top" href="#">
    <i class="lnr lnr-arrow-up"></i>
</a>
<script>
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to handle quantity change
        function updateQuantity(button, increment) {
            const quantityInput = button.closest('.quantity').querySelector('.cart-plus-minus-box');
            let currentQuantity = parseInt(quantityInput.value, 10);
            if (increment) {
                quantityInput.value = currentQuantity + 1; // Increment by 1
            } else {
                if (currentQuantity > 1) { // Prevent going below 1
                    quantityInput.value = currentQuantity - 1;
                }
            }
        }

        // Event listeners for increment and decrement buttons
        document.querySelectorAll('.qtybutton').forEach(function(button) {
            button.addEventListener('click', function() {
                const increment = this.classList.contains('inc');
                updateQuantity(this, increment);
            });
        });
    });
</script>

</script>

<script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
<script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
<script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>
<script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
<script src="assets/js/plugins/swiper-bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
