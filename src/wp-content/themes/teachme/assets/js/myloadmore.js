jQuery(function ($) {
    $('.news_loadmore').click(function () {

        var button = $(this),
            data = {
                'action': 'loadmore',
                'page': news_loadmore_params.current_page
            };

        $.ajax({
            url: news_loadmore_params.ajaxurl,
            data: data,
            type: 'POST',
            success: function (data) {
                if (data) {
                    button.text('Load More...').prev().after(data);
                    news_loadmore_params.current_page++;
                    if (news_loadmore_params.current_page == news_loadmore_params.max_page)

                        button.remove();
                } else {
                    button.remove();
                }
            }
        });
    });
});