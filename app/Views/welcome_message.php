<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Title -->
  <title>Mail Tracker Minerba 2023</title>

  <!-- Required Meta Tags Always Come First -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <!-- Favicon -->
  <link rel="shortcut icon" href="../../favicon.ico">
  <!-- Google Fonts -->
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C500%2C600%2C700%7CPlayfair+Display%7CRoboto%7CRaleway%7CSpectral%7CRubik">
  <!-- CSS Global Compulsory -->
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/icon-line-pro/style.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/animate.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/icon-awesome/css/font-awesome.min.css') ?>">

  <!-- CSS Unify -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/unify-core.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/unify-components.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/unify-globals.css') ?>">

  <!-- CSS Customization -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css') ?>">
</head>

<body>
  <main>
    <!-- Login -->
    <section class="g-min-height-100vh g-flex-centered g-bg-lightblue-radialgradient-circle">
      <div class="container g-py-100">
        <div class="row justify-content-center">
          <div class="col-sm-8 col-lg-5">
            <div class="u-shadow-v24 g-bg-white rounded g-py-40 g-px-30">
              <header class="text-center mb-4">
                <h2 class="h2 g-color-black g-font-weight-600">Mail Tracker Minerba</h2>
              </header>

              <!-- Form -->
              <form class="g-py-15">
                <div class="mb-4">
                  <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Email:</label>
                  <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="email" placeholder="johndoe@gmail.com">
                </div>

                <div class="g-mb-35">
                  <div class="row justify-content-between">
                    <div class="col align-self-center">
                      <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Password:</label>
                    </div>
                    <div class="col align-self-center text-right">
                      <a class="d-inline-block g-font-size-12 mb-2" href="#">Forgot password?</a>
                    </div>
                  </div>
                  <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15 mb-3" type="password" placeholder="Password">
                  <div class="row justify-content-between">
                    <div class="col-8 align-self-center">
                      <label class="form-check-inline u-check g-color-gray-dark-v5 g-font-size-12 g-pl-25 mb-0">
                        <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox">
                        <div class="u-check-icon-checkbox-v6 g-absolute-centered--y g-left-0">
                          <i class="fa" data-check-icon="&#xf00c"></i>
                        </div>
                        Keep signed in
                      </label>
                    </div>
                    <div class="col-4 align-self-center text-right">
                      <button class="btn btn-md u-btn-primary rounded g-py-13 g-px-25" type="button">Login</button>
                    </div>
                  </div>
                </div>
              </form>
              <!-- End Form -->

              <footer class="text-center">
                <p class="g-color-gray-dark-v5 g-font-size-13 mb-0">Don't have an account? <a class="g-font-weight-600" href="page-signup-6.html">signup</a>
                </p>
              </footer>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Login -->
  </main> 

  <div id="resolutionCaution" class="text-left g-max-width-600 g-bg-white g-pa-20" style="display: none;">
    <button type="button" class="close" onclick="Custombox.modal.close();">
      <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20">Screen resolution less than 1400px</h4>
  </div>

  <div id="copyModal" class="text-left modal-demo g-bg-white g-color-black g-pa-20" style="display: none;"></div>

  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/malihu-scrollbar/jquery.mCustomScrollbar.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/chosen/chosen.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/prism/themes/prism.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/custombox/custombox.min.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/style-switcher/vendor/spectrum/spectrum.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/style-switcher/vendor/spectrum/themes/sp-dark.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/style-switcher/style-switcher.css')?>">
  <!-- End CSS -->

  <!-- Scripts -->
  <script src="<?php echo base_url('assets/vendor/chosen/chosen.jquery.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/image-select/src/ImageSelect.jquery.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/malihu-scrollbar/jquery.mCustomScrollbar.concat.min.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/custombox/custombox.min.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/clipboard/dist/clipboard.min.js')?>"></script>

  <!-- Prism -->
  <script src="<?php echo base_url('assets/vendor/prism/prism.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/prism/components/prism-markup.min.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/prism/components/prism-css.min.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/prism/components/prism-clike.min.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/prism/components/prism-javascript.min.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/prism/plugins/toolbar/prism-toolbar.min.js')?>"></script>
  <script src="<?php echo base_url('assets/vendor/prism/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js')?>"></script>
  <!-- End Prism -->

  <script src="<?php echo base_url('assets/js/components/hs.scrollbar.js')?>"></script>
  <script src="<?php echo base_url('assets/js/components/hs.select.js')?>"></script>
  <script src="<?php echo base_url('assets/js/components/hs.modal-window.js')?>"></script>
  <script src="<?php echo base_url('assets/js/components/hs.markup-copy.js')?>"></script>

  <script src="<?php echo base_url('assets/style-switcher/vendor/cookiejs/jquery.cookie.js')?>"></script>
  <script src="<?php echo base_url('assets/style-switcher/vendor/spectrum/spectrum.js')?>"></script>
  <script src="<?php echo base_url('assets/style-switcher/style-switcher.js')?>"></script>
  <!-- End Scripts -->
  <!-- End Style Switcher -->

</body>

</html>
