(function ($) {
	/*document ready*/
	$(document).ready(function () {


		/* ------------ header -----------------*/

		$(window).on('scroll', function () {
			let st = $(this).scrollTop();
			if (st > 0) {
				$('.main__header').stop().addClass('sticky');
			}
			else {
				$('.main__header').stop().removeClass('sticky');
			}
		});

		$('.hamburger').on('click', function () {
			$(this).stop().toggleClass('show');

			$('.main__nav').stop().toggleClass('show');

			$('.main__header').stop().toggleClass('fixed');
			$('.main__header .main__menu li.menu-item-has-children').stop().removeClass('active');
			$('.main__header .main__menu li .sub-menu').stop().slideUp(400);
		});

		$('.main__header .main__menu > li > a').on('click', function () {
			$('.main__header').stop().removeClass('fixed');
			$('.hamburger').stop().removeClass('show');
			$('.main__nav').stop().removeClass('show');

		});

		$('.main__nav_overlay').on('click', function () {
			$('.hamburger').stop().removeClass('show');
			$('.main__nav').stop().removeClass('show');

			$('.main__header').stop().removeClass('fixed');
			$('.main__header .main__menu li.menu-item-has-children').stop().removeClass('active');
			$('.main__header .main__menu li .sub-menu').stop().slideUp(400);
		});



	});

})(jQuery);



