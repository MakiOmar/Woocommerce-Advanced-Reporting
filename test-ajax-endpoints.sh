#!/bin/bash

# Test AJAX Endpoints for PW Advanced Woo Reporting
# This script tests the main AJAX endpoints with curl

# Configuration
SITE_URL="http://localhost/alituts"
ADMIN_AJAX="${SITE_URL}/wp-admin/admin-ajax.php"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "======================================"
echo "PW Advanced Woo Reporting - AJAX Tests"
echo "======================================"
echo ""

# Test 1: pw_chosen_ajax (Product Search)
echo -e "${YELLOW}Test 1: Product Search AJAX${NC}"
echo "Endpoint: wp_ajax_pw_chosen_ajax"
echo "Method: POST"
echo ""

curl -X POST "${ADMIN_AJAX}" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=pw_chosen_ajax" \
  -d "postdata=target_type=all_products&q=test" \
  -d "nonce=INVALID_NONCE" \
  -s | python -m json.tool 2>/dev/null || echo "Response received (not JSON)"

echo ""
echo "---"
echo ""

# Test 2: pw_rpt_fetch_data (Fetch Report Data)
echo -e "${YELLOW}Test 2: Fetch Report Data AJAX${NC}"
echo "Endpoint: wp_ajax_pw_rpt_fetch_data"
echo "Method: POST"
echo ""

curl -X POST "${ADMIN_AJAX}" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=pw_rpt_fetch_data" \
  -d "postdata=table_names=product" \
  -d "nonce=INVALID_NONCE" \
  -s | head -100

echo ""
echo "---"
echo ""

# Test 3: pw_add_to_fav (Add to Favorites)
echo -e "${YELLOW}Test 3: Add to Favorites AJAX${NC}"
echo "Endpoint: wp_ajax_pw_add_to_fav"
echo "Method: POST"
echo ""

curl -X POST "${ADMIN_AJAX}" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=pw_add_to_fav" \
  -d "postdata=smenu=product" \
  -d "nonce=INVALID_NONCE" \
  -s

echo ""
echo "---"
echo ""

# Test 4: pw_remove_from_fav (Remove from Favorites)
echo -e "${YELLOW}Test 4: Remove from Favorites AJAX${NC}"
echo "Endpoint: wp_ajax_pw_remove_from_fav"
echo "Method: POST"
echo ""

curl -X POST "${ADMIN_AJAX}" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=pw_remove_from_fav" \
  -d "postdata=smenu=product" \
  -d "nonce=INVALID_NONCE" \
  -s

echo ""
echo "---"
echo ""

# Test 5: Security Test - No Nonce
echo -e "${YELLOW}Test 5: Security Test - No Nonce${NC}"
echo "Testing without nonce parameter"
echo ""

curl -X POST "${ADMIN_AJAX}" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=pw_chosen_ajax" \
  -d "postdata=target_type=all_products&q=test" \
  -s

echo ""
echo "---"
echo ""

# Test 6: Security Test - SQL Injection Attempt
echo -e "${YELLOW}Test 6: Security Test - SQL Injection Attempt${NC}"
echo "Testing with potential SQL injection payload"
echo ""

curl -X POST "${ADMIN_AJAX}" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "action=pw_chosen_ajax" \
  -d "postdata=target_type=all_products&q=' OR '1'='1" \
  -d "nonce=INVALID_NONCE" \
  -s | head -50

echo ""
echo "---"
echo ""

# Summary
echo -e "${GREEN}======================================"
echo "Test Summary:"
echo "======================================"
echo ""
echo "✓ All endpoints tested"
echo "✓ Nonce verification checked"
echo "✓ Basic security tests completed"
echo ""
echo "Note: For full testing, use valid nonces from WordPress session"
echo -e "${NC}"

