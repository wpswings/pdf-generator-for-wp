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
                        @font-face {
                            font-family: SourceSansPro;
                            src: url(SourceSansPro-Regular.ttf);
                          }
                          
                          .clearfix:after {
                            content: "";
                            display: table;
                            clear: both;
                          }
                          
                          a {
                            color: #0087C3;
                            text-decoration: none;
                          }
                          
                          body {
                            position: relative;
                            
                            margin: 0 auto; 
                            color: #555555;
                            background: #FFFFFF; 
                            font-family: Arial, sans-serif; 
                            font-size: 14px; 
                            font-family: SourceSansPro;
                          }
                          
                          header {
                            padding: 10px 0;
                            margin-bottom: 20px;
                            border-bottom: 1px solid #AAAAAA;
                          }
                          
                          #logo {
                            float: left;
                            margin-top: 8px;
                          }
                          
                          #logo img {
                            height: 70px;
                          }
                          
                         #company{
                            margin-left :50%;
                            text-align: right;

                         }
                          
                          
                          #details {
                            margin-bottom: 50px;
                          }
                          
                          #client {
                            padding-left: 6px;
                            border-left: 6px solid #0087C3;
                            float: left;
                          }
                          
                          #client .to {
                            color: #777777;
                          }
                          
                          h2.name {
                            font-size: 1.4em;
                            font-weight: normal;
                            margin: 0;
                          }
                          
                          #invoice {
                        
                            text-align: right;
                          }
                          
                          #invoice h1 {
                            color: #0087C3;
                            font-size: 2.4em;
                            line-height: 1em;
                            font-weight: normal;
                            margin: 0  0 10px 0;
                          }
                          
                          #invoice .date {
                            font-size: 1.1em;
                            color: #777777;
                          }
                          
                          table {
                            width: 100%;
                            border-collapse: collapse;
                            border-spacing: 0;
                            margin-bottom: 20px;
                          }
                          
                          table th,
                          table td {
                            padding: 20px;
                            background: #EEEEEE;
                           
                            border-bottom: 1px solid #FFFFFF;
                          }
                          
                          table th {
                            white-space: nowrap;        
                            font-weight: normal;
                          }
                          
                         
                          
                          table td h3{
                            color: #57B223;
                            font-size: 1.2em;
                            font-weight: normal;
                            margin: 0 0 0.2em 0;
                          }
                          
                          table .no {
                            color: #FFFFFF;
                            
                            background: #57B223;
                          }
                          
                          table .desc {
                            text-align: left;
                          }
                          
                          table .unit {
                            background: #DDDDDD;
                          }
                          
                          table .qty {
                          }
                          
                          table .total {
                            background: #57B223;
                            color: #FFFFFF;
                          }
                          
                          table td.unit,
                          table td.qty,
                          table td.total {
                            font-size: 1.2em;
                          }
                          
                          table tbody tr:last-child td {
                            border: none;
                          }
                          
                          table tfoot td {
                            padding: 10px 10px;
                            background: #FFFFFF;
                            border-bottom: none;
                            font-size: 1.2em;
                            white-space: nowrap; 
                           
                          }
                          
                          table tfoot tr:first-child td {
                            border-top: none; 
                          }
                          
                          table tfoot tr:last-child td {
                            color: #57B223;
                            font-size: 1.4em;
                           
                          
                          }
                          
                          table tfoot tr td:first-child {
                            border: none;
                          }
                          
                          #thanks{
                            font-size: 2em;
                            margin-bottom: 50px;
                          }
                          
                          #notices{
                            padding-left: 6px;
                            border-left: 6px solid #0087C3;  
                          }
                          
                          #notices .notice {
                            font-size: 1.2em;
                          }
                          
                          footer {
                            color: #777777;
                            width: 100%;
                            height: 30px;
                            position: absolute;
                            bottom: 0;
                            border-top: 1px solid #AAAAAA;
                            padding: 8px 0;
                            text-align: center;
                          }
                          
                          
						</style>
					</head>
					<body>
					<header class="clearfix">
          <div id="logo">';
          if ( 'yes' === $is_add_logo && '' !== $logo ) {
             $html .=' <img src="' . $logo . '" >';
          }
         $html .=' </div>
      <div id="company">
        <h2 class="name"><b>' . ucfirst( $company_name ) . '</b></h2>
        <div>'.ucfirst( $company_address ).'</div>
        <div>'.$company_phone.'</div>
        <div><a href="mailto:company@example.com">'.$company_email.'</a></div>
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">INVOICE TO:</div>
          <h2 class="name">'.$billing_details['billing_company'].'</h2>
          <div class="address">'.$billing_details['billing_address_1'].',' .$billing_details['billing_city'].','.$billing_details['billing_state'].','.$billing_details['billing_postcode'].'</div>
          <div>'. $billing_details['billing_phone'].'</div>
          <div class="email"><a href="mailto:john@example.com">'.$billing_details['billing_email'].'</a></div>
        </div>
        <div id="invoice">
          <h1>'. $invoice_id.'</h1>
          <div class="date">Date of Invoice: '.$billing_details['order_created_date'].'</div>
          <div class="date">Due Date: 30/06/2014</div>
        </div>
      </div>';
      if ( 'invoice' === $type ) {
     $html .=' <table style ="text-align:center;" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr id="wpg-prod-listing-table-title">
        <th id="wpg-table-items">' . __( 'Items', 'pdf-generator-for-wp' ) . '</th>
        <th>' . __( 'Quantity', 'pdf-generator-for-wp' ) . '</th>
        <th>' . __( 'Price', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . ')</th>
        <th>' . __( 'Tax', 'pdf-generator-for-wp' ) . ' (%)</th>
        <th>' . __( 'Amount', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . ')</th>
      </tr>
        </thead>
        <tbody>
        ';
        $meta_data = '';
        $i = 1;
        $total = 0;
        
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
            $html .= '
            <tr>
						<td class="wpg-product-name no">' . $product['product_name'] . $meta_data . '</td>
						<td class="desc"> ' . $product['product_quantity'] . '</td>
						<td class="unit">' . $product['product_price'] . '</td>
						<td class="qty" >' . $product['tax_percent'] . '</td>
						<td class="total">' . $product['product_total'] . '</td>
					</tr>
            
         ';
         $i++;
         $total += $product['product_price']* $product['product_quantity'];
        }
      
     $html .=' </tbody>
        <tfoot>
          <tr>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td>' . __( 'Payment via', 'pdf-generator-for-wp' ) . ' : ' . $billing_details['payment_method'] . '</td>
        </tr>
        <tr>
        <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td>' . __( 'Subtotal', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $billing_details['order_subtotal'] . '</td>
        </tr>
        <tr>
        <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td>' . __( 'Shipping', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $shipping_details['shipping_total'] . '</td>
        </tr>
        <tr>
        <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td>' . __( 'Total tax', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $billing_details['tax_totals'] . '</td>
        </tr>';
        $pgfw_coupon_details = $billing_details['coupon_details'];
			foreach ( $pgfw_coupon_details as $key => $price ) {
				$html .= '<tr>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
							<td>' . $key . '(' . $billing_details['order_currency'] . '): ' . $price . '</td>
						</tr>';
			}
     $html .='   <tr>
        <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
								<td>' . __( 'Total', 'pdf-generator-for-wp' ) . '(' . $billing_details['order_currency'] . '): ' . $billing_details['cart_total'] . '</td>
							</tr>
        </tfoot>
      </table>
      <div id="thanks">Thank you!</div>
      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice"><b>' . $disclaimer . '</b></div>
      </div>
    </main>';
      }
 $html .='   <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>	
	
				</body>
			</html>';

		return $html;
	}
	return '<div>' . esc_html__( 'Looks like order is not found', 'pdf-generator-for-wp' ) . '</div>';
}
