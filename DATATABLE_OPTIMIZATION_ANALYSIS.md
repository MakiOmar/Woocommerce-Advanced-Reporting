# Datatable Generator Optimization Analysis

**File**: `includes/datatable_generator.php`  
**Lines**: ~9,299 lines  
**Class**: `pw_rpt_datatable_generate`

---

## Current Architecture

### Structure:
```
pw_rpt_datatable_generate extends from nothing
    ├── fetch_sql($table_name, $search_fields)
    │   └── switch($table_name)
    │       ├── case 'product': require("fetch_data_product.php");
    │       ├── case 'category': require("fetch_data_category.php");
    │       └── ... 60+ more cases
    │
    ├── table_html($table_name, $search_fields)
    ├── search_form_html($table_name)
    └── 100+ other methods
```

### Identified Patterns:

#### 1. **Switch Statement Anti-Pattern**
- 60+ case statements in `fetch_sql()`
- Each case requires a separate file
- No default case handling
- Maintainability nightmare

#### 2. **File Inclusion Pattern**
All `fetch_data_*.php` files follow similar structure:
```php
<?php
if($file_used=="sql_table") {
    // Get parameters (all using similar pattern)
    $pw_from_date = $this->pw_get_woo_requests('pw_from_date', NULL, true);
    $pw_to_date = $this->pw_get_woo_requests('pw_to_date', NULL, true);
    $pw_order_status = $this->pw_get_woo_requests('pw_orders_status', '-1', true);
    
    // Build SQL (different for each)
    $sql_columns = "...";
    $sql_joins = "...";
    $sql_conditions = "...";
    
    // Final SQL assembly
    $sql = "SELECT $sql_columns FROM $sql_joins WHERE $sql_conditions";
    
    // Results
    $results = $wpdb->get_results($sql);
    $this->results = $results;
}
```

#### 3. **Common Request Parameters**
Across all `fetch_data_*.php` files:
- `pw_from_date` / `pw_to_date` (90% of files)
- `pw_order_status` / `pw_orders_status` (80% of files)
- `pw_product_id` (60% of files)
- `pw_category_id` (50% of files)
- `pw_hide_os` (40% of files)

---

## Issues Identified

### Critical Issues:

#### 1. **Massive Switch Statement** (Lines 35-300+)
- 60+ case statements
- No abstraction
- Difficult to maintain
- No validation

#### 2. **Code Duplication**
- Parameter extraction repeated in every file
- Similar SQL building patterns
- Duplicate conditional logic

#### 3. **No Caching**
- Expensive queries run on every request
- No transient usage
- No query result caching

#### 4. **Performance**
- File: ~9,299 lines (extremely large)
- 100+ methods in single class
- No lazy loading
- No query optimization

---

## Optimization Opportunities

### High Impact Optimizations:

#### 1. **Replace Switch with Factory Pattern**
```php
// BEFORE:
switch($table_name){
    case 'product': require("fetch_data_product.php"); break;
    case 'category': require("fetch_data_category.php"); break;
    // ... 60+ more cases
}

// AFTER:
$fetch_file = __PW_REPORT_WCREPORT_ROOT_DIR__ . '/includes/fetch_data_' . $table_name . '.php';
if ( file_exists( $fetch_file ) ) {
    require_once( $fetch_file );
} else {
    // Fallback or error
}
```

#### 2. **Create Base Query Builder Class**
```php
abstract class PW_Report_Base_Query {
    protected $wpdb;
    protected $from_date;
    protected $to_date;
    protected $order_status;
    
    abstract protected function get_columns();
    abstract protected function get_joins();
    abstract protected function get_conditions();
    
    public function build_query() {
        return "SELECT {$this->get_columns()} 
                FROM {$this->get_joins()} 
                WHERE {$this->get_conditions()}";
    }
}

class PW_Report_Product_Query extends PW_Report_Base_Query {
    protected function get_columns() {
        return "product_id, product_name, SUM(qty) as total_qty";
    }
    // ... implement other abstract methods
}
```

#### 3. **Add Query Caching**
```php
public function fetch_sql_cached($table_name, $search_fields = NULL) {
    $cache_key = 'awr_' . $table_name . '_' . md5(serialize($search_fields));
    $cached = get_transient($cache_key);
    
    if (false !== $cached) {
        return $cached;
    }
    
    $results = $this->fetch_sql($table_name, $search_fields);
    set_transient($cache_key, $results, HOUR_IN_SECONDS);
    
    return $results;
}
```

#### 4. **Extract Common Parameter Handling**
```php
class PW_Report_Request_Handler {
    public static function get_date_range() {
        return array(
            'from' => $this->pw_get_woo_requests('pw_from_date', NULL, true),
            'to' => $this->pw_get_woo_requests('pw_to_date', NULL, true),
        );
    }
    
    public static function get_filters() {
        return array(
            'product_id' => $this->pw_get_woo_requests('pw_product_id', '-1', true),
            'category_id' => $this->pw_get_woo_requests('pw_category_id', '-1', true),
            'order_status' => $this->pw_get_woo_requests('pw_orders_status', '-1', true),
        );
    }
}
```

---

## Recommended Refactoring Strategy

### Phase 1: Immediate Wins (2-4 hours)
1. ✅ **Replace switch with dynamic file loading**
2. ✅ **Extract common parameters to helper methods**
3. ✅ **Add validation for $table_name**
4. ✅ **Add file_exists check**

### Phase 2: Performance (4-6 hours)
1. ⏳ **Add query result caching (transients)**
2. ⏳ **Optimize slow queries**
3. ⏳ **Add database indexes**
4. ⏳ **Implement pagination**

### Phase 3: Architecture (8-12 hours)
1. ⏳ **Create base query builder class**
2. ⏳ **Convert fetch_data files to classes**
3. ⏳ **Implement strategy pattern**
4. ⏳ **Add dependency injection**

---

## Quick Wins Implementation

### 1. Replace Switch Statement
**Impact**: Reduce ~200 lines, easier maintenance

```php
// In fetch_sql() method:
public function fetch_sql($table_name, $search_fields = NULL) {
    global $wpdb;
    $file_used = "sql_table";
    
    // Sanitize table name
    $table_name = sanitize_file_name( $table_name );
    
    // Build file path
    $fetch_file = __PW_REPORT_WCREPORT_ROOT_DIR__ . '/includes/fetch_data_' . $table_name . '.php';
    
    // Validate file exists
    if ( ! file_exists( $fetch_file ) ) {
        error_log( 'AWR: fetch_data file not found: ' . $table_name );
        return false;
    }
    
    // Include file
    require( $fetch_file );
    
    return $this->results;
}
```

### 2. Add Common Parameter Extraction Method
**Impact**: Reduce duplication in all fetch_data files

```php
protected function get_common_filters() {
    return array(
        'from_date' => $this->pw_get_woo_requests('pw_from_date', NULL, true),
        'to_date' => $this->pw_get_woo_requests('pw_to_date', NULL, true),
        'order_status' => $this->pw_get_woo_requests('pw_orders_status', '-1', true),
        'product_id' => $this->pw_get_woo_requests('pw_product_id', '-1', true),
        'category_id' => $this->pw_get_woo_requests('pw_category_id', '-1', true),
        'hide_os' => $this->pw_get_woo_requests('pw_hide_os', '-1', true),
    );
}
```

---

## Performance Metrics

### Current Performance (Estimated):
- **Load Time**: 2-5 seconds per report
- **Query Count**: 5-20 queries per report
- **Memory Usage**: 64-128 MB
- **File Size**: 9,299 lines

### Target Performance (After Optimization):
- **Load Time**: <1 second (with caching)
- **Query Count**: 1-5 queries (optimized)
- **Memory Usage**: 32-64 MB
- **File Size**: Split into multiple organized files

---

## Files Analysis

### Duplicate Code Across fetch_data Files:

#### Parameter Extraction (~20 lines each):
```php
$pw_from_date = $this->pw_get_woo_requests('pw_from_date',NULL,true);
$pw_to_date = $this->pw_get_woo_requests('pw_to_date',NULL,true);
$pw_order_status = $this->pw_get_woo_requests('pw_orders_status','-1',true);
// ... repeated in 80+ files
```

#### Date Condition Building (~10 lines each):
```php
if($pw_from_date != NULL && $pw_to_date !=NULL){
    $pw_from_date_condition=" AND DATE(pw_posts.post_date) BETWEEN '".$pw_from_date."' AND '".$pw_to_date."'";
}
// ... repeated in 80+ files
```

#### Order Status Handling (~15 lines each):
```php
if($pw_id_order_status  && $pw_id_order_status != -1 && $pw_id_order_status != '-1' && $pw_id_order_status != "'-1'"){
    // ... complex logic repeated
}
// ... repeated in 70+ files
```

**Total Duplicate Code Estimate**: ~3,500 lines across all fetch_data files

---

## Implementation Priority

### Must Do (This Week):
1. ✅ Replace switch with dynamic file loading
2. ✅ Add file existence validation
3. ✅ Extract common parameter methods

### Should Do (Next Week):
1. ⏳ Add query caching
2. ⏳ Optimize slow queries
3. ⏳ Add error logging

### Could Do (Next Month):
1. ⏳ Refactor to class-based architecture
2. ⏳ Add unit tests
3. ⏳ Performance profiling

---

## Risk Assessment

### Refactoring Risks:
- **Low Risk**: Switch statement replacement (well-contained)
- **Low Risk**: Parameter extraction (doesn't change logic)
- **Medium Risk**: Query caching (cache invalidation complexity)
- **High Risk**: Architecture change (major refactor, extensive testing needed)

### Mitigation:
1. Test each change independently
2. Maintain backward compatibility
3. Add fallbacks for missing files
4. Monitor error logs
5. A/B test performance

---

**Next Action**: Implement Quick Wins (Switch Statement Replacement)

