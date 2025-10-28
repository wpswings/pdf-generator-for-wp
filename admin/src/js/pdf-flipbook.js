/* globals pdfjsLib, wpsGfwPdf */

// Set PDF.js worker.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';

jQuery( document ).ready( function( $ ) {
	/**
	 * Convert PDF to images.
	 */
	async function convertPdfToImages( arrayBuffer ) {
		$( '#fb_pdf_spinner' ).show();
		const uint8Array = new Uint8Array( arrayBuffer );
		const pdf = await pdfjsLib.getDocument( { data: uint8Array } ).promise;
		let htmlOutput = '';
		for ( let pageNum = 1; pageNum <= pdf.numPages; pageNum++ ) {
			const page = await pdf.getPage( pageNum );
			const viewport = page.getViewport( { scale: 1.5 } );
			const canvas = document.createElement( 'canvas' );
			const ctx = canvas.getContext( '2d' );
			canvas.height = viewport.height;
			canvas.width = viewport.width;
			await page.render( { canvasContext: ctx, viewport: viewport } ).promise;
			const imgData = canvas.toDataURL( 'image/png' );
			htmlOutput += `<div class="pdf-page"><div class="page-header">Page ${ pageNum }</div><div class="page-body"><img src="${ imgData }" style="max-width:100%; height:auto;"></div></div>\n`;
		}
		$( '#fb_pdf_html' ).val( htmlOutput );
		$( '#fb_pdf_spinner' ).hide();
	}

	/**
	 * Handle PDF URL fetch.
	 */
	$( '#fb_pdf_url' ).on( 'change', async function() {
		const url = $( this ).val().trim();
		if ( url ) {
			try {
				const response = await fetch( wpsGfwPdf.fbAjaxUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: 'action=fb_fetch_pdf&url=' + encodeURIComponent( url ) + '&nonce=' + encodeURIComponent( wpsGfwPdf.fbFetchNonce )
				} );
				if ( ! response.ok ) {
					const text = await response.text();
					throw new Error( text || ( 'HTTP ' + response.status ) );
				}
				const ct = response.headers.get( 'content-type' ) || '';
				if ( ! ct.includes( 'application/pdf' ) ) {
					const text = await response.text();
					throw new Error( text || 'Non-PDF response' );
				}
				const buffer = await response.arrayBuffer();
				convertPdfToImages( buffer );
			} catch ( err ) {
				alert( 'Failed to load PDF: ' + err.message );
			}
		}
	} );

	/**
	 * Handle tab navigation.
	 */
	$( '.fb-tab-nav a' ).on( 'click', function( e ) {
		e.preventDefault();
		const target = $( this ).attr( 'href' );
		$( '.fb-tab-nav a' ).removeClass( 'active' );
		$( this ).addClass( 'active' );
		$( '.fb-tab-content' ).removeClass( 'active' );
		$( target ).addClass( 'active' );
	} );

	/**
	 * Handle flipping time input validation.
	 */
	const flippingTimeInput = document.getElementById( 'fb_flippingTime' );
	if ( flippingTimeInput ) {
		flippingTimeInput.addEventListener( 'input', function() {
			if ( this.value < 0 ) {
				this.value = 0;
			}
		} );
	}
} );