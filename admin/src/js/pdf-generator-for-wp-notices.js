jQuery( document ).ready(
    function($){
        $(document).on(
            'click',
            '#dismiss-banner',
            function (e) {
                if ( wps_pgfw_notice.check_pro_activate ) {

                	jQuery(document).find('.wps-offer-notice').hide();
                } else {
                e.preventDefault();
                var data = {
                    action: 'wps_pgfw_dismiss_notice_banner',
                    wps_nonce: wps_pgfw_notice.wps_pgfw_nonce
                };
               
                $.ajax(
                    {
                        url: wps_pgfw_notice.ajaxurl,
                        type: "POST",
                        data: data,
                        success: function (response) {
                            window.location.reload();
                        }
                    }
                );
            }
            }
        );
    }
);