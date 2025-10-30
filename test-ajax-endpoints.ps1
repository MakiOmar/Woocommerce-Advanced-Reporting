# PowerShell Test Script for PW Advanced Woo Reporting AJAX Endpoints
# This script tests the main AJAX endpoints

# Configuration
$SiteUrl = "http://localhost/alituts"
$AdminAjax = "$SiteUrl/wp-admin/admin-ajax.php"

Write-Host "======================================" -ForegroundColor Cyan
Write-Host "PW Advanced Woo Reporting - AJAX Tests" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""

# Test 1: pw_chosen_ajax (Product Search)
Write-Host "Test 1: Product Search AJAX" -ForegroundColor Yellow
Write-Host "Endpoint: wp_ajax_pw_chosen_ajax"
Write-Host "Method: POST"
Write-Host ""

try {
    $body = @{
        action = "pw_chosen_ajax"
        postdata = "target_type=all_products&q=test"
        nonce = "INVALID_NONCE"
    }
    
    $response = Invoke-WebRequest -Uri $AdminAjax -Method Post -Body $body -UseBasicParsing -ErrorAction Stop
    Write-Host "Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response: $($response.Content.Substring(0, [Math]::Min(200, $response.Content.Length)))"
} catch {
    Write-Host "Error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "---"
Write-Host ""

# Test 2: pw_rpt_fetch_data (Fetch Report Data)
Write-Host "Test 2: Fetch Report Data AJAX" -ForegroundColor Yellow
Write-Host "Endpoint: wp_ajax_pw_rpt_fetch_data"
Write-Host "Method: POST"
Write-Host ""

try {
    $body = @{
        action = "pw_rpt_fetch_data"
        postdata = "table_names=product"
        nonce = "INVALID_NONCE"
    }
    
    $response = Invoke-WebRequest -Uri $AdminAjax -Method Post -Body $body -UseBasicParsing -ErrorAction Stop
    Write-Host "Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response Length: $($response.Content.Length) bytes"
} catch {
    Write-Host "Error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "---"
Write-Host ""

# Test 3: pw_add_to_fav (Add to Favorites)
Write-Host "Test 3: Add to Favorites AJAX" -ForegroundColor Yellow
Write-Host "Endpoint: wp_ajax_pw_add_to_fav"
Write-Host "Method: POST"
Write-Host ""

try {
    $body = @{
        action = "pw_add_to_fav"
        postdata = "smenu=product"
        nonce = "INVALID_NONCE"
    }
    
    $response = Invoke-WebRequest -Uri $AdminAjax -Method Post -Body $body -UseBasicParsing -ErrorAction Stop
    Write-Host "Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response: $($response.Content)"
} catch {
    Write-Host "Error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "---"
Write-Host ""

# Test 4: Security Test - No Nonce
Write-Host "Test 4: Security Test - No Nonce" -ForegroundColor Yellow
Write-Host "Testing without nonce parameter"
Write-Host ""

try {
    $body = @{
        action = "pw_chosen_ajax"
        postdata = "target_type=all_products&q=test"
    }
    
    $response = Invoke-WebRequest -Uri $AdminAjax -Method Post -Body $body -UseBasicParsing -ErrorAction Stop
    Write-Host "Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response: $($response.Content)"
} catch {
    Write-Host "Error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "---"
Write-Host ""

# Test 5: Security Test - SQL Injection Attempt
Write-Host "Test 5: Security Test - SQL Injection Attempt" -ForegroundColor Yellow
Write-Host "Testing with potential SQL injection payload"
Write-Host ""

try {
    $body = @{
        action = "pw_chosen_ajax"
        postdata = "target_type=all_products&q=' OR '1'='1"
        nonce = "INVALID_NONCE"
    }
    
    $response = Invoke-WebRequest -Uri $AdminAjax -Method Post -Body $body -UseBasicParsing -ErrorAction Stop
    Write-Host "Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response: $($response.Content.Substring(0, [Math]::Min(100, $response.Content.Length)))"
} catch {
    Write-Host "Error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "---"
Write-Host ""

# Summary
Write-Host "======================================" -ForegroundColor Green
Write-Host "Test Summary:" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Green
Write-Host ""
Write-Host "All endpoints tested"
Write-Host "Nonce verification checked"
Write-Host "Basic security tests completed"
Write-Host ""
Write-Host "Note: For full testing use valid nonces from WordPress session"

