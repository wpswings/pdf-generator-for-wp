jQuery(document).ready(function($){

    // Cover uploader
    $(document).on('click', '.upload-cover-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        var $input = $row.find('input[name="fb_cover_image"]');
        var $preview = $row.find('.cover-preview');

        var frame = wp.media({
            title: 'Select Cover Image',
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $input.val(attachment.url);
            $preview.html('<img src="'+attachment.url+'" style="max-width:250px; height:auto; border:1px solid #ccc; display:block; margin-bottom:5px;">');
            $row.find('.upload-cover-btn').text('Change Cover Image');
            if ($row.find('.remove-cover-btn').length === 0) {
                $row.find('.upload-cover-btn').after(' <button type="button" class="button remove-cover-btn">Remove</button>');
            }
        });

        frame.open();
    });

    $(document).on('click', '.remove-cover-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        $row.find('input[name="fb_cover_image"]').val('');
        $row.find('.cover-preview').empty();
        $(this).remove();
        $row.find('.upload-cover-btn').text('Select Cover Image');
    });

    // Back uploader
    $(document).on('click', '.upload-back-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        var $input = $row.find('input[name="fb_back_image"]');
        var $preview = $row.find('.back-preview'); // ✅ now targets correct div

        var frame = wp.media({
            title: 'Select Back Cover Image',
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $input.val(attachment.url);
            $preview.html('<img src="'+attachment.url+'" style="max-width:250px; height:auto; border:1px solid #ccc; display:block; margin-bottom:5px;">');
            $row.find('.upload-back-btn').text('Change Back Image');
            if ($row.find('.remove-back-btn').length === 0) {
                $row.find('.upload-back-btn').after(' <button type="button" class="button remove-back-btn">Remove</button>');
            }
        });

        frame.open();
    });

    $(document).on('click', '.remove-back-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        $row.find('input[name="fb_back_image"]').val('');
        $row.find('.back-preview').empty(); // ✅ clears correct preview
        $(this).remove();
        $row.find('.upload-back-btn').text('Select Back Image');
    });

    // Audio uploader
    $(document).on('click', '.upload-audio-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        var $input = $row.find('input[name="fb_flip_sound_url"]');
        var $preview = $row.find('.audio-preview');

        var frame = wp.media({
            title: 'Select Flip Sound',
            multiple: false,
            library: { type: 'audio' }
        });

        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $input.val(attachment.url);
            $preview.html('<audio controls src="'+attachment.url+'" style="width:100%;"></audio>');
            $row.find('.upload-audio-btn').text('Change Audio');
            if ($row.find('.remove-audio-btn').length === 0) {
                $row.find('.upload-audio-btn').after(' <button type="button" class="button remove-audio-btn">Remove</button>');
            }
        });

        frame.open();
    });

    $(document).on('click', '.remove-audio-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        $row.find('input[name="fb_flip_sound_url"]').val('');
        $row.find('.audio-preview').empty();
        $(this).remove();
        $row.find('.upload-audio-btn').text('Upload/Select Audio');
    });

    // PDF uploader via media library
    $(document).on('click', '.upload-pdf-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        var $input = $row.find('input[name="fb_pdf_url"]');
        var $preview = $row.find('.pdf-preview');

        var frame = wp.media({
            title: 'Select PDF',
            multiple: false,
            library: { type: 'application/pdf' }
        });

        frame.on('select', async function(){
            var attachment = frame.state().get('selection').first().toJSON();
            var url = attachment.url;
            $input.val(url).trigger('change');
            $preview.html('<a href="'+url+'" target="_blank" rel="noopener">Current PDF</a>');
            $row.find('.upload-pdf-btn').text('Change PDF');
            if ($row.find('.remove-pdf-btn').length === 0) {
                $row.find('.upload-pdf-btn').after(' <button type="button" class="button remove-pdf-btn">Remove</button>');
            }
        });

        frame.open();
    });

    $(document).on('click', '.remove-pdf-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        $row.find('input[name="fb_pdf_url"]').val('');
        $row.find('.pdf-preview').empty();
        $(this).remove();
        $row.find('.upload-pdf-btn').text('Upload/Select PDF');
    });

    // Images multi-select
    $(document).on('click', '.upload-images-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        var $input = $row.find('#fb_image_urls');
        var $preview = $row.find('.images-preview');
        var frame = wp.media({
            title: 'Select Images',
            multiple: true,
            library: { type: 'image' }
        });
        frame.on('select', function(){
            var selection = frame.state().get('selection');
            var urls = [];
            $preview.empty();
            selection.each(function(att){
                var a = att.toJSON();
                if (a && a.url) {
                    urls.push(a.url);
                    $preview.append('\
                        <div class="fb-img-chip" data-url="'+a.url+'" style="position:relative;width:60px;height:60px;">\
                            <img src="'+a.url+'" style="width:60px;height:60px;object-fit:cover;border:1px solid #ddd;border-radius:4px;display:block;" />\
                            <button type="button" class="button-link-delete fb-img-remove" title="Remove" style="position:absolute;top:-8px;right:-6px;background:#d63638;color:#fff;border:none;border-radius:999px;width: 20px;height: 20px;line-height: 1;text-align:center;cursor:pointer;display: inline-flex;align-items: center;justify-content: center;font-size: 14px;">&times;</button>\
                        </div>');
                }
            });
            $input.val(JSON.stringify(urls));
            $row.find('.clear-images-btn').show();
        });
        frame.open();
    });

    $(document).on('click', '.clear-images-btn', function(e){
        e.preventDefault();
        var $row = $(this).closest('td');
        $row.find('#fb_image_urls').val('');
        $row.find('.images-preview').empty();
        $(this).hide();
    });

    // Remove individual image from selection
    $(document).on('click', '.fb-img-remove', function(e){
        e.preventDefault();
        var $chip = $(this).closest('.fb-img-chip');
        var $row = $(this).closest('td');
        var $input = $row.find('#fb_image_urls');
        var urls = [];
        try { urls = JSON.parse($input.val() || '[]'); } catch(_) { urls = []; }
        var url = $chip.attr('data-url');
        urls = urls.filter(function(u){ return u !== url; });
        $input.val(JSON.stringify(urls));
        $chip.remove();
        if (urls.length === 0) {
            $row.find('.clear-images-btn').hide();
        }
    });

    // Admin notice from validation transient
    (function(){
        var params = new URLSearchParams(window.location.search);
        if (params.get('post') && params.get('action') === 'edit') {
            // Attempt to fetch transient via inline markup (added server-side below)
            var notice = document.getElementById('ifb-validation-notice');
            if (notice) {
                // Auto-hide after few seconds
                setTimeout(function(){ notice.style.display = 'none'; }, 6000);
            }
        }
    })();

});

jQuery(document).ready(function($){
    function toggleCoverSettings() {
        if ($('#fb_show_cover').is(':checked')) {
            $('.cover-settings-row').show();
        } else {
            $('.cover-settings-row').hide();
        }
    }

    // Init on page load
    toggleCoverSettings();

    // Toggle on checkbox change
    $(document).on('change', '#fb_show_cover', toggleCoverSettings);
    $(document).on('click', '#pgfw_general_settings_save', function () {
        const isChecked = $('#pgfw_flipbook_enable').is(':checked');
        location.reload();
        setTimeout(() => location.reload(), 500);
    });


});
