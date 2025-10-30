# Comprehensive URL and Form Testing Script
# Tests all admin URLs and report pages

$SiteUrl = "http://localhost/alituts"
$AdminUrl = "$SiteUrl/wp-admin/admin.php"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "URL & Form Testing - AWR Plugin" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Report pages to test
$reportPages = @(
    'product',
    'category',
    'coupon',
    'customer',
    'brand',
    'tags',
    'variation',
    'orderstatus',
    'paymentgateway',
    'billingcountry',
    'billingstate',
    'billingcity',
    'recentorder',
    'taxreport',
    'profit',
    'refunddetails',
    'stock_list',
    'stock_min_level',
    'stock_max_level',
    'stock_zero_level',
    'details',
    'customer_analysis',
    'abandoned_cart',
    'abandoned_product',
    'coupon_discount',
    'customer_guest',
    'customer_category',
    'customer_min_max',
    'customer_no_purchased',
    'customer_order_frequently',
    'customerbuyproducts',
    'custom_taxonomy',
    'clinic',
    'country_per_month',
    'ord_status_per_month',
    'order_per_country',
    'order_per_custom_shipping',
    'order_product_analysis',
    'order_variation_analysis',
    'order_status_change',
    'payment_per_month',
    'prod_per_country',
    'prod_per_state',
    'prod_per_month',
    'product_per_users',
    'product_variation_qty',
    'projected_actual_sale',
    'stock_list_sales',
    'stock_summary_avg',
    'summary_per_month',
    'variation_per_month',
    'variation_stock',
    'tax_reports',
    'details_full',
    'details_combined',
    'details_depot',
    'details_full_shipping',
    'details_full_shipping_tax',
    'details_order_country',
    'details_tax_field',
    'details_tickera',
    'details_user_id'
)

$passed = 0
$failed = 0
$errors = @()

Write-Host "Testing $($reportPages.Count) report URLs..." -ForegroundColor Yellow
Write-Host ""

foreach ($page in $reportPages) {
    $url = "$AdminUrl" + "?page=wcx_wcreport_plugin_$page" + "_report&parent=$page"
    
    try {
        Write-Host "Testing: $page..." -NoNewline
        $response = Invoke-WebRequest -Uri $url -Method Get -UseBasicParsing -TimeoutSec 10 -ErrorAction Stop
        
        if ($response.StatusCode -eq 200) {
            Write-Host " OK" -ForegroundColor Green
            $passed++
        } else {
            Write-Host " UNEXPECTED STATUS: $($response.StatusCode)" -ForegroundColor Yellow
            $failed++
            $errors += "$page : Status $($response.StatusCode)"
        }
    }
    catch {
        Write-Host " FAILED" -ForegroundColor Red
        $failed++
        $errors += "$page : $($_.Exception.Message)"
    }
    
    Start-Sleep -Milliseconds 100
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Test Summary" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Total URLs Tested: $($reportPages.Count)"
Write-Host "Passed: $passed" -ForegroundColor Green
Write-Host "Failed: $failed" -ForegroundColor Red
Write-Host ""

if ($errors.Count -gt 0) {
    Write-Host "Errors:" -ForegroundColor Red
    foreach ($error in $errors) {
        Write-Host "  - $error" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Note: Failures are expected if WordPress is not running" -ForegroundColor Yellow
Write-Host "or if user is not authenticated." -ForegroundColor Yellow
Write-Host ""
Write-Host "To run full tests:" -ForegroundColor Cyan
Write-Host "1. Start WAMP server" -ForegroundColor White
Write-Host "2. Login to WordPress admin" -ForegroundColor White
Write-Host "3. Keep browser session active" -ForegroundColor White
Write-Host "4. Run this script again" -ForegroundColor White

