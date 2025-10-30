<?php
	// REMOVED: External add-ons feed for white label version
	
	echo '
	<div class="awr-news-header">
		<div class="awr-news-header-big">'. esc_html__("Reporting Add-ons",__PW_REPORT_WCREPORT_TEXTDOMAIN__) .'</div>
		<div class="awr-news-header-mini">'. esc_html__("No external add-ons available in white label version",__PW_REPORT_WCREPORT_TEXTDOMAIN__) .'</div>
	</div>';
	
	echo '<div class="row awr-addons-wrapper">';
	echo '<p style="text-align: center; padding: 20px;">'. esc_html__("This is a white-labeled version. Add-ons feed has been removed.",__PW_REPORT_WCREPORT_TEXTDOMAIN__) .'</p>';
	echo '</div>';
?>
