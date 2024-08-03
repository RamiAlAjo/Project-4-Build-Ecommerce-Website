<?php
include 'config.php'; // Include database configuration

// Fetch categories
$category_query = "SELECT * FROM categories ORDER BY name ASC";
$category_stmt = $conn->prepare($category_query);
$category_stmt->execute();
$category_result = $category_stmt->get_result();
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row;
}
$category_stmt->close();

// Initialize filter and search variables
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 100;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$stock_filter = isset($_GET['stock_filter']) ? $_GET['stock_filter'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Determine the sorting order
$sort_query = '';
switch ($sort) {
    case 'price_asc':
        $sort_query = 'ORDER BY price ASC';
        break;
    case 'price_desc':
        $sort_query = 'ORDER BY price DESC';
        break;
    default:
        $sort_query = 'ORDER BY name ASC'; // Default sorting
}

// Construct SQL query with filters
$sql = "SELECT p.*, c.name AS category_name FROM `products` p 
        JOIN `categories` c ON p.category_id = c.category_id 
        WHERE p.name LIKE ? AND p.price BETWEEN ? AND ?";

$params = [$search_param = "%$search%", $min_price, $max_price];
$types = 'sii'; // 'sii' means: string, integer, integer

if (!empty($category_filter)) {
    $sql .= " AND c.name = ?";
    $params[] = $category_filter;
    $types .= 's'; // Adding type for the category filter
}

if ($stock_filter === 'in_stock') {
    $sql .= " AND p.stock > 0";
} elseif ($stock_filter === 'out_of_stock') {
    $sql .= " AND p.stock = 0";
}

$sql .= " $sort_query";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }
  
  $cart_items = $_SESSION['cart'];
// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <?php include 'header.php'; ?>
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Add your CSS file -->
</head>
<body>
    <!-- Header Area -->
    <?php include 'header.php'; ?>

    <!-- Breadcrumb Area -->
    <div class="breadcrumbs-area position-relative">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="breadcrumb-content position-relative section-content">
                        <h3 class="title-3">Shop Sidebar</h3>
                        <ul>
                            <li><a href="index.html">Home</a></li>
                            <li>Shop</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Shop Main Area -->
    <div class="shop-main-area">
        <div class="container container-default custom-area">
            <div class="row flex-row-reverse">
                <!-- Main Content -->
                <div class="col-lg-9 col-12 col-custom widget-mt">
                    <!-- Shop Toolbar -->
                    <div class="shop_toolbar_wrapper mb-30">
                        <div class="shop_toolbar_btn">
                            <button data-role="grid_3" type="button" class="active btn-grid-3" title="Grid"><i class="fa fa-th"></i></button>
                            <button data-role="grid_list" type="button" class="btn-list" title="List"><i class="fa fa-th-list"></i></button>
                        </div>
                        <div class="shop-select">
                            <form class="d-flex flex-column w-100" method="GET" action="">
                                <div class="d-flex align-items-center mb-2">
                                    <label for="sort-by" class="mr-2">Sort By:</label>
                                    <select id="sort-by" name="sort" class="form-control">
                                        <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Default</option>
                                        <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                                        <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                                    </select>
                                </div>
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                                <input type="hidden" name="min_price" value="<?php echo $min_price; ?>">
                                <input type="hidden" name="max_price" value="<?php echo $max_price; ?>">
                                <input type="hidden" name="stock_filter" value="<?php echo htmlspecialchars($stock_filter); ?>">
                                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_filter); ?>">
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </form>
                        </div>
                    </div>
                    <!-- Shop Toolbar End -->

                    <!-- Shop Wrapper -->
                    <div class="row shop_wrapper grid_3">
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-6 col-sm-6 col-lg-4 col-custom product-area">
                                <div class="product-item">
                                    <div class="single-product position-relative mr-0 ml-0">
                                        <div class="product-image">
                                            <a class="d-block" href="product-details.php?id=<?php echo $product['id']; ?>">
                                                <img src="uploaded_img/<?php echo htmlspecialchars($product['path']); ?>" alt="" class="product-image-1 w-100">
<!--                                                 <img src="uploaded_img/aster.webp" alt="" class="product-image-2 position-absolute w-100">
 -->                                            </a>
                                             <?php if ($product['discount']> 0) {
                                                echo '<span class="onsale">'.$product['discount'].'%</span>';
                                                }?>
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
                                            <div class="price-box">
                                                <span class="regular-price"><?php echo htmlspecialchars($product['price']); ?></span>
                                                <?php if (!empty($product['old_price'])): ?>
                                                    <span class="old-price"><del><?php echo htmlspecialchars($product['old_price']); ?></del></span>
                                                <?php endif; ?>
                                            </div>
                                            <a href="cart.html" class="btn product-cart">Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Shop Wrapper End -->
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3 col-12 col-custom">
                    <aside class="sidebar_widget widget-mt">
                        <!-- Search Widget -->
                        <div class="widget-list widget-mb-1">
                            <h3 class="widget-title">Search</h3>
                            <div class="search-box">
                                <form method="GET" action="">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search Our Store" value="<?php echo htmlspecialchars($search); ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="widget-list widget-mb-1">
                            <form method="GET" action="" id="filter-form">
                                <!-- Category Filter -->
                                <div class="mb-3">
                                    <h6>Category</h6>
                                    <select class="form-control" name="category" id="category">
                                        <option value="">All Categories</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo htmlspecialchars($category['name']); ?>" <?php echo $category_filter == $category['name'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Price Filter Widget -->
                                <div class="widget-list widget-mb-1">
                                    <h3 class="widget-title">Price Filter</h3>
                                    <div id="slider-range"></div>
                                    <input type="text" id="amount" readonly class="form-control mt-2" />
                                    <input type="hidden" name="min_price" id="min_price" value="<?php echo $min_price; ?>">
                                    <input type="hidden" name="max_price" id="max_price" value="<?php echo $max_price; ?>">
                                </div>

                                <!-- Stock Availability Widget -->
                                <div class="widget-list widget-mb-1">
                                    <h3 class="widget-title">Stock Availability</h3>
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="stock-filter" class="mr-2">Stock Availability:</label>
                                        <select id="stock-filter" name="stock_filter" class="form-control">
                                            <option value="" <?php echo $stock_filter === '' ? 'selected' : ''; ?>>All</option>
                                            <option value="in_stock" <?php echo $stock_filter === 'in_stock' ? 'selected' : ''; ?>>In Stock</option>
                                            <option value="out_of_stock" <?php echo $stock_filter === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Hidden Inputs for Search and Filters -->
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">

                                <!-- Filter Buttons -->
                                <button type="submit" class="btn btn-primary mt-2">Apply</button>
                                <button type="button" class="btn btn-secondary mt-2" onclick="resetFilters()">Remove Filter</button>
                            </form>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Area -->
    <?php include 'footer.php'; ?>

    <!-- Scroll to Top -->
    <a class="scroll-to-top" href="#">
        <i class="lnr lnr-arrow-up"></i>
    </a>

    <!-- Include JavaScript files -->
    <?php include 'aside.php'; ?>
    <?php include 'script.php'; ?>
    <script src="assets/js/jquery-ui.js"></script>
    
<script>
    $(function() {
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 100,
            values: [<?php echo $min_price; ?>, <?php echo $max_price; ?>],
            slide: function(event, ui) {
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
    });

    function resetFilters() {
        document.getElementById('filter-form').reset();
        $("#slider-range").slider("values", [0, 100]);
        $("#amount").val("$0 - $100");
        document.getElementById('min_price').value = 0;
        document.getElementById('max_price').value = 100;

        // Submit the form to apply the reset filters
        document.getElementById('filter-form').submit();
    }
</script>

</body>
</html>