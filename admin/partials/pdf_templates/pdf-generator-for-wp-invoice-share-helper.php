<?php
/**
 * Helper to render invoice share section.
 *
 * @package pdf_generator_for_wp
 */

if ( ! function_exists( 'wpg_invoice_share_meta' ) ) {
	/**
	 * Prepare share links and text.
	 *
	 * @param string $invoice_id  Invoice identifier.
	 * @param int    $order_id    Order ID.
	 * @param string $type        Invoice type.
	 * @param string $invoice_url Pre-generated invoice URL if available.
	 *
	 * @return array
	 */
	function wpg_invoice_share_meta( $invoice_id, $order_id, $type, $invoice_url = '' ) {
		$share_link = $invoice_url;
		if ( empty( $share_link ) ) {
			$upload_dir = wp_upload_dir();
			$share_link = trailingslashit( $upload_dir['baseurl'] ) . 'invoices/' . rawurlencode( $invoice_id ) . '.pdf';
		}

		$share_link = $share_link ? $share_link : home_url( '/' );
		$share_link = apply_filters( 'wpg_invoice_share_link', $share_link, $order_id, $invoice_id, $type );

		$whatsapp_message = sprintf(
			/* translators: 1: Invoice ID, 2: Invoice URL. */
			__( 'Invoice %1$s: %2$s', 'pdf-generator-for-wp' ),
			$invoice_id,
			$share_link
		);
		$email_subject = sprintf(
			/* translators: %s: Invoice ID. */
			__( 'Invoice %s', 'pdf-generator-for-wp' ),
			$invoice_id
		);
		$email_body = sprintf(
			/* translators: %s: Invoice URL. */
			__( 'Here is your invoice: %s', 'pdf-generator-for-wp' ),
			$share_link
		);

		return array(
			'share_link'     => $share_link,
			'whatsapp_share' => 'https://api.whatsapp.com/send?text=' . rawurlencode( $whatsapp_message ),
			'email_share'    => 'mailto:?subject=' . rawurlencode( $email_subject ) . '&body=' . rawurlencode( $email_body ),
		);
	}
}

if ( ! function_exists( 'wpg_invoice_share_section' ) ) {
	/**
	 * Render share section HTML.
	 *
	 * @param string $share_link     URL to share.
	 * @param string $color          Accent color.
	 * @param string $whatsapp_share Whatsapp share URL.
	 * @param string $email_share    Email share URL.
	 *
	 * @return string
	 */
	function wpg_invoice_share_section( $share_link, $color, $whatsapp_share, $email_share ) {
		$share_link            = esc_url( $share_link );
		$whatsapp_share        = esc_url( $whatsapp_share );
		$email_share           = esc_url( $email_share );
		$color_style           = $color ? $color : '#000000';
		$open_link_text        = esc_attr__( 'Open invoice link', 'pdf-generator-for-wp' );

		$html  = '<div style="text-align:center; padding:14px; border:1px solid #e6e6e6; border-radius:8px; background:#fafafa;">';
		$html .= '<div style="font-weight:700; letter-spacing:0.3px; margin-bottom:15px; color:' . esc_attr( $color_style ) . '; text-transform:uppercase; font-size:13px;">' . __( 'Share this invoice', 'pdf-generator-for-wp' ) . '</div>';
		$html .= '<div style="display:flex; gap:10px; align-items:center; justify-content:center; flex-wrap:wrap; margin-bottom:10px;">';
		$html .= '<a target="_blank" rel="noopener noreferrer" href="' . $whatsapp_share . '" style="min-width:96px; background:#25D366;color:#fff;padding:8px 12px;border-radius:18px;text-decoration:none;font-size:12px; font-weight:600; box-shadow:0 1px 2px rgba(0,0,0,0.08);margin-right:5px;">' . __( 'WhatsApp', 'pdf-generator-for-wp' ) . '</a>';
		$html .= '<a target="_blank" rel="noopener noreferrer" href="' . $email_share . '" style="min-width:96px; background:#4285f4;color:#fff;padding:8px 12px;border-radius:18px;text-decoration:none;font-size:12px; font-weight:600; box-shadow:0 1px 2px rgba(0,0,0,0.08);">' . __( 'Email', 'pdf-generator-for-wp' ) . '</a>';
		$html .= '</div>';
		$html .= '<a target="_blank" rel="noopener noreferrer" href="' . $share_link . '" aria-label="' . $open_link_text . '" title="' . $open_link_text . '" style="display:block; width:100%; max-width:520px; margin:15px auto 0; font-size:11px; color:#4a4a4a; text-decoration:none; word-break:break-all; padding:6px 10px; border:1px dashed #d0d0d0; border-radius:6px; background:#fff; line-height:1.45; font-family:inherit; text-align:left; cursor:pointer;">' . esc_html( $share_link ) . '</a>';
		$html .= '</div>';

		return $html;
	}
}
