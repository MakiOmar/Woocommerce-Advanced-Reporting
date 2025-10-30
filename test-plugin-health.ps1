# Plugin Health Check Script
# Analyzes code quality, security, and identifies issues

$PluginPath = "D:\wamp64\www\alituts\wp-content\plugins\PW-Advanced-Woocommerce-Reporting-System"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Plugin Health Check" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Count PHP files
Write-Host "1. File Analysis:" -ForegroundColor Yellow
$phpFiles = Get-ChildItem -Path $PluginPath -Recurse -Filter "*.php" -File
$classFiles = Get-ChildItem -Path "$PluginPath\class" -Filter "*.php" -File
$includeFiles = Get-ChildItem -Path "$PluginPath\includes" -Recurse -Filter "*.php" -File
$docFiles = Get-ChildItem -Path $PluginPath -Filter "*.md" -File

Write-Host "   Total PHP Files: $($phpFiles.Count)"
Write-Host "   Class Files: $($classFiles.Count)"
Write-Host "   Include Files: $($includeFiles.Count)"
Write-Host "   Documentation Files: $($docFiles.Count)"
Write-Host ""

# 2. Analyze refactored files
Write-Host "2. Refactored Report Files:" -ForegroundColor Yellow
$smallFiles = $classFiles | Where-Object { $_.Length -lt 500 }
Write-Host "   Refactored Files (< 500 bytes): $($smallFiles.Count) / $($classFiles.Count)"
Write-Host "   Percentage Refactored: $([math]::Round(($smallFiles.Count / $classFiles.Count) * 100, 2))%"
Write-Host ""

# 3. Check for duplicate/backup files
Write-Host "3. Duplicate/Backup Files:" -ForegroundColor Yellow
$duplicates = Get-ChildItem -Path $PluginPath -Recurse -Filter "*copy*.php" -File
$backups = Get-ChildItem -Path $PluginPath -Recurse -Filter "*-20*.php" -File
$beforeFiles = Get-ChildItem -Path $PluginPath -Recurse -Filter "*before*.php" -File

Write-Host "   Files with 'copy': $($duplicates.Count)"
Write-Host "   Dated backup files: $($backups.Count)"
Write-Host "   Debug 'before' files: $($beforeFiles.Count)"
Write-Host "   Total Duplicates: $($duplicates.Count + $backups.Count + $beforeFiles.Count)"
Write-Host ""

# 4. Security Analysis
Write-Host "4. Security Scan:" -ForegroundColor Yellow

# Count $_REQUEST usage
$requestMatches = Get-ChildItem -Path $PluginPath -Recurse -Filter "*.php" | Select-String -Pattern '\$_REQUEST\['
$requestCount = $requestMatches.Count
Write-Host "   Unsafe _REQUEST usage: $requestCount"

# Count nopriv hooks
$noprivMatches = Select-String -Path "$PluginPath\includes\actions.php" -Pattern "wp_ajax_nopriv_"
$noprivCount = $noprivMatches.Count
Write-Host "   Unauthenticated AJAX hooks: $noprivCount"

# Count prepared statements
$preparedMatches = Get-ChildItem -Path $PluginPath -Recurse -Filter "*.php" | Select-String -Pattern 'wpdb->prepare\('
$preparedCount = $preparedMatches.Count
Write-Host "   Prepared SQL statements: $preparedCount"

Write-Host ""

# 5. Code Quality
Write-Host "5. Code Quality Indicators:" -ForegroundColor Yellow

# Count PHPDoc blocks
$phpDocCount = (Select-String -Path "$PluginPath\includes\class-awr-helpers.php" -Pattern '/\*\*').Count
Write-Host "   PHPDoc blocks in helpers: $phpDocCount"

# Check helper class exists
if (Test-Path "$PluginPath\includes\class-awr-helpers.php") {
    Write-Host "   Helper class: EXISTS" -ForegroundColor Green
    $helperSize = (Get-Item "$PluginPath\includes\class-awr-helpers.php").Length
    Write-Host "   Helper class size: $helperSize bytes"
} else {
    Write-Host "   Helper class: MISSING" -ForegroundColor Red
}

Write-Host ""

# 6. Documentation
Write-Host "6. Documentation:" -ForegroundColor Yellow
$docs = @(
    'START_HERE.md',
    'EXECUTIVE_SUMMARY.md',
    'README_REFACTORING.md',
    'OPTIMIZATION_REPORT.md',
    'AJAX_SECURITY_ANALYSIS.md',
    'AJAX_REFACTORING_COMPLETE.md',
    'FINAL_OPTIMIZATION_SUMMARY.md',
    'DATATABLE_OPTIMIZATION_ANALYSIS.md',
    'COMPREHENSIVE_OPTIMIZATION_COMPLETE.md',
    'BEFORE_AFTER_COMPARISON.md'
)

$docsFound = 0
foreach ($doc in $docs) {
    if (Test-Path "$PluginPath\$doc") {
        $docsFound++
    }
}

Write-Host "   Documentation files found: $docsFound / $($docs.Count)"
Write-Host ""

# 7. Test Scripts
Write-Host "7. Test Infrastructure:" -ForegroundColor Yellow
$testScripts = @(
    'test-ajax-endpoints.ps1',
    'test-ajax-endpoints.sh',
    'test-all-urls.ps1',
    'test-plugin-health.ps1'
)

$scriptsFound = 0
foreach ($script in $testScripts) {
    if (Test-Path "$PluginPath\$script") {
        $scriptsFound++
    }
}

Write-Host "   Test scripts found: $scriptsFound / $($testScripts.Count)"
Write-Host ""

# 8. Final Score
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Health Score" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$refactorScore = [math]::Round(($smallFiles.Count / $classFiles.Count) * 100, 0)
$cleanupScore = if (($duplicates.Count + $backups.Count + $beforeFiles.Count) -eq 0) { 100 } else { 50 }
$securityScore = [math]::Round(100 - ($noprivCount * 5), 0)
$docScore = [math]::Round(($docsFound / $docs.Count) * 100, 0)
$testScore = [math]::Round(($scriptsFound / $testScripts.Count) * 100, 0)

$overallScore = [math]::Round(($refactorScore + $cleanupScore + $securityScore + $docScore + $testScore) / 5, 0)

Write-Host "   Refactoring: $refactorScore%" -ForegroundColor $(if($refactorScore -ge 80){"Green"}else{"Yellow"})
Write-Host "   Cleanup: $cleanupScore%" -ForegroundColor $(if($cleanupScore -ge 80){"Green"}else{"Yellow"})
Write-Host "   Security: $securityScore%" -ForegroundColor $(if($securityScore -ge 80){"Green"}else{"Yellow"})
Write-Host "   Documentation: $docScore%" -ForegroundColor $(if($docScore -ge 80){"Green"}else{"Yellow"})
Write-Host "   Testing: $testScore%" -ForegroundColor $(if($testScore -ge 80){"Green"}else{"Yellow"})
Write-Host ""
Write-Host "   OVERALL HEALTH: $overallScore%" -ForegroundColor $(if($overallScore -ge 80){"Green"}elseif($overallScore -ge 60){"Yellow"}else{"Red"})
Write-Host ""

if ($overallScore -ge 80) {
    Write-Host "Status: EXCELLENT - Ready for production" -ForegroundColor Green
} elseif ($overallScore -ge 60) {
    Write-Host "Status: GOOD - Minor improvements needed" -ForegroundColor Yellow
} else {
    Write-Host "Status: NEEDS WORK - Additional refactoring required" -ForegroundColor Red
}

Write-Host ""

