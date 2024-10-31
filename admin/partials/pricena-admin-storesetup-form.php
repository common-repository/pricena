<?php

/**
 * Provide a admin area view for the store setup form
 *
 * This file is used to markup the admin-facing aspects of the store setup data.
 *
 * @link       https://ae.pricena.com
 * @since      1.0.0
 *
 * @package    Pricena
 * @subpackage Pricena/admin/partials
 */

$cat_args = array(
    'orderby'    => 'name',
    'order'      => 'asc',
    'hide_empty' => true,
);
 
$product_categories = get_terms( 'product_cat', $cat_args );

$store_data = get_option(Pricena::OPTION_STORE_REQUEST);

$selected_categories = ($store_data ? array_keys($store_data['extra']['categories']) : array());

$creds = explode(':', strrev($store_data['extra']['token']));

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">
	<div class="row">
		<h1 class="my-5"><?php _e('Store Setup Request Form', 'pricena'); ?></h1>
	</div>
	<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<div class="row mb-4">
			<div class="col-6">
				<h5>Store details</h5>
				<p>Pricena will use this information to listing your store and contact you.</p>
			</div>
			<div class="col-6">
				<div class="card mt-0" style="max-width: 100%">
					<div class="card-body">
						<div class="mb-3">
							<label for="pna_store_url" class="form-label">Store URL</label>
							<input type="text" class="form-control" id="pna_store_url" value="<?php echo esc_url( get_option('siteurl') ); ?>" disabled>
						</div>

						<div class="mb-3">
							<label for="pna_contact_name" class="form-label">Contact person fullname</label>
							<input type="text" class="form-control" name="contact_name" id="pna_contact_name" value="<?php echo esc_textarea( $store_data ? $store_data['name'] : '' ); ?>" required>
						</div>

						<div class="mb-3">
							<label for="pna_store_email" class="form-label">Contact email</label>
							<input type="email" class="form-control" id="pna_store_email" value="<?php echo esc_textarea( get_option('admin_email') ); ?>" disabled>
						</div>

						<div class="mb-3">
							<label for="pna_store_phone" class="form-label">Contact phone</label>
							<input type="text" class="form-control" name="contact_phone" id="pna_store_phone" value="<?php echo esc_textarea( $store_data ? $store_data['phone'] : '' ); ?>" required>
						</div>

						<div class="mb-3">
							<label for="pna_store_address" class="form-label">Store address</label>
							<input type="text" class="form-control" id="pna_store_address" value="<?php echo esc_textarea( get_option('woocommerce_store_address').'; '.get_option('woocommerce_store_address_2').'; '.get_option('woocommerce_store_city').'; '.get_option('woocommerce_default_country').'; '.get_option('woocommerce_store_postcode') ); ?>" disabled>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-6">
				<h5>API credentials</h5>
				<p>Pricena will use Woocommerce API to get products from categories you select. You can find or create API credentials by <a href="<?php menu_page_url('wc-settings', true); ?>&tab=advanced&section=keys" target="_blank">visiting this link</a></p>
			</div>
			<div class="col-6">
				<div class="card mt-0" style="max-width: 100%">
					<div class="card-body">
						<div class="mb-3">
							<label for="pna_api_key" class="form-label">API key</label>
							<input type="text" class="form-control" name="api_key" id="pna_api_key" value="<?php echo esc_textarea( $creds[0] ); ?>" required>
						</div>

						<div class="mb-3">
							<label for="pna_api_secret" class="form-label">API secret</label>
							<input type="text" class="form-control" name="api_secret" id="pna_api_secret" value="<?php echo esc_textarea( $creds[1] ); ?>" required>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-6">
				<h5>Product categories</h5>
				<p>Check product categories you want to be listed on Pricena</p>
			</div>
			<div class="col-6">
				<div class="card mt-0" style="max-width: 100%">
					<div class="card-body">
						<?php foreach ($product_categories as $product_category) : ?>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="<?php echo esc_textarea( $product_category->term_id ); ?>" name="categories[]" id="category<?php echo esc_textarea( $product_category->term_id ); ?>" <?php echo esc_textarea( in_array($product_category->name, $selected_categories) ? 'checked' : ''); ?>>
							<label class="form-check-label" for="category<?php echo esc_textarea( $product_category->term_id); ?>"><?php echo esc_textarea( $product_category->name ); ?></label>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-6">
				<h5>Your comment</h5>
				<p>Write here some notes if you have</p>
			</div>
			<div class="col-6">
				<div class="card mt-0" style="max-width: 100%">
					<div class="card-body">
						<div class="mb-3">
							<textarea type="text" class="form-control" name="client_comments" id="pna_client_comments" rows="5"><?php echo esc_textarea( $store_data['comments'] ); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-success"><?php _e('Send request', 'pricena'); ?></button>
				<?php wp_nonce_field('pna_process_storesetup_request', 'csrf'); ?>
				<input type="hidden" name="action" value="pna_process_storesetup_request">
			</div>
		</div>
	</form>
</div>
