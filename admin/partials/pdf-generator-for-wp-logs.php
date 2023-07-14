<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for PDF logs.
 *
 * @link       https://wpswings.com/
 * @since      3.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!--  template file for admin settings. -->
<div class="wpg-secion-wrap wps_pgfw_pro_tag">
	<?php
	
	echo '<form method="POST" action=""><input type="hidden" name="delete_nonce" value="366a82202a"><input type="hidden" id="_wpnonce" name="_wpnonce" value="470d3c82fb"><input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=pdf_generator_for_wp_menu&amp;pgfw_tab=pdf-generator-for-wp-logs">	<div class="tablenav top">

	<table class="wp-list-table widefat fixed striped table-view-list wp-swings_page_pdf_generator_for_wp_menu">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td><th scope="col" id="id" class="manage-column column-id hidden column-primary">ID</th><th scope="col" id="postid" class="manage-column column-postid">Post ID</th><th scope="col" id="username" class="manage-column column-username sortable asc"><a href="#"><span>Username</span><span class="sorting-indicator"></span></a></th><th scope="col" id="email" class="manage-column column-email sortable asc"><a href="#"><span>Email</span><span class="sorting-indicator"></span></a></th><th scope="col" id="time" class="manage-column column-time sortable asc"><a href="#"><span>Time</span><span class="sorting-indicator"></span></a></th>	</tr>
	</thead>

	<tbody id="the-list">
		<tr class="no-items"><td class="colspanchange" colspan="5">No items found.</td></tr>	</tbody>

	<tfoot>
	<tr>
		<td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></td><th scope="col" class="manage-column column-id hidden column-primary">ID</th><th scope="col" class="manage-column column-postid">Post ID</th><th scope="col" class="manage-column column-username sortable asc"><a href="#"><span>Username</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-email sortable asc"><a href=""><span>Email</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-time sortable asc"><a href="#"><span>Time</span><span class="sorting-indicator"></span></a></th>	</tr>
</form>';
	?>
</div>
