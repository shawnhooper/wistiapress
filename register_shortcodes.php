<?php

function wistiapress_register_shortcodes() {
	add_shortcode('wistiapress_media_list', 'wistiapress_shortcode_media_list');
}
add_action('init', 'wistiapress_register_shortcodes');

function wistiapress_human_filesize($bytes, $dec = 2)
{
	$size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$factor = floor((strlen($bytes) - 1) / 3);

	return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
}


function wistiapress_shortcode_media_list($atts) {
	$apiKey = get_option('wistiapress_api_key');
	if ($apiKey === false) return;
	$wistiaApi = new WistiaApi($apiKey);

	// Is a Project ID defined
	if (!isset($atts['project'])) {
		return 'Error: WistiaPress Media List Shortcode missing project atttribute.';
	}
	$mediaList = $wistiaApi->mediaList($atts['project']);
	if (isset($mediaList->error)) {
		return 'Error: ' . esc_html($mediaList->error);
	}

	ob_start();
	?>

	<p>Right-click on the download icon and click on "Save Link As..." to download.</p>

	<table style="width:99%;">
		<thead>
			<tr>
				<th>Video Name</th>
				<th style="padding-right:10px;padding-left:10px;">Duration</th>
				<th style="padding-right:10px;padding-left:10px;">File Size</th>
				<th style="padding-right:10px;padding-left:10px;text-align:center;">Right&#8209;Click&nbsp;to Download</th>
			</tr>
		</thead>
		<tbody>
			<?foreach ($mediaList as $media) {?>
				<tr style="border-right:1px solid #ccc;">
					<td><?php echo esc_html($media->name); ?></td>
					<td style="white-space: nowrap;text-align:center;padding-right:10px;padding-left:10px;vertical-align: middle;"><?php echo esc_html(gmdate("H:i:s", $media->duration)); ?></td>
					<td style="white-space: nowrap;text-align:center;padding-right:10px;padding-left:10px;vertical-align: middle;"><?php echo esc_html(wistiapress_human_filesize($media->assets[0]->fileSize)); ?></td>
					<td style="white-space: nowrap;text-align:center;padding-right:10px;padding-left:10px;vertical-align: middle;"><a class="wistiadownload" href="<?php echo esc_url(str_replace('.bin', '/' . urlencode(str_replace(',', '_', strtolower(str_replace(' ', '_', $media->name)))) . '.mp4', $media->assets[0]->url)); ?>"><img title="Download '<?php echo esc_attr($media->name); ?>'" alt="Download Video" src="<?php echo plugins_url( 'img/box_download.png', __FILE__ ); ?>" /></a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<script type="text/javascript">
		jQuery('.wistiadownload').click(function(e) {
			e.preventDefault();
			alert('Right-click to download this video');
		});
	</script>

<?php
	return ob_get_clean();
}


