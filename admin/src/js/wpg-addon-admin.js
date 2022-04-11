jQuery(document).ready( function($) {

	const ajaxUrl  		 = localised.ajaxurl;
	const nonce    		 = localised.nonce;
	const action         = localised.callback;
	const pending_settings = localised.pending_settings;
	const hide_import     = localised.hide_import;


	
	const pending_settings_count  = 'undefined' != typeof pending_settings ? pending_settings.length : 0;

	/* Close Button Click */
	jQuery( document ).on( 'click','.treat-button',function(e){
		e.preventDefault();
		Swal.fire({
			icon: 'warning',
			title: 'We Have got '+ pending_settings_count +' ready to go!',
			text: 'Click to start import',
			footer: 'Please do not reload/close this page until prompted',
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: false,
			confirmButtonText:
			  '<i class="fa fa-thumbs-up"></i> Start',
			confirmButtonAriaLabel: 'Thumbs up',
			cancelButtonText:
			  '<i class="fa fa-thumbs-down"></i> Cancel',
			cancelButtonAriaLabel: 'Thumbs down'
		}).then((result) => {
			if (result.isConfirmed) {

				Swal.fire({
					title   : 'Settings are being imported!',
					html    : 'Do not reload/close this tab.',
					footer  : '<span class="order-progress-report">' + pending_settings_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
			
				pgfw_startOptionsImport();
			} else if (result.isDismissed) {
			  Swal.fire('Import Stopped', '', 'info');
			}
		})
	});

	const pgfw_startOptionsImport = () => {
		var event   = 'wpg_import_options_table';
		var request = { action, event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
		}).then(
		function() {
			// All options imported!
			Swal.fire({
				title   : 'Hold On Settings are been imported',
				html    : 'Do not reload/close this tab.',
				footer  : '<span class="order-progress-report">' + pending_settings_count + ' are left to import',
				didOpen: () => {
					Swal.showLoading()
				}
			});
			pgfw_startpdflogImport();
		}, function(error) {
			console.error(error);
		});
	}

	const pgfw_startpdflogImport = () => {
		var event   = 'wpg_import_pdflog';
		var request = { action, event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
		}).then(
		function() {
			// All options imported!
		//	window.location.reload();
		Swal.fire(' All of the Data are Migrated Successfully !', 'If You are using Premium Version of PDF Generator then please update Pro plugin from plugin page.', 'success').then(() => {
			window.location.reload();
		});
		}, function(error) {
			console.error(error);
		});
	}
	if ( hide_import == 'yes') {
		jQuery('#hide_button').hide();
		jQuery('#migration_completed').html('<b>DB keys migrated successfully!!</b>');
		
	}
	// End of scripts.
});
