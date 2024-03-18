<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Hrm_Templates' ) ) {
	class Hrm_Templates {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			//
		}

		public static function mobile_menu_offcanvas_template() { ?>
            <!-- Offcanvas Area Start -->
            <div class="fix-area">
                <div class="offcanvas__info">
                    <div class="offcanvas__wrapper">
                        <div class="offcanvas__content">
                            <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                                <div class="offcanvas__logo">
                                    <a href="<?php echo esc_url(home_url('/')); ?>">
                                        <img src="<?php echo get_template_directory_uri() . '/assets/img/logo/logo.png' ?>" alt="logo-img">
                                    </a>
                                </div>
                                <div class="offcanvas__close">
                                    <button>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mobile-menu fix mb-5"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas__overlay"></div>
		<?php }

	} // class end

	Hrm_Templates::instance()->initialize();
}