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
 * Function to return the html for the first template.
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
	$disclaimer            = get_option( 'wpg_invoice_disclaimer' );
	$color                 = get_option( 'wpg_invoice_color' );
	$is_add_logo           = get_option( 'wpg_is_add_logo_invoice' );
	$logo                  = get_option( 'sub_wpg_upload_invoice_company_logo' );
	$color                 = ( $color ) ? $color : '#000000';
	if ( $order_details ) {
		$html = '<!DOCTYPE html>
					<html lang="en">
					<head>
						<style>
							.wpg-invoice-background-color{
								background-color: #f5f5f5;
							}
							.wpg-invoice-color{
								color: ' . $color . ';
							}
							
						</style>
					</head>
					<body>
						<div id="mwb-pdf-form">
							<form action="" method="post">';
		if ( 'yes' === $is_add_logo && '' !== $logo ) {
			$html .= '<div style="text-align:center;margin-bottom: 30px;"><img src="' . $logo . '" height="120" width="120"></div>';
		}
		$html .= '<table border = "0" cellpadding = "0" cellspacing = "0" style="width: 100%; vertical-align: top; margin-bottom: 30px;">
					<tbody>
						<tr>
							<td valign="top">
								<table border = "0" cellpadding = "0" cellspacing = "0" style="width: 100%;"> 
									<tbody>
										<tr>
											<td class="wpg-invoice-background-color" style="padding: 10px;">
												<h3 class="wpg-invoice-color" style="margin: 0;font-size: 24px;">' . $company_name . '</h3>
											</td>
										</tr>';
		if ( $company_address ) {
			$html .= '<tr>
				<td style="padding: 5px 10px;">' . ucfirst( $company_address ) . '</td>
			</tr>';
		}
		$html .= '<tr>
					<td style="padding: 5px 10px;">';
		if ( $company_city ) {
			$html .= ucfirst( $company_city );
		}
		if ( $company_state ) {
			$html .= '<br/> ' . ucfirst( $company_state );
		}
		if ( $company_pin ) {
			$html .= '<br/> ' . $company_pin;
		}
		$html .= '</td>
				</tr>';
		if ( $company_phone ) {
			$html .= '<tr>
						<td style="padding: 5px 10px;">Phone : ' . $company_phone . '</td>
					</tr>';
		}
		if ( $company_email ) {
			$html .= '<tr>
						<td style="padding: 5px 10px;">Email : ' . $company_email . '</td>
					</tr>';
		}
		$html .= '</tbody>
				</table>
			</td>
			<td valign="top">
				<table border = "0" cellpadding = "0" cellspacing = "0" class="" style="width: 100%;table-layout: auto;">
					<thead>
						<tr>
							<th colspan="2" class="wpg-invoice-background-color" style="padding: 10px;">
								<h3 class="wpg-invoice-color" style="margin: 0;text-align:right;font-size:24px;">
									' . __( 'Invoice', 'pdf-generator-for-wp' ) . '
								</h3>
							</th>
						</tr>
						<tr>
							<th style="width: 70%;text-align: right;padding: 10px;">
								' . __( 'Invoice', 'pdf-generator-for-wp' ) . '
							</th>
							<th style="width: 30%;text-align: right;padding: 10px;">
								' . __( 'Date', 'pdf-generator-for-wp' ) . '
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="width: 30%;text-align: right;padding: 0 10px;">' . $invoice_id . '</td>
							<td style="width: 30%;text-align: right;padding: 0 10px;">' . $billing_details['order_created_date'] . '</td>
						</tr>
					</tbody>
				</table>
				<table border = "0" class="" style="width: 100%;table-layout: auto;">
					<thead>
						<tr>
							<th style="width: 70%;text-align: right;padding: 10px;">
								' . __( 'Customer ID', 'pdf-generator-for-wp' ) . '
							</th>
							<th style="width: 30%;text-align: right;padding: 10px;">
								' . __( 'Status', 'pdf-generator-for-wp' ) . '
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="" style="width: 70%;text-align: right;padding: 0 10px;">
								' . $billing_details['customer_id'] . '
							</td>
							<td class="" style="width: 30%;text-align: right;padding: 0 10px;">
								' . $shipping_details['order_status'] . '
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>';
		if ( 'invoice' === $type ) {
			$html .= '<tr>
					<td colspan="2">
						<table border = "0" cellpadding="0" cellpadding="0" style="width: 100%;margin-top: 20px;">
							<thead>
								<tr>
									<th class="wpg-invoice-background-color wpg-invoice-color" style="text-align:left;padding:10px;font-size: 20px;">
										' . __( 'BILL TO', 'pdf-generator-for-wp' ) . '
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="padding: 2px 10px;font-weight: bold;font-size: 18px;">' . ucfirst( $billing_details['billing_first_name'] ) . ' ' . ucfirst( $billing_details['billing_last_name'] ) . '</td>
								</tr>';
			if ( $billing_details['billing_company'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . $billing_details['billing_company'] . '</td>
						</tr>';
			}
			if ( $billing_details['billing_address_1'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . ucfirst( $billing_details['billing_address_1'] ) . ' ' . ucfirst( $billing_details['billing_address_2'] ) . '</td>
						</tr>';
			}
			if ( $billing_details['billing_city'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . ucfirst( $billing_details['billing_city'] ) . ', ' . ucfirst( $billing_details['billing_state'] ) . ', ' . $billing_details['billing_postcode'] . '</td>
						</tr>';
			}
			if ( $billing_details['billing_phone'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . $billing_details['billing_phone'] . '</td>
						</tr>';
			}
			if ( $billing_details['billing_email'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . $billing_details['billing_email'] . '</td>
						</tr>';
			}
			$html .= '</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>';
		} else {
			$html .= '<tr>
						<td colspan="2">
							<table border = "0" cellpadding="0" cellpadding="0" style="width: 100%;margin-top: 20px;">
								<thead>
									<tr>
										<th class="wpg-invoice-background-color wpg-invoice-color" style="text-align:left;padding:10px;font-size:20px;">
											' . __( 'SHIP TO', 'pdf-generator-for-wp' ) . '
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="padding: 2px 10px;font-weight: bold;font-size: 18px;">' . ucfirst( $shipping_details['shipping_first_name'] ) . ' ' . ucfirst( $shipping_details['shipping_last_name'] ) . '</td>
									</tr>';
			if ( $shipping_details['shipping_company'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . $shipping_details['shipping_company'] . '</td>
						</tr>';
			}
			if ( $shipping_details['shipping_address_1'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . ucfirst( $shipping_details['shipping_address_1'] ) . ' ' . ucfirst( $shipping_details['shipping_address_2'] ) . '</td>
						</tr>';
			}
			if ( $shipping_details['shipping_city'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . ucfirst( $shipping_details['shipping_city'] ) . ', ' . ucfirst( $shipping_details['shipping_state'] ) . ', ' . $shipping_details['shipping_postcode'] . '</td>
						</tr>';
			}
			if ( $billing_details['billing_phone'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . $billing_details['billing_phone'] . '</td>
						</tr>';
			}
			if ( $billing_details['billing_email'] ) {
				$html .= '<tr>
							<td style="padding: 2px 10px;font-size: 16px;">' . $billing_details['billing_email'] . '</td>
						</tr>';
			}
			$html .= '</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>';
		}
		if ( 'invoice' === $type ) {
			$html .= '<table border = "0" cellpadding = "0" cellspacing = "0" style="width: 100%; vertical-align: top;text-align: left;" id="my-table-mwb-prod-listing">
					<thead class="background-pdf-color-template">
						<tr class="wpg-invoice-background-color">
							<th style="text-align: left;padding: 10px;" class="wpg-invoice-color">
								' . __( 'Name', 'pdf-generator-for-wp' ) . '
							</th>
							<th style="text-align: left;padding: 10px;" class="wpg-invoice-color">
								' . __( 'Qty', 'pdf-generator-for-wp' ) . '
							</th>
							<th style="text-align: left;padding: 10px;" class="wpg-invoice-color">
								' . __( 'Price', 'pdf-generator-for-wp' ) . ' ( ' . $billing_details['order_currency'] . ' )
							</th>
							<th style="text-align: left;padding: 10px;" class="wpg-invoice-color">
								' . __( 'Tax', 'pdf-generator-for-wp' ) . '( % )
							</th>
							<th style="text-align: left;padding: 10px;" class="wpg-invoice-color">
								' . __( 'Total', 'pdf-generator-for-wp' ) . ' ( ' . $billing_details['order_currency'] . ' )
							</th>
						</tr>
					</thead>
					<tbody>';
			$meta_data = '';
			foreach ( $order_product_details as $key => $product ) {
				$item_data = ! empty( $product['item_meta'] ) ? $product['item_meta'] : array();
				if ( ! empty( $item_data ) && is_array( $item_data ) ) {
					foreach ( $item_data as $key => $item ) {

						if ( 'is_upsell_purchase' === $item['display_key'] ) {
							continue;
						}

						$meta_data .= '<br>' . $item['display_key'] . ':' . $item['display_value'];
					}
				}
				$html .= '<tr>
								<td style="text-align: left;padding: 10px;">' . $product['product_name'] . $meta_data . '</td>
								<td style="text-align: left;padding: 10px;">' . $product['product_quantity'] . '</td>
								<td style="text-align: left;padding: 10px;">' . $product['product_price'] . '</td>
								<td style="text-align: left;padding: 10px;">' . $product['tax_percent'] . '</td>
								<td style="text-align: left;padding: 10px;">' . $product['product_total'] . '</td>
							</tr>';
			}
			$html               .= '<tr>
										<td colspan="3" style="padding: 2px 10px;font-weight: bold;">
										</td>
										<td style="padding: 2px 10px;font-weight: bold;">
											' . __( 'Payment via', 'pdf-generator-for-wp' ) . '
										</td>
										<td style="padding: 2px 10px;font-weight: bold;">
											' . $billing_details['payment_method'] . '
										</td>
								</tr>
								<tr>
									<td colspan="3" style="padding: 2px 10px;font-weight: bold;">
									</td>
									<td style="padding: 2px 10px;font-weight: bold;">
									' . __( 'Subtotal', 'pdf-generator-for-wp' ) . '</td>
									<td style="padding: 2px 10px;font-weight: bold;">
										' . $billing_details['order_subtotal'] . '
									</td>
								</tr>
								<tr>
									<td colspan="3" style="padding: 2px 10px;font-weight: bold;" class="no-border">

									</td>
									<td style="padding: 2px 10px;font-weight: bold;">
										' . __( 'Shipping', 'pdf-generator-for-wp' ) . '
									</td>
									<td style="padding: 2px 10px;font-weight: bold;">
										' . $shipping_details['shipping_total'] . '
									</td>
								</tr>
								<tr>
									<td colspan="3" style="padding: 2px 10px;font-weight: bold;" class="no-border">

									</td>
									<td style="padding: 2px 10px;font-weight: bold;">
										' . __( 'Total Tax', 'pdf-generator-for-wp' ) . '
									</td>
									<td style="padding: 2px 10px;font-weight: bold;">
										' . $billing_details['tax_totals'] . '
									</td>
								</tr>';
			$pgfw_coupon_details = $billing_details['coupon_details'];
			foreach ( $pgfw_coupon_details as $key => $price ) {
				$html .= '<tr>
					<td colspan="3" style="padding: 2px 10px;font-weight: bold;" class="no-border">

					</td>
					<td style="padding: 2px 10px;font-weight: bold;">
						' . $key . '
					</td>
					<td style="padding: 2px 10px;font-weight: bold;">
						' . $price . '
					</td>
				</tr>';
			}
			$order = wc_get_order($order_id);
			// Get applied coupons.
			$coupons = $order->get_coupon_codes();

			// Check if any coupons are applied.
			if (!empty($coupons)) {
			$html .= '
			<tr>
						<td colspan="3" style="padding: 2px 10px;font-weight: bold;" class="no-border">

						</td>
						<td style="padding: 2px 10px;font-weight: bold;">
							' . __( 'Discount', 'pdf-generator-for-wp' ) . ' ( ' . $billing_details['order_currency'] . ' ) 
						</td>
						<td style="padding: 2px 10px;font-weight: bold;">
							' . $order->get_discount_total() . '
						</td>
					</tr>';
			}

			$html .= '
			<tr>
						<td colspan="3" style="padding: 2px 10px;font-weight: bold;" class="no-border">

						</td>
						<td style="padding: 2px 10px;font-weight: bold;">
							' . __( 'Total', 'pdf-generator-for-wp' ) . ' ( ' . $billing_details['order_currency'] . ' ) 
						</td>
						<td style="padding: 2px 10px;font-weight: bold;">
							' . $billing_details['cart_total'] . '
						</td>
					</tr>
				</tbody>
			</table>
			<div style="margin-top: 30px;font-size: 24px;padding: 10px;text-align: center;">
				' . $disclaimer . '
			</div>';
		}
		$html .= '</form>
				</div>
			</body>
		</html>';

		return $html;

	}
	return '<div>' . esc_html__( 'Looks like order is not found', 'pdf-generator-for-wp' ) . '</div>';
}
