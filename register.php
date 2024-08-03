<?php
include('include/clsValidator.php');
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['pass'];
  $conpassword = $_POST['cpass'];

  $validator = new Validator($username, $email, $password, $conpassword, $conn);


if ($validator->validate()) {
    
    if ($validator->checkUserExist()) {
        
        if ($validator->insertUserInDatabase()) {
            header('location: login.php');
        } else {
            $er = $validator->getErrors();
        }
    } else {
            $er = $validator->getErrors();
    }
} 
}

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

$cart_items = $_SESSION['cart'];

$conn->close();

include('header.php');
?>


    <!-- Header Area End Here -->
    <!-- Breadcrumb Area Start Here -->
    <div class="breadcrumbs-area position-relative">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <div class="breadcrumb-content position-relative section-content">
              <h3 class="title-3">Login-Register</h3>
              <ul>
                <li><a href="index.html">Home</a></li>
                <li>Login-Register</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Breadcrumb Area End Here -->
    <!-- Register Area Start Here -->
    <div class="login-register-area mt-no-text">
      <div class="container container-default-2 custom-area">
        <div class="row">
          <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-custom">
            <div class="login-register-wrapper">
              <div class="section-content text-center mb-5">
                <h2 class="title-4 mb-2">Create Account</h2>
                <p class="desc-content">
                  Please Register using account detail bellow.
                </p>
              </div>
              <form action="" method="post">
                <div class="single-input-item mb-3">
                  <input type="text" placeholder="Username" name="name" />
                  <div
                    id="user_error"
                    style="color: red; font-size: 12px"
                  ></div>
                </div>
                <div class="single-input-item mb-3">
                  <input type="email" placeholder="Email " name="email" />
                  <div
                    id="email_error"
                    style="color: red; font-size: 12px"
                  ></div>
                </div>
                <div class="single-input-item mb-3">
                  <input
                    type="password"
                    placeholder="Enter your Password"
                    name="pass"
                  />
                  <div
                    id="pass_error"
                    style="color: red; font-size: 12px"
                  ></div>
                </div>
                <div class="single-input-item mb-3">
                  <input
                    type="password"
                    placeholder="Confirm Password"
                    name="cpass"
                  />
                  <div
                    id="cpass_error"
                    style="color: red; font-size: 12px"
                  ></div>
                  
                  <?php 
                  if (isset($er['username_exist'])) {
                      echo '<div id="State" style="color: red; font-size: 12px">';
                      echo 'User already exists!';
                      echo '</div>';
                  }elseif (isset($er['email_exist'])) {
                      echo '<div id="State" style="color: red; font-size: 12px">';
                      echo 'Email already exists!';
                      echo '</div>';
                  }
                  ?>
                
                </div>
                <div class="single-input-item mb-3"></div>
                <div class="single-input-item mb-3">
                  <button
                    class="btn flosun-button secondary-btn theme-color rounded-0"
                    type="submit"
                  >
                    Register
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Register Area End Here -->
    <!--Footer Area Start-->
    <footer class="footer-area mt-no-text">
      <div class="footer-widget-area">
        <div class="container container-default custom-area">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-custom">
              <div class="single-footer-widget m-0">
                <div class="footer-logo">
                  <a href="index.html">
                    <img
                      src="assets/images/logo/logo-footer.png"
                      alt="Logo Image"
                    />
                  </a>
                </div>
                <p class="desc-content">
                  Lorem Khaled Ipsum is a major key to success. To be successful
                  you’ve got to work hard you’ve got to make it.
                </p>
                <div class="social-links">
                  <ul class="d-flex">
                    <li>
                      <a class="rounded-circle" href="#" title="Facebook">
                        <i class="fa fa-facebook-f"></i>
                      </a>
                    </li>
                    <li>
                      <a class="rounded-circle" href="#" title="Twitter">
                        <i class="fa fa-twitter"></i>
                      </a>
                    </li>
                    <li>
                      <a class="rounded-circle" href="#" title="Linkedin">
                        <i class="fa fa-linkedin"></i>
                      </a>
                    </li>
                    <li>
                      <a class="rounded-circle" href="#" title="Youtube">
                        <i class="fa fa-youtube"></i>
                      </a>
                    </li>
                    <li>
                      <a class="rounded-circle" href="#" title="Vimeo">
                        <i class="fa fa-vimeo"></i>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-custom">
              <div class="single-footer-widget">
                <h2 class="widget-title">Information</h2>
                <ul class="widget-list">
                  <li><a href="about-us.html">Our Company</a></li>
                  <li><a href="contact-us.html">Contact Us</a></li>
                  <li><a href="about-us.html">Our Services</a></li>
                  <li><a href="about-us.html">Why We?</a></li>
                  <li><a href="about-us.html">Careers</a></li>
                </ul>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-custom">
              <div class="single-footer-widget">
                <h2 class="widget-title">Quicklink</h2>
                <ul class="widget-list">
                  <li><a href="about-us.html">About</a></li>
                  <li><a href="blog.html">Blog</a></li>
                  <li><a href="shop.html">Shop</a></li>
                  <li><a href="cart.html">Cart</a></li>
                  <li><a href="contact-us.html">Contact</a></li>
                </ul>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-custom">
              <div class="single-footer-widget">
                <h2 class="widget-title">Support</h2>
                <ul class="widget-list">
                  <li><a href="contact-us.html">Online Support</a></li>
                  <li><a href="contact-us.html">Shipping Policy</a></li>
                  <li><a href="contact-us.html">Return Policy</a></li>
                  <li><a href="contact-us.html">Privacy Policy</a></li>
                  <li><a href="contact-us.html">Terms of Service</a></li>
                </ul>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-custom">
              <div class="single-footer-widget">
                <h2 class="widget-title">See Information</h2>
                <div class="widget-body">
                  <address>
                    123, ABC, Road ##, Main City, Your address goes here.<br />Phone:
                    01234 567 890<br />Email: https://example.com
                  </address>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-copyright-area">
        <div class="container custom-area">
          <div class="row">
            <div class="col-12 text-center col-custom">
              <div class="copyright-content">
                <p>
                  Copyright © 2021
                  <a
                    href="https://hasthemes.com/"
                    title="https://hasthemes.com/"
                    >HasThemes</a
                  >
                  | Built with&nbsp;<strong>FloSun</strong>&nbsp;by
                  <a
                    href="https://hasthemes.com/"
                    title="https://hasthemes.com/"
                    >HasThemes</a
                  >.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!--Footer Area End-->

    <!-- JS
============================================ -->

    <!-- jQuery JS -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- jQuery Migrate JS -->
    <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <!-- Modernizer JS -->
    <script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>

    <!-- Swiper Slider JS -->
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <!-- nice select JS -->
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <!-- Ajaxchimpt js -->
    <script src="assets/js/plugins/jquery.ajaxchimp.min.js"></script>
    <!-- Jquery Ui js -->
    <script src="assets/js/plugins/jquery-ui.min.js"></script>
    <!-- Jquery Countdown js -->
    <script src="assets/js/plugins/jquery.countdown.min.js"></script>
    <!-- jquery magnific popup js -->
    <script src="assets/js/plugins/jquery.magnific-popup.min.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <!-- Validation JS -->
    <script src="assets/js/register.js"></script>
  </body>

  <!-- Mirrored from htmldemo.net/flosun/flosun/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 04 Dec 2022 05:03:27 GMT -->
</html>
