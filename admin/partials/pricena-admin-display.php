<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ae.pricena.com
 * @since      1.0.0
 *
 * @package    Pricena
 * @subpackage Pricena/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">
	<div class="row">
		<h1 class="my-5"><?php _e('Pricena Dashboard', 'pna'); ?></h1>
	</div>
	<div class="row">
		<div class="col">
			<a href="<?php echo esc_url( menu_page_url('pna-settings', false).'&tab=storesetup&section=request' ); ?>" class="btn btn-success">Edit Storesetup Data</a>
		</div>
	</div>
</div>