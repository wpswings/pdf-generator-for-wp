<?php
/**
 * This is template one for the pdf generation.
 *
 * @package pdf_generator_for_wp.
 *
 * @return void
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Return the html data for the second template.
 *
 * @param int    $order_id order id.
 * @param string $type packing slip or the invoice.
 * @param string $invoice_id current invoice ID.
 * @return string
 */
function return_ob_value( $order_id, $type, $invoice_id ) {

	$order_details         = do_shortcode( '[WPG_FETCH_ORDER order_id ="' . $order_id . '"]' );

	$order_details         = json_decode( $order_details, true );

	$shipping_details      = $order_details['shipping_details'];
	$billing_details       = $order_details['billing_details'];
	$order_product_details = $order_details['product_details'];
	$company_name          = get_option( 'wpg_company_name' );
	$company_address       = get_option( 'wpg_company_address' );
	$company_city          = get_option( 'wpg_company_city' );
	$company_state         = get_option( 'wpg_company_state' );
	$company_pin           = get_option( 'wpg_company_pin' );
	$company_phone         = get_option( 'wpg_company_phone' );
	$company_email         = get_option( 'wpg_company_email' );
	$digit                 = get_option( 'wpg_invoice_number_digit' );
	$prefix                = get_option( 'wpg_invoice_number_prefix' );
	$suffix                = get_option( 'wpg_invoice_number_suffix' );
	$date                  = get_option( 'wpg_invoice_renew_date' );
	$disclaimer            = get_option( 'wpg_invoice_disclaimer' );
	$color                 = get_option( 'wpg_invoice_color' );
	$is_add_logo           = get_option( 'wpg_is_add_logo_invoice' );
	$logo                  = get_option( 'sub_wpg_upload_invoice_company_logo' );
	$digit                 = ( $digit ) ? $digit : 3;
	$color                 = ( $color ) ? $color : '#000000';
	if ( $order_details ) {
		$html = '<!DOCTYPE html>
					<html>
					<head>
						<title> INVOICE SYSTEM FOR WOOCOMMERCE </title>
						<style>
							#wpg-pdf-header {
								margin-bottom: -80px;
							}
							#wpg-invoice-title-right {
								margin-top: -20px;
							}
					
							.wpg-invoice-inline {
								display: inline-block;
								width: 50%;
							}
							table#wpg-prod-listing-table{
								margin-top: 40px;
								width: 100%;
							}
							table#wpg-prod-listing-table tr th {
								text-align: center;
								padding: 5px;
							}
							table#wpg-prod-listing-table tr #wpg-table-items {
								text-align: left;
							}
					
							#wpg-pdf-prod-body tr td {
								text-align: center;
								padding: 15px 0;
							}
							#wpg-pdf-prod-body tr .wpg-product-name {
								text-align: left;
							}
							#wpg-prod-listing-table-title {
								background-color: ' . $color . ';
								color: white;
							}
							#wpg-prod-listing-table-bottom {
								border-bottom: 2px solid black;
								margin-top: 20px;
								position: relative;
							}
							
							#wpg-invoice-text{
								color: ' . $color . ';
								margin-bottom: 30px;
							}
					
							#wpg-prod-total-calc table {
								text-align: right;
								table-layout: fixed;
								width: 96%;
								margin-top: 30px;
							}
							.wpg-invoice-greetings {
								margin-top: 40px;
							}
							#wpg-invoice-title-right {
								margin-top: -20px;
								text-align: right;
							}
							
						</style>
					</head>
					<body>
						<div id="wpg-pdf">
							<h2 id="wpg-invoice-text">
							' . __( 'INVOICE', 'pdf-generator-for-wp' ) . '
							</h2>
							<div id="wpg-pdf-header">
								<div id="wpg-invoice-title-left" class="wpg-invoice-inline">
									<div>
										<b>' . __( 'Invoice Number', 'pdf-generator-for-wp' ) . '</b><br/>
										' . $invoice_id . '
									</div>
									<div>
										<b>' . __( 'Date', 'pdf-generator-for-wp' ) . '</b><br/>
										' . $billing_details['order_created_date'] . '
									</div>
								</div>
								<div id="wpg-invoice-title-right" class="wpg-invoice-inline">
									<div>';
									if ( 'yes' === $is_add_logo && '' !== $logo ) {
										$html .= '<img src="' . $logo . '" height="120" width="120"><br/>';
									}else{
										$html .= '<img src="' . $logo . '" height="120" width="120"><br/>';
									}
									if ( $company_name ) {
										$html .= '<b>' . ucfirst( $company_name ) . '</b><br/>';
									}else {
										$html .= '<b>Company Name</b><br/>';
									}
									if ( $company_address ) {
										$html .= ucfirst( $company_address ) . ' ,';
									}else {
										$html .= ucfirst('Company Address' ) . ' ,';
									}
									if ( $company_city ) {
										$html .= ucfirst( $company_city ) . '<br/>';
									}else {
										$html .= ucfirst( 'Company City' ) . '<br/>';
									}
									if ( $company_state ) {
										$html .= ucfirst( $company_state );
									}else {
										$html .= ucfirst( 'Company State' );
									}
									if ( $company_pin ) {
										$html .= '<br/> ' . $company_pin . '<br/>';
									}else{
										$html .= '<br/> Example pin 123456 <br/>';
									}
									if ( $company_phone ) {
										$html .= $company_phone . '<br/>';
									}else{
										$html .= 'Company Number' . '<br/>';
									}
									if ( $company_email ) {
										$html .= $company_email;
									}else{
										$html .= 'company@gmail.com';
									}
							$html .= '</div>
						</div>
					</div>';
		if ( 'invoice' === $type ) {
			$html .= '<div id="wpg-invoice-title-to" >
						<b>' . __( 'Invoice to', 'pdf-generator-for-wp' ) . '</b><br/>
						<div>
							' . ucfirst( $billing_details['billing_first_name'] ) . ' ' . ucfirst( $billing_details['billing_last_name'] ) . '<br/>';
			if ( $billing_details['billing_company'] ) {
				$html .= $billing_details['billing_company'] . '<br/>';
			}
			if ( $billing_details['billing_address_1'] ) {
				$html .= ucfirst( $billing_details['billing_address_1'] ) . ' ' . ucfirst( $billing_details['billing_address_2'] ) . '<br/>';
			}
			if ( $billing_details['billing_city'] ) {
				$html .= ucfirst( $billing_details['billing_city'] ) . '<br/>';
			}
			if ( $billing_details['billing_state'] ) {
				$html .= ucfirst( $billing_details['billing_state'] ) . '<br/>';
			}
			if ( $billing_details['billing_postcode'] ) {
				$html .= $billing_details['billing_postcode'] . '<br/>';
			}
			if ( $billing_details['billing_phone'] ) {
				$html .= $billing_details['billing_phone'] . '<br/>';
			}
			if ( $billing_details['billing_email'] ) {
				$html .= $billing_details['billing_email'] . '<br/>';
			}
			$html .= '</div>
			</div>';
		} else {
			$html .= '<div id="wpg-invoice-title-to" >
						<b>' . __( 'SHIP TO', 'pdf-generator-for-wp' ) . '</b><br/>
						<div>
							' . ucfirst( $shipping_details['shipping_first_name'] ) . ' ' . ucfirst( $shipping_details['shipping_last_name'] ) . '<br/>';
			if ( $shipping_details['shipping_company'] ) {
				$html .= $shipping_details['shipping_company'] . '<br/>';
			}
			if ( $shipping_details['shipping_address_1'] ) {
				$html .= ucfirst( $shipping_details['shipping_address_1'] ) . ' ' . ucfirst( $shipping_details['shipping_address_2'] ) . '<br/>';
			}
			if ( $shipping_details['shipping_city'] ) {
				$html .= ucfirst( $shipping_details['shipping_city'] ) . '<br/>';
			}
			if ( $shipping_details['shipping_state'] ) {
				$html .= ucfirst( $shipping_details['shipping_state'] ) . '<br/>';
			}
			if ( $shipping_details['shipping_postcode'] ) {
				$html .= $shipping_details['shipping_postcode'] . '<br/>';
			}
			if ( $billing_details['billing_phone'] ) {
				$html .= $billing_details['billing_phone'] . '<br/>';
			}
			if ( $billing_details['billing_email'] ) {
				$html .= $billing_details['billing_email'] . '<br/>';
			}
			$html .= '</div>
					</div>';
		}
		if ( 'invoice' === $type ) {
			$html .= '<div>
						<table border = "0" cellpadding = "0" cellspacing = "0" id="wpg-prod-listing-table">
							<thead>
								<tr id="wpg-prod-listing-table-title">
									<th id="wpg-table-items">' . __( 'Items', 'pdf-generator-for-wp' ) . '</th>
									<th>' . __( 'Quantity', 'pdf-generator-for-wp' ) . '</th>
									<th>' . __( 'Price', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . ')</th>
									<th>' . __( 'Tax', 'pdf-generator-for-wp' ) . ' (%)</th>
									<th>' . __( 'Amount', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . ')</th>
								</tr>
							</thead>
							<tbody id="wpg-pdf-prod-body">';
			$meta_data = '';
			foreach ( $order_product_details as $product ) {
				$item_data = ! empty( $product['item_meta'] ) ? $product['item_meta'] : array();
				if ( ! empty( $item_data ) && is_array( $item_data ) ) {
					foreach ( $item_data as $key => $item ) {

						if ( 'is_upsell_purchase' === $item['display_key'] ) {
							continue;
						}

						$meta_data .= '<br/>' . $item['display_key'] . ':' . $item['display_value'];
					}
				}
				$html .= '<tr>
						<td class="wpg-product-name">' . $product['product_name'] . $meta_data . '</td>
						<td>' . $product['product_quantity'] . '</td>
						<td>' . $product['product_price'] . '</td>
						<td>' . $product['tax_percent'] . '</td>
						<td>' . $product['product_total'] . '</td>
					</tr>';
			}
			$html               .= '</tbody>
										</table>
										<div id="wpg-prod-listing-table-bottom"></div>
										<div id="wpg-prod-total-calc">
											<table border = "0" cellpadding = "0" cellspacing = "0">
												<tr>
													<td>' . __( 'Payment via', 'pdf-generator-for-wp' ) . ' : ' . $billing_details['payment_method'] . '</td>
												</tr>
												<tr>
													<td>' . __( 'Subtotal', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $billing_details['order_subtotal'] . '</td>
												</tr>
												<tr>
													<td>' . __( 'Shipping', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $shipping_details['shipping_total'] . '</td>
												</tr>
												<tr>
													<td>' . __( 'Total tax', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $billing_details['tax_totals'] . '</td>
												</tr>';
			$pgfw_coupon_details = $billing_details['coupon_details'];
			foreach ( $pgfw_coupon_details as $key => $price ) {
				$html .= '<tr>
							<td>' . $key . '(' . $billing_details['order_currency'] . '): ' . $price . '</td>
						</tr>';
			}
			$html .= '<tr>
								<td>' . __( 'Total', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $billing_details['cart_total'] . '</td>
							</tr>
						</table>
					</div>
					<div class="wpg-invoice-greetings">
						<b>' . $disclaimer . '</b>
					</div>
					</div>';
		}
		$html .= '</div>
				</body>
			</html>';
		return $html;
	}
	return '<div>' . esc_html__( 'Looks like order is not found', 'pdf-generator-for-wp' ) . '</div>';
}
