<?php
/**
 * Payment Gateway Report View
 *
 * @package PW_Advanced_Woo_Reporting
 */

global $pw_rpt_main_class;

PW_Report_AWR_Helpers::render_standard_report(
	$pw_rpt_main_class,
	'paymentgateway',
	__( 'Configuration', __PW_REPORT_WCREPORT_TEXTDOMAIN__ )
);
