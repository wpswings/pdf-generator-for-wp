(function( $ ) {
	'use strict';

	/**
	 * All of the code for your common JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 jQuery(document).ready(function($){
		// add product ajax.
		$('#pgfw-bulk-product-add').click(function(e){
			e.preventDefault();
			var self = this;
			var cur_html = $(self).html();
			$(self).html('<img src="' + pgfw_common_param.loader + '" style="width:20px;height:20px;display:inline;">');
			var product_id = $(self).data('product-id');
			$.ajax({
				url    : pgfw_common_param.ajaxurl,
				method : 'post',
				data   : {
					action     : 'pgfw_bulk_add_products_ajax',
					product_id : product_id,
					nonce : pgfw_common_param.nonce,
				},
				success: function( msg ) {
					$(self).html(cur_html);
					if ( msg == 1 ) {
						location.reload();
					}
					$('.mwb_pgfw-display-value').text(msg);
				},
				error: function() {
					$(self).html(cur_html);
					alert('no');
				}
			});
		});
		// bulk display of products chart ajax.
		$('.mwb_pgw-button').click(function(e){
			e.preventDefault();
			$.ajax({
				url    : pgfw_common_param.ajaxurl,
				method : 'post',
				data   : {
					action     : 'pgfw_build_html_from_session',
					nonce: pgfw_common_param.nonce,
				},
				success: function( msg ) {
					$('.mwb_pgw-button_content').html(msg);
				},
				error: function() {
					alert('no');
				}
			});
		});
		// delete products from the bulk list ajax.
		$(document).on('click', '.pgfw-delete-this-products-bulk', function(e) {
			e.preventDefault();
			var self = this;
			var cur_html = $(self).html();
			$(self).html('<img src="' + pgfw_common_param.loader + '" style="width:20px;height:20px;display:inline;">');
			var product_id = $(this).data('product-id');
			$.ajax({
				url    : pgfw_common_param.ajaxurl,
				method : 'post',
				data   : {
					action     : 'pgfw_delete_product_from_session',
					product_id : product_id,
					nonce: pgfw_common_param.nonce,
				},
				success: function( msg ) {
					$(self).html(cur_html);
					$('.mwb_pgw-button_content').html(msg);
					var cur_count = $('.mwb_pgfw-display-value').text();
					cur_count = parseInt( cur_count ) -1;
					$('.mwb_pgfw-display-value').text( cur_count );
					if ( cur_count <=0 ) {
						$('.mwb_pgw-button_wrapper').hide();
					}
				},
				error: function() {
					$(self).html(cur_html);
					alert('no');
				}
			});
		});
		// create zip of bulk products.
		$(document).on("click", "#pgfw-create-zip-bulk", function(){
			var self = this;
			var cur_html = $(self).html();
			$(self).html('<img src="' + pgfw_common_param.loader + '" style="width:20px;height:20px;display:inline;">');
			pgfw_ajax_for_zip_or_pdf( 'pdf_zip' );
			$(self).html(cur_html);
		});
		// create pdf in continuation of bulk.
		$(document).on('click', '#pgfw-create-pdf-bulk', function() {
			var self = this;
			var cur_html = $(self).html();
			$(self).html('<img src="' + pgfw_common_param.loader + '" style="width:20px;height:20px;display:inline;">');
			pgfw_ajax_for_zip_or_pdf( 'pdf_bulk' );
			$(self).html(cur_html);
		});
		// ajax to create pdf for bulk and download it.
		function pgfw_ajax_for_zip_or_pdf( name ) {
			$.ajax({
				url    : pgfw_common_param.ajaxurl,
				method : 'post',
				data   : {
					action : 'mwb_pgfw_ajax_for_zip_or_pdf',
					nonce  : pgfw_common_param.nonce,
					name   : name
				},
				success: function( msg ) {
					$('#pgfw-download-zip-parent').html('<a href="' + msg + '" download id="pgfw-download-zip"></a>');
					$('#pgfw-download-zip')[0].click();
					$('#pgfw-download-zip-parent').html('');
				},
				error: function() {
					alert('error');
				} 
			});
		}
	});
})( jQuery );
