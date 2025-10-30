<?php
/**
 * Order Status Per Month Report View
 *
 * @package PW_Advanced_Woo_Reporting
 */

global $pw_rpt_main_class;

PW_Report_AWR_Helpers::render_standard_report(
	$pw_rpt_main_class,
	'ord_status_per_month',
	__( 'Configuration', __PW_REPORT_WCREPORT_TEXTDOMAIN__ )
);
