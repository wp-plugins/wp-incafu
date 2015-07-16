<div class="wrap">

	<?php screen_icon(); ?>

	<form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form"
		  name="<?php echo $plugin_id; ?>_options_form">

		<?php settings_fields($plugin_id . '_options'); ?>

		<h2>WP-Incafu &raquo; Options</h2>
		<table class="widefat">
			<thead>
			<tr>
				<th><input type="submit" name="submit" value="Enregistrer" class="button-primary"/></th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th><input type="submit" name="submit" value="Enregistrer" class="button-primary"/></th>
			</tr>
			</tfoot>
			<tbody>
			<tr>
				<td>
					<label for="incafu_url">
						<p>Incafu Store URL (boutique.php)</p>

						<p><input type="text" name="incafu_url" value="<?php echo get_option('incafu_url'); ?>"
								  size="100"/></p>
					</label>
				</td>
			</tr>
			<tr>
				<td>
					<label for="incafu_checkout">
						<p>Show checkout</p>

						<p><input type="checkbox" name="incafu_checkout"
								  value="1" <?php checked(1, get_option('incafu_checkout'), true); ?> /></p>
					</label>
				</td>
			</tr>
			<tr>
				<td>
					<label for="incafu_css">
						<p>Extra CSS</p>

						<p><textarea name="incafu_css"><?php echo get_option('incafu_css'); ?></textarea></p>
					</label>
				</td>
			</tr>
			<tr>
				<td>
					<label for="incafu_js">
						<p>Extra JS</p>

						<p><textarea name="incafu_js"><?php echo get_option('incafu_js'); ?></textarea></p>
					</label>
				</td>
			</tr>

			</tbody>
		</table>

	</form>

</div>
