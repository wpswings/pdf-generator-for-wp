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
		<tr><th scope="row" class="check-column"><input type="checkbox" name="bulk-delete[]" value="31"></th><td class="id column-id has-row-actions column-primary hidden" data-colname="ID">31<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="postid column-postid" data-colname="Post ID">31</td><td class="username column-username" data-colname="Username">admin</td><td class="email column-email" data-colname="Email">dev-email@wpengine.local</td><td class="time column-time" data-colname="Time">July 18, 2023 6:25 am</td></tr><tr><th scope="row" class="check-column"><input type="checkbox" name="bulk-delete[]" value="34"></th><td class="id column-id has-row-actions column-primary hidden" data-colname="ID">34<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="postid column-postid" data-colname="Post ID">25</td><td class="username column-username" data-colname="Username">admin</td><td class="email column-email" data-colname="Email">dev-email@wpengine.local</td><td class="time column-time" data-colname="Time">July 18, 2023 9:50 am</td></tr>	</tbody>

	<tfoot>
	<tr>
		<td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></td><th scope="col" class="manage-column column-id hidden column-primary">ID</th><th scope="col" class="manage-column column-postid">Post ID</th><th scope="col" class="manage-column column-username sortable asc"><a href="#"><span>Username</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-email sortable asc"><a href=""><span>Email</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-time sortable asc"><a href="#"><span>Time</span><span class="sorting-indicator"></span></a></th>	</tr>
</tfoot></table></form>';
	?>
</div>
