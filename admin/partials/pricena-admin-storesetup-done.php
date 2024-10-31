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
$store_data = get_option(Pricena::OPTION_STORE_REQUEST);
if (is_array($store_data) && array_key_exists('categories', $store_data)) {
	$store_data['categories'] = json_decode($store_data['categories']);
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">
	<div class="row">
		<h1 class="my-5"><?php _e('Congratulations', 'pna'); ?></h1>
	</div>
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-body">
					<p><?php _e('We have received your details, and will start the process of setting up your store. Our team will contact you within 1-2 days regarding the next steps. Below are your details.', 'pna'); ?></p>
					<?php if (is_array($store_data) && !empty($store_data)) : ?>
					<ul class="list-group list-group-flush">
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">Store URL</div>
								<div class="col-6"><?php echo esc_url( $store_data['url'] ); ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">Contact Person Fullname</div>
								<div class="col-6"><?php echo esc_textarea( $store_data['name'] ); ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">Contact email</div>
								<div class="col-6"><?php echo esc_textarea( $store_data['email'] ); ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">Store Phone</div>
								<div class="col-6"><?php echo esc_textarea( $store_data['phone'] ); ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">Store Address</div>
								<div class="col-6"><?php echo esc_textarea( $store_data['address'] ); ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">API Key</div>
								<div class="col-6">*****************************************</div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">API Secret</div>
								<div class="col-6">*****************************************</div>
							</div>
						</li>
						<?php if ( is_array( $store_data['categories'] ) ) : ?>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">Product Categories</div>
								<div class="col-6">
									<?php foreach ( $store_data['categories'] as $category ) : ?>
										<p><?php echo esc_textarea( $category->name ); ?></p>
									<?php endforeach; ?>
								</div>
							</div>
						</li>
						<?php endif; ?>
						<li class="list-group-item">
							<div class="row">
								<div class="col-6">Comment</div>
								<div class="col-6"><?php echo esc_textarea( $store_data['comments'] ); ?></div>
							</div>
						</li>
					</ul>
					<?php endif; ?>

					<a href="<?php echo esc_url( menu_page_url('pna-settings', false).'&tab=storesetup&section=request' ); ?>" class="btn btn-success">Edit Storesetup Data</a>
				</div>
			</div>
		</div>
	</div>
</div>