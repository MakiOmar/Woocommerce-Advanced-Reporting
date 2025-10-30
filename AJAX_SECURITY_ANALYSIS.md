# AJAX Security Analysis & Improvements

## Overview
Analysis of AJAX handlers in `includes/actions.php` and recommendations for security hardening.

---

## Current AJAX Endpoints

### 1. `pw_chosen_ajax` - Product/Customer Search
**File**: `includes/actions.php` (Lines 5-58)
**Purpose**: Live search for products and customers

#### Current Issues:
- ✅ Has nonce verification
- ⚠️ Uses `parse_str()` on unsanitized `$_REQUEST['postdata']`
- ⚠️ No capability checking
- ⚠️ Search query (`$my_array_of_vars['q']`) not sanitized
- ⚠️ Allows `nopriv` access (unauthenticated users)

#### Security Risks:
- **Variable Override**: `parse_str()` can override existing variables
- **SQL Injection**: Search query passed directly to functions
- **Information Disclosure**: Unauthenticated access to product/customer data

---

### 2. `pw_rpt_fetch_data` - Fetch Report Data
**File**: `includes/actions.php` (Lines 62-100)
**Purpose**: Main data fetching for all reports

#### Current Issues:
- ✅ Has nonce verification
- ⚠️ Uses `parse_str()` on unsanitized `$_REQUEST['postdata']`
- ⚠️ No capability checking
- ⚠️ Table name not validated against whitelist
- ⚠️ Allows `nopriv` access (unauthenticated users)

#### Security Risks:
- **Arbitrary Report Access**: Any report can be accessed
- **Data Exposure**: Sensitive business data accessible without authentication
- **Performance**: No rate limiting on expensive queries

---

### 3. `pw_rpt_int_customer_details` - Intelligence Customer Details
**File**: `includes/actions.php` (Lines 105-129)
**Purpose**: Fetch customer details for intelligence reports

#### Current Issues:
- ✅ Has nonce verification
- ⚠️ Uses `parse_str()` on unsanitized data
- ⚠️ No capability checking
- ⚠️ `order_id` not sanitized
- ⚠️ Includes file based on user input
- ⚠️ Allows `nopriv` access

#### Security Risks:
- **Information Disclosure**: Customer PII exposed
- **File Inclusion**: Potential path traversal (mitigated by hardcoded path)

---

### 4. `pw_rpt_int_add_note` - Add Note to Intelligence Report
**File**: `includes/actions.php` (Lines 132-148+)
**Purpose**: Add notes to customer records

#### Current Issues:
- ✅ Has nonce verification
- ⚠️ Uses `parse_str()` on unsanitized data
- ⚠️ No capability checking
- ⚠️ Allows `nopriv` access
- ⚠️ Note content not sanitized

#### Security Risks:
- **Stored XSS**: Unsanitized notes stored and displayed
- **Data Manipulation**: Unauthenticated users can add notes
- **Spam/Abuse**: No rate limiting

---

### 5. `pw_add_to_fav` & `pw_remove_from_fav` - Favorites Management
**Purpose**: Manage favorite reports

#### Current Issues:
- ⚠️ Similar pattern to above (need to analyze further)
- ⚠️ Likely missing capability checks

---

## Recommended Fixes

### Priority 1: Remove `nopriv` Access
```php
// BEFORE (Insecure):
add_action('wp_ajax_pw_chosen_ajax', 'pw_chosen_ajax');
add_action('wp_ajax_nopriv_pw_chosen_ajax', 'pw_chosen_ajax'); // ❌ REMOVE

// AFTER (Secure):
add_action('wp_ajax_pw_chosen_ajax', 'pw_chosen_ajax'); // ✅ Only authenticated
```

### Priority 2: Add Capability Checks
```php
// Add at start of each AJAX function:
if ( ! current_user_can( 'manage_woocommerce' ) ) {
    wp_send_json_error( array( 'message' => 'Insufficient permissions' ), 403 );
}
```

### Priority 3: Sanitize All Input
```php
// BEFORE:
parse_str($_REQUEST['postdata'], $my_array_of_vars);
$order_id = $my_array_of_vars['row_id'];

// AFTER:
parse_str( sanitize_text_field( wp_unslash( $_REQUEST['postdata'] ?? '' ) ), $my_array_of_vars );
$order_id = absint( $my_array_of_vars['row_id'] ?? 0 );
```

### Priority 4: Validate Table Names
```php
// Add whitelist:
$allowed_tables = array(
    'product', 'category', 'coupon', 'customer', 'details',
    'orderstatus', 'paymentgateway', 'billingcountry', // ... etc
);

$table_name = sanitize_text_field( $my_array_of_vars['table_names'] ?? '' );
if ( ! in_array( $table_name, $allowed_tables, true ) ) {
    wp_send_json_error( array( 'message' => 'Invalid table name' ), 400 );
}
```

### Priority 5: Use Helper Functions
```php
// Use centralized helpers from class-awr-helpers.php:
if ( ! PW_Report_AWR_Helpers::verify_nonce( 'nonce', 'pw_livesearch_nonce' ) ) {
    PW_Report_AWR_Helpers::json_error( 'Invalid nonce', 403 );
}

if ( ! PW_Report_AWR_Helpers::current_user_can() ) {
    PW_Report_AWR_Helpers::json_error( 'Insufficient permissions', 403 );
}
```

---

## Refactored Example

### Original Code:
```php
add_action('wp_ajax_pw_chosen_ajax', 'pw_chosen_ajax');
add_action('wp_ajax_nopriv_pw_chosen_ajax', 'pw_chosen_ajax'); // ❌
function pw_chosen_ajax() {
    global $wpdb,$pw_rpt_main_class;
    
    parse_str( $_REQUEST['postdata'], $my_array_of_vars ); // ❌
    
    $nonce = $_REQUEST['nonce']; // ❌
    if(!wp_verify_nonce( $nonce, 'pw_livesearch_nonce' )) {
        $arr = array('success'=>'no-nonce','products' => array());
        print_r($arr); // ❌
        die();
    }
    
    $data='';
    switch ($my_array_of_vars['target_type']){ // ❌
        case 'simple_products':
            $data=$pw_rpt_main_class->pw_get_product_woo_data_chosen('simple',false,$my_array_of_vars['q']); // ❌
        break;
    }
    
    echo json_encode($data); // ❌
    die(0);
}
```

### Refactored Code:
```php
add_action('wp_ajax_pw_chosen_ajax', 'pw_chosen_ajax'); // ✅ Only authenticated

function pw_chosen_ajax() {
    // Verify nonce
    if ( ! PW_Report_AWR_Helpers::verify_nonce( 'nonce', 'pw_livesearch_nonce' ) ) {
        PW_Report_AWR_Helpers::json_error( 'Invalid nonce', 403 );
    }
    
    // Check capabilities
    if ( ! PW_Report_AWR_Helpers::current_user_can( 'manage_woocommerce' ) ) {
        PW_Report_AWR_Helpers::json_error( 'Insufficient permissions', 403 );
    }
    
    // Sanitize input
    $postdata = sanitize_text_field( wp_unslash( $_REQUEST['postdata'] ?? '' ) );
    parse_str( $postdata, $my_array_of_vars );
    
    $target_type = sanitize_text_field( $my_array_of_vars['target_type'] ?? '' );
    $search_query = sanitize_text_field( $my_array_of_vars['q'] ?? '' );
    
    // Validate target type
    $allowed_types = array( 'simple_products', 'variable_products', 'all_products', 'customer' );
    if ( ! in_array( $target_type, $allowed_types, true ) ) {
        PW_Report_AWR_Helpers::json_error( 'Invalid target type', 400 );
    }
    
    global $pw_rpt_main_class;
    $data = array();
    
    switch ( $target_type ) {
        case 'simple_products':
            $data = $pw_rpt_main_class->pw_get_product_woo_data_chosen( 'simple', false, $search_query );
            break;
        case 'variable_products':
            $data = $pw_rpt_main_class->pw_get_product_woo_data_chosen( '1', false, $search_query );
            break;
        case 'all_products':
            $data = $pw_rpt_main_class->pw_get_product_woo_data_chosen( '0', false, $search_query );
            break;
        case 'customer':
            $data = $pw_rpt_main_class->pw_get_woo_customers_orders( 'shop_order', 'no', $search_query );
            break;
    }
    
    PW_Report_AWR_Helpers::json_success( $data );
}
```

---

## Testing Checklist

### Functional Tests
- [ ] Product search with valid nonce works
- [ ] Customer search with valid nonce works
- [ ] Report data fetching works
- [ ] Favorites add/remove works

### Security Tests
- [x] Requests without nonce are rejected
- [ ] Requests without authentication are rejected
- [ ] Requests without capabilities are rejected
- [ ] SQL injection attempts are blocked
- [ ] Invalid table names are rejected
- [ ] XSS attempts in notes are sanitized

### Performance Tests
- [ ] Search queries are reasonably fast (<2s)
- [ ] Report fetching doesn't timeout
- [ ] No N+1 query issues

---

## Implementation Plan

### Phase 1: Critical Security Fixes (1-2 hours)
1. Remove all `nopriv` hooks
2. Add capability checks to all AJAX handlers
3. Sanitize all `$_REQUEST` input
4. Replace `print_r()` / `echo json_encode()` with `wp_send_json_*`

### Phase 2: Input Validation (2-3 hours)
1. Create whitelist for table names
2. Create whitelist for target types
3. Validate all numeric inputs with `absint()`
4. Sanitize search queries

### Phase 3: Refactoring (3-4 hours)
1. Move to helper functions
2. Consolidate duplicate code
3. Add proper error messages
4. Add logging for security events

### Phase 4: Testing (2-3 hours)
1. Manual testing with curl
2. Automated tests with valid nonces
3. Security penetration testing
4. Performance profiling

**Total Estimated Time**: 8-12 hours

---

## Files to Modify

1. `includes/actions.php` - Main AJAX handlers (PRIMARY)
2. `includes/class-awr-helpers.php` - Add AJAX helper methods (DONE)
3. `includes/Abandoned/woocommerce-cart-reports.php` - Abandoned cart AJAX
4. All `fetch_data_*.php` files - Backend data fetchers

---

**Report Generated**: 2025-10-30
**Severity**: HIGH
**Priority**: URGENT

