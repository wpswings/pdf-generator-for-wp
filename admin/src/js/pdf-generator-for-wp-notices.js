jQuery( document ).ready(
    function($){
        $( document ).on(
            'click',
            '#dismiss-banner',
            function(e){
               
                e.preventDefault();
                var data = {
                    action:'wps_pgfw_dismiss_notice_banner',
                    wps_nonce:wps_pgfw_notice.wps_pgfw_nonce
                };
                alert(data)
                $.ajax(
                    {
                        url: wps_pgfw_notice.ajaxurl,
                        type: "POST",
                        data: data,
                        success: function(response)
                        {
                            window.location.reload();
                        }
                    }
                );
            }
        );
    }
);