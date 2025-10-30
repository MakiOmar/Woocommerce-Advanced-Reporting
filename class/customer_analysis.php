<?php
/**
 * Customer Analysis Report View
 *
 * @package PW_Advanced_Woo_Reporting
 */

global $pw_rpt_main_class;

PW_Report_AWR_Helpers::render_standard_report(
	$pw_rpt_main_class,
	'customer_analysis',
	__( 'Configuration', __PW_REPORT_WCREPORT_TEXTDOMAIN__ )
);
