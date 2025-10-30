<?php
if ($file_used == "sql_table") {
    $request = array();
    $start   = 0;

    $pw_from_date = $this->pw_get_woo_requests('pw_from_date', null, true);
    $pw_to_date   = $this->pw_get_woo_requests('pw_to_date', null, true);
    $date_format  = $this->pw_date_format($pw_from_date);

    //CUSTOM WORK - 11899
    $pw_from_date_delivery = $this->pw_get_woo_requests('pw_from_date_delivery', null, true);
    $pw_to_date_delivery   = $this->pw_get_woo_requests('pw_to_date_delivery', null, true);

    $pw_id_order_status = $this->pw_get_woo_requests('pw_id_order_status', null, true);
    $pw_paid_customer   = $this->pw_get_woo_requests('pw_customers_paid', null, true);
    $txtProduct         = $this->pw_get_woo_requests('txtProduct', null, true);
    $pw_product_id      = $this->pw_get_woo_requests('pw_product_id', "-1", true);
    $category_id        = $this->pw_get_woo_requests('pw_category_id', '-1', true);

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    $brand_id = $this->pw_get_woo_requests('pw_brand_id', '-1', true);

    $limit = $this->pw_get_woo_requests('limit', 15, true);
    $p     = $this->pw_get_woo_requests('p', 1, true);

    $page     = $this->pw_get_woo_requests('page', null, true);
    $order_id = $this->pw_get_woo_requests('pw_id_order', null, true);
    if ($order_id) {
        $order_id = "'" . str_replace(",", "','", $order_id) . "'";
    }

    $pw_from_date = $this->pw_get_woo_requests('pw_from_date', null, true);
    $pw_to_date   = $this->pw_get_woo_requests('pw_to_date', null, true);

    $pw_txt_email = $this->pw_get_woo_requests('pw_email_text', null, true);

    $pw_txt_first_name = $this->pw_get_woo_requests('pw_first_name_text', null, true);

    $pw_detail_view     = $this->pw_get_woo_requests('pw_view_details', "no", true);
    $pw_country_code    = $this->pw_get_woo_requests('pw_countries_code', null, true);
    $state_code         = $this->pw_get_woo_requests('pw_states_code', '-1', true);
    $pw_payment_method  = $this->pw_get_woo_requests('payment_method', null, true);
    $pw_order_item_name = $this->pw_get_woo_requests('order_item_name', null, true);//for coupon
    $pw_coupon_code     = $this->pw_get_woo_requests('coupon_code', null, true);//for coupon

    $pw_publish_order  = $this->pw_get_woo_requests('publish_order', 'no',
        true);//if publish display publish order only, no or null display all order
    $pw_coupon_used    = $this->pw_get_woo_requests('pw_use_coupon', 'no', true);
    $pw_order_meta_key = $this->pw_get_woo_requests('order_meta_key', '-1', true);
    $pw_order_status   = $this->pw_get_woo_requests('pw_orders_status', '-1', true);
    //$pw_order_status  		= "'".str_replace(",","','",$pw_order_status)."'";

    $pw_paid_customer = str_replace(",", "','", $pw_paid_customer);

    $pw_coupon_code  = $this->pw_get_woo_requests('coupon_code', '-1', true);
    $pw_coupon_codes = $this->pw_get_woo_requests('pw_codes_of_coupon', '-1', true);

    $pw_coupon_code  = '';
    $pw_coupon_codes = '';
    ////////////////////CUSTOM WORK/////////////////////
    $pw_coupon_code  = $this->pw_get_woo_requests('coupon_code', '-1', true);
    $pw_coupon_codes = $this->pw_get_woo_requests('pw_codes_of_coupon', '-1', true);
    if ($pw_coupon_codes != "-1") {
        $pw_coupon_codes = "'" . str_replace(",", "','", $pw_coupon_codes) . "'";
    }

    $coupon_discount_types = $this->pw_get_woo_requests('pw_coupon_discount_types', '-1', true);
    if ($coupon_discount_types != "-1") {
        $coupon_discount_types = "'" . str_replace(",", "','", $coupon_discount_types) . "'";
    }


    $pw_max_amount = $this->pw_get_woo_requests('max_amount', '-1', true);
    $pw_min_amount = $this->pw_get_woo_requests('min_amount', '-1', true);

    $pw_billing_post_code = $this->pw_get_woo_requests('pw_bill_post_code', '-1', true);

    ////ADDED IN V4.0
    $pw_variation_id   = $this->pw_get_woo_requests('pw_variation_id', '-1', true);
    $pw_variation_only = $this->pw_get_woo_requests('pw_variation_only', '-1', true);
    $pw_variations     = $pw_item_meta_key = '';
    if ($pw_variation_id != '-1' and strlen($pw_variation_id) > 0) {

        $pw_variations = explode(",", $pw_variation_id);
        $var      = array();
        $item_att = array();
        foreach ($pw_variations as $key => $value):
            $var[]      .= "attribute_pa_" . $value;
            $var[]      .= "attribute_" . $value;
            $item_att[] .= "pa_" . $value;
            $item_att[] .= $value;
        endforeach;
        $pw_variations    = implode("', '", $var);
        $pw_item_meta_key = implode("', '", $item_att);
    }
    $pw_variation_attributes    = $pw_variations;
    $pw_variation_item_meta_key = $pw_item_meta_key;

    $pw_hide_os = $this->pw_get_woo_requests('pw_hide_os', '"trash"', true);

    $pw_show_cog = $this->pw_get_woo_requests('pw_show_cog', 'no', true);

    ///////////HIDDEN FIELDS////////////
    $pw_hide_os         = $this->otder_status_hide;
    $pw_publish_order   = 'no';
    $pw_order_item_name = '';

    $pw_payment_method = '';

    $pw_order_meta_key = '';

    $data_format = $this->pw_get_woo_requests('date_format', get_option('date_format'), true);

    $amont_zero = '';
    //////////////////////

    /////////////////////////
    //APPLY PERMISSION TERMS
    $key = 'all_orders';

    $category_id = $this->pw_get_form_element_permission('pw_category_id', $category_id, $key);

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    $brand_id = $this->pw_get_form_element_permission('pw_brand_id', $brand_id, $key);

    $pw_product_id = $this->pw_get_form_element_permission('pw_product_id', $pw_product_id, $key);

    $pw_country_code = $this->pw_get_form_element_permission('pw_countries_code', $pw_country_code, $key);

    if ($pw_country_code != null && $pw_country_code != '-1') {
        $pw_country_code = "'" . str_replace(",", "','", $pw_country_code) . "'";
    }

    $state_code = $this->pw_get_form_element_permission('pw_states_code', $state_code, $key);

    if ($state_code != null && $state_code != '-1') {
        $state_code = "'" . str_replace(",", "','", $state_code) . "'";
    }

    $pw_order_status = $this->pw_get_form_element_permission('pw_orders_status', $pw_order_status, $key);

    if ($pw_order_status != null && $pw_order_status != '-1') {
        $pw_order_status = "'" . str_replace(",", "','", $pw_order_status) . "'";
    }
    ///////////////////////////

    $pw_variations_formated = '';

    if (strlen($pw_max_amount) <= 0) {
        $_REQUEST['max_amount'] = $pw_max_amount = '-1';
    }
    if (strlen($pw_min_amount) <= 0) {
        $_REQUEST['min_amount'] = $pw_min_amount = '-1';
    }

    if ($pw_max_amount != '-1' || $pw_min_amount != '-1') {
        if ($pw_order_meta_key == '-1') {
            $_REQUEST['order_meta_key'] = "_order_total";
        }
    }

    $last_days_orders = "0";
    if (is_array($pw_id_order_status)) {
        $pw_id_order_status = implode(",", $pw_id_order_status);
    }
    if (is_array($category_id)) {
        $category_id = implode(",", $category_id);
    }

    /////ADDED IN VER4.0
    //BRANDS ADDONS
    if (is_array($brand_id)) {
        $brand_id = implode(",", $brand_id);
    }

    if ( ! $pw_from_date) {
        $pw_from_date = date_i18n('Y-m-d');
    }
    if ( ! $pw_to_date) {
        $last_days_orders = apply_filters($page . '_back_day', $last_days_orders);//-1,-2,-3,-4,-5
        $pw_to_date       = date('Y-m-d', strtotime($last_days_orders . ' day', strtotime(date_i18n("Y-m-d"))));
    }

    $pw_sort_by  = $this->pw_get_woo_requests('sort_by', 'order_id', true);
    $pw_order_by = $this->pw_get_woo_requests('order_by', 'DESC', true);
    ///

    if ($p > 1) {
        $start = ($p - 1) * $limit;
    }

    if ($pw_detail_view == "yes") {
        $pw_variations_value    = $this->pw_get_woo_requests('variations_value', "-1", true);
        $pw_variations_formated = '-1';
        if ($pw_variations_value != "-1" and strlen($pw_variations_value) > 0) {
            $pw_variations_value = explode(",", $pw_variations_value);
            $var                 = array();
            foreach ($pw_variations_value as $key => $value):
                $var[] .= $value;
            endforeach;
            $result = array_unique($var);
            //$this->print_array($var);
            $pw_variations_formated = implode("', '", $result);
        }
        $_REQUEST['variations_formated'] = $pw_variations_formated;
    }


    //pw_first_name_text
    $pw_txt_first_name_cols        = '';
    $pw_txt_first_name_join        = '';
    $pw_txt_first_name_condition_1 = '';
    $pw_txt_first_name_condition_2 = '';

    //pw_email_text
    $pw_txt_email_cols        = '';
    $pw_txt_email_join        = '';
    $pw_txt_email_condition_1 = '';
    $pw_txt_email_condition_2 = '';

    //SORT BY
    $pw_sort_by_cols = '';

    //CATEGORY
    $category_id_join      = '';
    $category_id_condition = '';

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    $brand_id_join      = '';
    $brand_id_condition = '';

    //ORDER ID
    $pw_id_order_status_join      = '';
    $pw_id_order_status_condition = '';

    //COUNTRY
    $pw_country_code_join        = '';
    $pw_country_code_condition_1 = '';
    $pw_country_code_condition_2 = '';

    //STATE
    $state_code_join        = '';
    $state_code_condition_1 = '';
    $state_code_condition_2 = '';

    //PAYMENT METHOD
    $pw_payment_method_join        = '';
    $pw_payment_method_condition_1 = '';
    $pw_payment_method_condition_2 = '';

    //POSTCODE
    $pw_billing_post_code_join      = '';
    $pw_billing_post_code_condition = '';

    //COUPON USED
    $pw_coupon_used_join      = '';
    $pw_coupon_used_condition = '';

    //VARIATION ID
    $pw_variation_id_join      = '';
    $pw_variation_id_condition = '';

    ////ADDED IN V4.0
    //VARIATION
    $pw_variation_item_meta_key_join      = '';
    $sql_variation_join                   = '';
    $pw_show_variation_join               = '';
    $pw_variation_item_meta_key_condition = '';
    $sql_variation_condition              = '';

    //VARIATION ONLY
    $pw_variation_only_join      = '';
    $pw_variation_only_condition = '';

    //VARIATION FORMAT
    $pw_variations_formated_join      = '';
    $pw_variations_formated_condition = '';

    //ORDER META KEY
    $pw_order_meta_key_join      = '';
    $pw_order_meta_key_condition = '';

    //COUPON CODES
    $pw_coupon_codes_join      = '';
    $pw_coupon_codes_condition = '';

    //COUPON CODE
    $pw_coupon_code_condition = '';

    //DATA CONDITION
    $date_condition = '';

    //CUSTOM WORK - 11899
    //DELIVERY DATA CONDITION
    $date_delivery_condition = '';
    $delivery_date_join      = '';

    //ORDER ID
    $order_id_condition = '';

    //PAID CUSTOMER
    $pw_paid_customer_condition = '';

    //PUBLISH ORDER
    $pw_publish_order_condition_1 = '';
    $pw_publish_order_condition_2 = '';

    //ORDER ITEM NAME
    $pw_order_item_name_condition = '';

    //txt PRODUCT
    $txtProduct_condition = '';

    //PRODUCT ID
    $pw_product_id_condition = '';

    //CATEGORY ID
    $category_id_condition = '';

    //ORDER STATUS ID
    $pw_id_order_status_condition = '';

    //ORDER STATUS
    $pw_order_status_condition = '';

    //HIDE ORDER STATUS
    $pw_hide_os_condition = '';

    ////ADDED IN VER4.0
    /// COST OF GOOD
    $pw_show_cog_cols      = '';
    $pw_show_cog_join      = '';
    $pw_show_cog_condition = '';


    if (($pw_txt_first_name and $pw_txt_first_name != '-1') || $pw_sort_by == "billing_name") {
        $pw_txt_first_name_cols = " CONCAT(pw_postmeta1.meta_value, ' ', pw_postmeta2.meta_value) AS billing_name,";
    }
    if ($pw_txt_email || ($pw_paid_customer && $pw_paid_customer != '-1' and $pw_paid_customer != "'-1'") || $pw_sort_by == "billing_email") {
        $pw_txt_email_cols = " postmeta.meta_value AS billing_email,";
    }

    if ($pw_sort_by == "status") {
        $pw_sort_by_cols = " terms2.name as status, ";
    }
    $sql_columns = " $pw_txt_first_name_cols $pw_txt_email_cols $pw_sort_by_cols";
    $sql_columns .= "
		IF ( (woocommerce_order_itemmeta.meta_key = '_fee_amount'), 1, 0) AS fee,

        DATE_FORMAT(pw_posts.post_date,'%m/%d/%Y') 													AS order_date,
		pw_woocommerce_order_items.order_id 															AS order_id,
		pw_woocommerce_order_items.order_item_name 													AS product_name,
		pw_woocommerce_order_items.order_item_id														AS order_item_id,
		woocommerce_order_itemmeta.meta_value 														AS woocommerce_order_itemmeta_meta_value,
		(pw_woocommerce_order_itemmeta2.meta_value/pw_woocommerce_order_itemmeta3.meta_value) 			AS sold_rate,
		IF ( (woocommerce_order_itemmeta.meta_key = '_fee_amount'), woocommerce_order_itemmeta.meta_value , (pw_woocommerce_order_itemmeta4.meta_value/pw_woocommerce_order_itemmeta3.meta_value))AS product_rate,

		IF ( (woocommerce_order_itemmeta.meta_key = '_fee_amount'), woocommerce_order_itemmeta.meta_value , (pw_woocommerce_order_itemmeta4.meta_value))AS item_amount,
		(pw_woocommerce_order_itemmeta2.meta_value) 													AS item_net_amount,
		(pw_woocommerce_order_itemmeta4.meta_value - pw_woocommerce_order_itemmeta2.meta_value) 			AS item_discount,
		pw_woocommerce_order_itemmeta2.meta_value 														AS total_price,
		count(pw_woocommerce_order_items.order_item_id) 												AS product_quentity,
		woocommerce_order_itemmeta.meta_value 														AS product_id,
		woocommerce_order_itemmeta_var.meta_value 														AS variation_id

		,pw_woocommerce_order_itemmeta3.meta_value 													AS 'product_quantity'
		,pw_posts.post_status 																			AS post_status
		,pw_posts.post_status 																			AS order_status

		";

    ////ADDED IN V4.0
    if (($pw_variation_item_meta_key != "-1" and strlen($pw_variation_item_meta_key) > 1)) {
        $sql_columns .= " , pw_woocommerce_order_itemmeta_variation.meta_key AS variation_key";
        $sql_columns .= " , pw_woocommerce_order_itemmeta_variation.meta_value AS variation_value";
    }

    ////ADDED IN VER4.0
    /// COST OF GOOD
    if ($pw_show_cog == "yes") {
        $sql_columns .= " ,woo_itemmeta_cog.meta_value as cog ";
    }


    $sql_joins = "{$wpdb->prefix}woocommerce_order_items as pw_woocommerce_order_items

		LEFT JOIN  {$wpdb->prefix}posts as pw_posts ON pw_posts.ID=pw_woocommerce_order_items.order_id

		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as woocommerce_order_itemmeta 	ON woocommerce_order_itemmeta.order_item_id		=	pw_woocommerce_order_items.order_item_id

    LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as woocommerce_order_itemmeta_var 	ON woocommerce_order_itemmeta_var.order_item_id		=	pw_woocommerce_order_items.order_item_id";

    ////ADDED IN V4.0
    if (($pw_variation_item_meta_key != "-1" and strlen($pw_variation_item_meta_key) > 1)) {
        $pw_variation_item_meta_key_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as pw_woocommerce_order_itemmeta_variation ON pw_woocommerce_order_itemmeta_variation.order_item_id= pw_woocommerce_order_items.order_item_id";
    }


    $sql_joins .= "
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as pw_woocommerce_order_itemmeta2 	ON pw_woocommerce_order_itemmeta2.order_item_id	=	pw_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as pw_woocommerce_order_itemmeta3 	ON pw_woocommerce_order_itemmeta3.order_item_id	=	pw_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as pw_woocommerce_order_itemmeta4 	ON pw_woocommerce_order_itemmeta4.order_item_id	=	pw_woocommerce_order_items.order_item_id AND pw_woocommerce_order_itemmeta4.meta_key='_line_subtotal'

        ";


    if ($category_id && $category_id != "-1") {
        $category_id_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as pw_term_relationships 			ON pw_term_relationships.object_id		=	woocommerce_order_itemmeta.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 				ON term_taxonomy.term_taxonomy_id	=	pw_term_relationships.term_taxonomy_id";
    }

    /////ADDED IN VER4.0
    //BRANDS ADDONS
    if ($brand_id && $brand_id != "-1") {
        $brand_id_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as pw_term_relationships_brand 			ON pw_term_relationships_brand.object_id		=	woocommerce_order_itemmeta.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_brand				ON term_taxonomy_brand.term_taxonomy_id	=	pw_term_relationships_brand.term_taxonomy_id";
    }

    if (($pw_id_order_status && $pw_id_order_status != '-1') || $pw_sort_by == "status") {
        $pw_id_order_status_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as pw_term_relationships2			ON pw_term_relationships2.object_id	= pw_woocommerce_order_items.order_id
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as pw_term_taxonomy2				ON pw_term_taxonomy2.term_taxonomy_id	= pw_term_relationships2.term_taxonomy_id";
        if ($pw_sort_by == "status") {
            $pw_id_order_status_join .= " LEFT JOIN  {$wpdb->prefix}terms 	as terms2 						ON terms2.term_id					=	pw_term_taxonomy2.term_id";
        }
    }

    if ($pw_txt_email || ($pw_paid_customer && $pw_paid_customer != '-1' and $pw_paid_customer != "'-1'") || $pw_sort_by == "billing_email") {
        $pw_txt_email_join = "
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id=pw_woocommerce_order_items.order_id";
    }
    if (($pw_txt_first_name and $pw_txt_first_name != '-1') || $pw_sort_by == "billing_name") {
        $pw_txt_first_name_join = " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta1 ON pw_postmeta1.post_id=pw_woocommerce_order_items.order_id
			LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta2 ON pw_postmeta2.post_id=pw_woocommerce_order_items.order_id";
    }

    if ($pw_country_code and $pw_country_code != '-1') {
        $pw_country_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta4 ON pw_postmeta4.post_id=pw_woocommerce_order_items.order_id";
    }

    if ($state_code && $state_code != '-1') {
        $state_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta_billing_state ON pw_postmeta_billing_state.post_id=pw_posts.ID";
    }

    if ($pw_payment_method) {
        $pw_payment_method_join = " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta5 ON pw_postmeta5.post_id=pw_woocommerce_order_items.order_id";
    }

    if ($pw_billing_post_code and $pw_billing_post_code != '-1') {
        $pw_billing_post_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta_billing_postcode ON pw_postmeta_billing_postcode.post_id	=	pw_posts.ID";
    }

    if ($pw_coupon_used == "yes") {
        $pw_coupon_used_join = " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta6 ON pw_postmeta6.post_id=pw_woocommerce_order_items.order_id";
    }

    if ($pw_coupon_used == "yes") {
        $pw_coupon_used_join .= " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta7 ON pw_postmeta7.post_id=pw_posts.ID";
    }

    if ($pw_variation_only && $pw_variation_only != "-1" && $pw_variation_only == "yes") {
        $pw_variation_only_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as pw_woocommerce_order_itemmeta_variation_o			ON pw_woocommerce_order_itemmeta_variation_o.order_item_id 		= 	pw_woocommerce_order_items.order_item_id";
    }

    if ($pw_variations_formated != "-1" and $pw_variations_formated != null) {
        $pw_variations_formated_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as pw_woocommerce_order_itemmeta8 ON pw_woocommerce_order_itemmeta8.order_item_id = pw_woocommerce_order_items.order_item_id";
        $pw_variations_formated_join .= " LEFT JOIN  {$wpdb->prefix}postmeta as pw_postmeta_variation ON pw_postmeta_variation.post_id = pw_woocommerce_order_itemmeta8.meta_value";
    }

    if ($pw_order_meta_key and $pw_order_meta_key != '-1') {
        $pw_order_meta_key_join = " LEFT JOIN  {$wpdb->prefix}postmeta as pw_order_meta_key ON pw_order_meta_key.post_id=pw_posts.ID";
    }

    if (($pw_coupon_codes != '' && $pw_coupon_codes != "-1") or ($pw_coupon_code && $pw_coupon_code != "-1")) {
        $pw_coupon_codes_join = " LEFT JOIN {$wpdb->prefix}woocommerce_order_items as pw_woocommerce_order_coupon_item ON pw_woocommerce_order_coupon_item.order_id = pw_posts.ID AND pw_woocommerce_order_coupon_item.order_item_type = 'coupon'";
    }


    ////ADDED IN VER4.0
    /// COST OF GOOD
    if ($pw_show_cog == "yes") {
        $pw_show_cog_join = " LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woo_itemmeta_cog ON woocommerce_order_itemmeta.order_item_id	=	woo_itemmeta_cog.order_item_id  ";
    }


    //CUSTOM WORK - 11899
    if ($pw_from_date_delivery != null && $pw_to_date_delivery != null) {
        $delivery_date_join      = " LEFT JOIN {$wpdb->prefix}postmeta as pw_delivery ON pw_delivery.post_id=pw_posts.ID ";
        $date_delivery_condition .= " AND pw_delivery.meta_key='_delivery_date' ";
    }

    $post_type_condition = "pw_posts.post_type = 'shop_order' AND billing_country.meta_key	= '_billing_country' ";
    $post_type_condition = "";


    if ($pw_txt_email || ($pw_paid_customer && $pw_paid_customer != '-1' and $pw_paid_customer != "'-1'") || $pw_sort_by == "billing_email") {
        $pw_txt_email_condition_1 = "
				AND postmeta.meta_key='_billing_email'";
    }

    if (($pw_txt_first_name and $pw_txt_first_name != '-1') || $pw_sort_by == "billing_name") {
        $pw_txt_first_name_condition_1 = "
				AND pw_postmeta1.meta_key='_billing_first_name'
				AND pw_postmeta2.meta_key='_billing_last_name'";
    }

    $other_condition_1 = "
		((woocommerce_order_itemmeta.meta_key = '_product_id'  AND pw_woocommerce_order_itemmeta3.meta_key='_qty') OR (woocommerce_order_itemmeta.meta_key = '_fee_amount'))


		AND woocommerce_order_itemmeta_var.meta_key = '_variation_id'

		AND pw_woocommerce_order_itemmeta2.meta_key='_line_total'
		";

    if ($pw_country_code and $pw_country_code != '-1') {
        $pw_country_code_condition_1 = " AND pw_postmeta4.meta_key='_billing_country'";
    }

    if ($state_code && $state_code != '-1') {
        $state_code_condition_1 = " AND pw_postmeta_billing_state.meta_key='_billing_state'";
    }

    if ($pw_billing_post_code and $pw_billing_post_code != '-1') {
        $pw_billing_post_code_condition = " AND pw_postmeta_billing_postcode.meta_key='_billing_postcode' AND pw_postmeta_billing_postcode.meta_value LIKE '%{$pw_billing_post_code}%' ";
    }

    if ($pw_payment_method) {
        $pw_payment_method_condition_1 = " AND pw_postmeta5.meta_key='_payment_method_title'";
    }

    if ($pw_from_date != null && $pw_to_date != null) {

        $date_condition = " AND DATE(pw_posts.post_date) BETWEEN STR_TO_DATE('" . $pw_from_date . "', '$date_format') and STR_TO_DATE('" . $pw_to_date . "', '$date_format')";

    }


    //CUSTOM WORK - 11899
    if ($pw_from_date_delivery != null && $pw_to_date_delivery != null) {
        $date_delivery_condition = " AND pw_delivery.meta_value BETWEEN '" . $pw_from_date_delivery . "' AND '" . $pw_to_date_delivery . "'";
    }

    if ($order_id) {
        $order_id_condition = " AND pw_woocommerce_order_items.order_id IN (" . $order_id . ") ";
    }

    if ($pw_txt_email) {
        $pw_txt_email_condition_2 = " AND postmeta.meta_value LIKE '%" . $pw_txt_email . "%'";
    }

    if ($pw_paid_customer && $pw_paid_customer != '-1' and $pw_paid_customer != "'-1'") {
        $pw_paid_customer_condition = " AND postmeta.meta_value IN ('" . $pw_paid_customer . "')";
    }

    if ($pw_txt_first_name and $pw_txt_first_name != '-1') {
        $pw_txt_first_name_condition_2 = " AND (lower(concat_ws(' ', pw_postmeta1.meta_value, pw_postmeta2.meta_value)) like lower('%" . $pw_txt_first_name . "%') OR lower(concat_ws(' ', pw_postmeta2.meta_value, pw_postmeta1.meta_value)) like lower('%" . $pw_txt_first_name . "%'))";
    }

    if ($pw_publish_order == 'yes') {
        $pw_publish_order_condition_1 = " AND pw_posts.post_status = 'publish'";
    }

    if ($pw_publish_order == 'publish' || $pw_publish_order == 'trash') {
        $pw_publish_order_condition_2 = " AND pw_posts.post_status = '" . $pw_publish_order . "'";
    }

    if ($pw_country_code and $pw_country_code != '-1') {
        $pw_country_code_condition_2 = " AND pw_postmeta4.meta_value IN (" . $pw_country_code . ")";
    }

    if ($state_code && $state_code != '-1') {
        $state_code_condition_2 = " AND pw_postmeta_billing_state.meta_value IN (" . $state_code . ")";
    }

    if ($pw_payment_method) {
        $pw_payment_method_condition_2 = " AND pw_postmeta5.meta_value LIKE '%" . $pw_payment_method . "%'";
    }

    if ($pw_order_meta_key and $pw_order_meta_key != '-1') {
        $pw_order_meta_key_condition = " AND pw_order_meta_key.meta_key='{$pw_order_meta_key}' AND pw_order_meta_key.meta_value > 0";
    }

    if ($pw_order_item_name) {
        $pw_order_item_name_condition = " AND pw_woocommerce_order_items.order_item_name LIKE '%" . $pw_order_item_name . "%'";
    }

    if ($txtProduct && $txtProduct != '-1') {
        $txtProduct_condition = " AND pw_woocommerce_order_items.order_item_name LIKE '%" . $txtProduct . "%'";
    }

    if ($pw_product_id && $pw_product_id != "-1") {
        $pw_product_id_condition = " AND woocommerce_order_itemmeta.meta_value IN (" . $pw_product_id . ")";
    }

    if ($category_id && $category_id != "-1") {
        $category_id_condition = " AND term_taxonomy.taxonomy LIKE('product_cat') AND term_taxonomy.term_id IN (" . $category_id . ")";
    }

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    if ($brand_id && $brand_id != "-1") {
        $brand_id_condition = " AND term_taxonomy_brand.taxonomy LIKE('" . __PW_BRAND_SLUG__ . "') AND term_taxonomy_brand.term_id IN (" . $brand_id . ")";
    }


    if ($pw_id_order_status && $pw_id_order_status != "-1") {
        $pw_id_order_status_condition = " AND pw_term_taxonomy2.taxonomy LIKE('shop_order_status') AND pw_term_taxonomy2.term_id IN (" . $pw_id_order_status . ")";
    }

    if ($pw_coupon_used == "yes") {
        $pw_coupon_used_condition = " AND( (pw_postmeta6.meta_key='_order_discount' AND pw_postmeta6.meta_value > 0) ||  (pw_postmeta7.meta_key='_cart_discount' AND pw_postmeta7.meta_value > 0))";
    }


    if ($pw_coupon_code != '' && $pw_coupon_code != "-1") {
        $pw_coupon_code_condition = " AND (pw_woocommerce_order_coupon_item.order_item_name IN ('{$pw_coupon_code}') OR pw_woocommerce_order_coupon_item.order_item_name LIKE '%{$pw_coupon_code}%')";
    }

    if ($pw_coupon_codes != '' && $pw_coupon_codes != "-1") {
        $pw_coupon_codes_condition = " AND pw_woocommerce_order_coupon_item.order_item_name IN ({$pw_coupon_codes})";
    }

    if ($pw_variation_only && $pw_variation_only != "-1" && $pw_variation_only == "yes") {
        $pw_variation_only_condition = " AND pw_woocommerce_order_itemmeta_variation_o.meta_key 	= '_variation_id'
					 AND (pw_woocommerce_order_itemmeta_variation_o.meta_value IS NOT NULL AND pw_woocommerce_order_itemmeta_variation_o.meta_value > 0)";
    }

    ////ADDED IN V4.0
    if (($pw_variation_item_meta_key != "-1" and strlen($pw_variation_item_meta_key) > 1)) {
        $pw_variation_item_meta_key_condition = " AND pw_woocommerce_order_itemmeta_variation.meta_key IN ('{$pw_variation_item_meta_key}')";
    }

    if ($pw_variations_formated != "-1" and $pw_variations_formated != null) {
        $pw_variations_formated_condition = "
			AND pw_woocommerce_order_itemmeta8.meta_key = '_variation_id' AND (pw_woocommerce_order_itemmeta8.meta_value IS NOT NULL AND pw_woocommerce_order_itemmeta8.meta_value > 0)";
        $pw_variations_formated_condition .= "
			AND pw_postmeta_variation.meta_value IN ('{$pw_variations_formated}')";
    }

    ////ADDED IN VER4.0
    /// COST OF GOOD
    if ($pw_show_cog == "yes") {
        $pw_show_cog_condition = " AND woo_itemmeta_cog.meta_key='" . __PW_COG_TOTAL__ . "' ";
    }


    if ($pw_order_status && $pw_order_status != '-1' and $pw_order_status != "'-1'") {
        $pw_order_status_condition = " AND pw_posts.post_status IN (" . $pw_order_status . ")";
    }

    if ($pw_hide_os && $pw_hide_os != '-1' and $pw_hide_os != "'-1'") {
        $pw_hide_os_condition = " AND pw_posts.post_status NOT IN ('" . $pw_hide_os . "')";
    }


    $sql = "SELECT $sql_columns FROM $sql_joins";

    $sql .= "$category_id_join $brand_id_join $pw_id_order_status_join $pw_txt_email_join $pw_txt_first_name_join
				$pw_country_code_join $state_code_join $pw_payment_method_join $pw_billing_post_code_join
				$pw_coupon_used_join $pw_variation_id_join $pw_variation_only_join $pw_variations_formated_join
				$pw_order_meta_key_join $pw_coupon_codes_join $pw_variation_item_meta_key_join $pw_show_cog_join
				$delivery_date_join";

    $sql .= " Where $other_condition_1 $post_type_condition $pw_txt_email_condition_1 $pw_txt_first_name_condition_1
						 $pw_country_code_condition_1 $state_code_condition_1
						$pw_billing_post_code_condition $pw_payment_method_condition_1 $date_condition
						$order_id_condition $pw_txt_email_condition_2 $pw_paid_customer_condition
						$pw_txt_first_name_condition_2 $pw_publish_order_condition_1 $pw_publish_order_condition_2
						$pw_country_code_condition_2 $state_code_condition_2 $pw_payment_method_condition_2
						$pw_order_meta_key_condition $pw_order_item_name_condition $txtProduct_condition
						$pw_product_id_condition $category_id_condition $brand_id_condition $pw_id_order_status_condition
						$pw_coupon_used_condition $pw_coupon_code_condition $pw_coupon_codes_condition $pw_variation_item_meta_key_condition
						$pw_variation_id_condition $pw_variation_only_condition $pw_variations_formated_condition $pw_show_cog_condition
						$pw_order_status_condition $pw_hide_os_condition
						$date_delivery_condition";

    $sql_group_by = " GROUP BY pw_woocommerce_order_items.order_item_id ";
    $sql_order_by = " ORDER BY {$pw_sort_by} {$pw_order_by}";

    $sql .= $sql_group_by . $sql_order_by;

    //echo $sql;
    //print_r($search_fields);


    //CUSTOM WORK - 4227
    //COMPATIBLE WITH CUSTOM FIELDS PLUGIN
    global $wpdb;
    $fetch_custom_fileds = "Select id,pw_meta.meta_value as ffield from {$wpdb->prefix}posts as pw_posts LEFT JOIN {$wpdb->prefix}postmeta as pw_meta ON pw_posts.ID=pw_meta.post_id where pw_posts.post_type='fpf_fields' and pw_meta.meta_key='_fields'";
    $fetch_custom_fileds = $wpdb->get_results($fetch_custom_fileds);


    $ff_columns    = array();
    $ff_columns_in = 0;
    if (defined('FLEXIBLE_PRODUCT_FIELDS_VERSION')) {
        foreach ($fetch_custom_fileds as $ffield) {
            $ff_ = (unserialize($ffield->ffield));
            foreach ($ff_ as $fields) {
                $ff_columns[] = array('lable' => $fields['title'], 'status' => 'show');
                $ff_columns_in++;
            }
        }
    }
    // print_r($ff_columns);


    $columns_total = '';
    if ($pw_detail_view == "yes") {

        $columns = array(
            array('lable' => esc_html__('رمز المنتج (SKU)', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
            array('lable' => esc_html__('الحجم / المنتج الإضافي', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
            array('lable' => esc_html__('الحجم', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
            array('lable' => esc_html__('الكمية', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
        );

    } else {

        $columns = array(
            array('lable' => esc_html__('رمز المنتج (SKU)', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
            array('lable' => esc_html__('الحجم / المنتج الإضافي', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
            array('lable' => esc_html__('الحجم', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
            array('lable' => esc_html__('الكمية', __PW_REPORT_WCREPORT_TEXTDOMAIN__), 'status' => 'show'),
        );
    }

    $columns          = array_values($columns);
    $this->table_cols = $columns;

    // echo "<pre>".print_r($columns, true)."</pre>";

} elseif ($file_used == "data_table") {

    //CUSTOM WORK - 12679
    $pw_clinic_type = '';
    if (is_array(__CUSTOMWORK_ID__) && in_array('12679', __CUSTOMWORK_ID__)) {
        $pw_clinic_type = $this->pw_get_woo_requests('pw_clinic_type', null, true);
    }

    $first_order_id = '';


    //CUSTOM WORK - 4227
    global $wpdb;
    $fetch_custom_fileds = "Select id,pw_meta.meta_value as ffield from {$wpdb->prefix}posts as pw_posts LEFT JOIN {$wpdb->prefix}postmeta as pw_meta ON pw_posts.ID=pw_meta.post_id where pw_posts.post_type='fpf_fields' and pw_meta.meta_key='_fields'";
    $fetch_custom_fileds = $wpdb->get_results($fetch_custom_fileds);
    $ff_columns          = array();
    if (defined('FLEXIBLE_PRODUCT_FIELDS_VERSION')) {
        foreach ($fetch_custom_fileds as $ffield) {
            $ff_ = (unserialize($ffield->ffield));
            foreach ($ff_ as $fields) {
                $ff_columns[] = $fields['title'];
            }
        }
    }


    $order_items = $this->results;
    $categories  = array();
    $order_meta  = array();
    if (count($order_items) > 0) {
        foreach ($order_items as $key => $order_item) {

            $order_id                              = $order_item->order_id;
            $order_items[$key]->billing_first_name = '';//Default, some time it missing
            $order_items[$key]->billing_last_name  = '';//Default, some time it missing
            $order_items[$key]->billing_email      = '';//Default, some time it missing

            if ( ! isset($order_meta[$order_id])) {
                $order_meta[$order_id] = $this->pw_get_full_post_meta($order_id);
            }

            foreach ($order_meta[$order_id] as $k => $v) {
                $order_items[$key]->$k = $v;
            }


            $order_items[$key]->order_total    = isset($order_item->order_total) ? $order_item->order_total : 0;
            $order_items[$key]->order_shipping = isset($order_item->order_shipping) ? $order_item->order_shipping : 0;


            $order_items[$key]->cart_discount  = isset($order_item->cart_discount) ? $order_item->cart_discount : 0;
            $order_items[$key]->order_discount = isset($order_item->order_discount) ? $order_item->order_discount : 0;
            $order_items[$key]->total_discount = isset($order_item->total_discount) ? $order_item->total_discount : ($order_items[$key]->cart_discount + $order_items[$key]->order_discount);


            $order_items[$key]->order_tax          = isset($order_item->order_tax) ? $order_item->order_tax : 0;
            $order_items[$key]->order_shipping_tax = isset($order_item->order_shipping_tax) ? $order_item->order_shipping_tax : 0;
            $order_items[$key]->total_tax          = isset($order_item->total_tax) ? $order_item->total_tax : ($order_items[$key]->order_tax + $order_items[$key]->order_shipping_tax);

            $transaction_id                    = "ransaction ID";
            $order_items[$key]->transaction_id = isset($order_item->$transaction_id) ? $order_item->$transaction_id : (isset($order_item->transaction_id) ? $order_item->transaction_id : '');
            $order_items[$key]->gross_amount   = ($order_items[$key]->order_total + $order_items[$key]->total_discount) - ($order_items[$key]->order_shipping + $order_items[$key]->order_shipping_tax + $order_items[$key]->order_tax);


            $order_items[$key]->billing_first_name = isset($order_item->billing_first_name) ? $order_item->billing_first_name : '';
            $order_items[$key]->billing_last_name  = isset($order_item->billing_last_name) ? $order_item->billing_last_name : '';
            $order_items[$key]->billing_name       = $order_items[$key]->billing_first_name . ' ' . $order_items[$key]->billing_last_name;


        }
    }


    // print_r($order_items);

    $this->results = $order_items;


    // echo "<pre>" . print_r($this->results) . "</pre>";

    $items_render = array();


    ////ADDE IN VER4.0
    /// TOTAL ROWS VARIABLES
    $gross_amnt = $discount_amnt = $shipping_amnt = $shipping_tax_amnt = $cog_amnt = $profit_amnt =
    $order_tax_amnt = $total_tax_amnt = $part_refund_amnt = $order_count =
    $product_count = $product_qty = $total_rate = $product_amnt = $product_discount = $net_amnt = 0;



    $default_country = get_option("woocommerce_default_country");
    $default_country = (explode(":", str_replace("", " ", $default_country)))[0];

    foreach ($this->results as $items) {


        //CUSTOM WORK - 12679
        $custom_fields = '';
        if (is_array(__CUSTOMWORK_ID__) && in_array('12679', __CUSTOMWORK_ID__)) {
            $custom_fields = wc_get_order_item_meta($items->order_item_id, 'Gallery Name', true);
            if ($custom_fields) {
                $custom_fields = explode("-", $custom_fields);
                $custom_fields = $custom_fields[2];
            }
            if ($pw_clinic_type != '-1' && strtolower($pw_clinic_type) != strtolower($custom_fields)) {
                continue;
            }
        }

        $index_cols = 0;

        ////ADDE IN VER4.0
        /// TOTAL ROWS
        $product_count++;

        $order_id = $items->order_id;

        $order = new WC_Order($order_id);

        $fetch_other_data = '';

        if ( ! isset($this->order_meta[$order_id])) {
            $fetch_other_data = $this->pw_get_full_post_meta($order_id);
        }

        $new_order = false;
        if ($first_order_id == '') {
            $first_order_id = $items->order_id;
            $new_order      = true;
        } elseif ($first_order_id != $items->order_id) {
            $first_order_id = $items->order_id;
            $new_order      = true;
        }
        $pw_detail_view = $this->pw_get_woo_requests('pw_view_details', "no", true);


        if ($pw_detail_view == "yes") {
            if ($new_order) {

                $has_serialized_data = maybe_unserialize(wc_get_order_item_meta(  $items->order_item_id, '_ywapo_meta_data'));
                $is_individually_sold = wc_get_order_item_meta(  $items->order_item_id, '_yith_wapo_individual_addons');
                $order_id = $items->order_id;

                //SKU
                $display_class = '';
                $item_id = $items->order_item_id;
                $sku = '';
                if (wc_get_order_item_meta(  $item_id, '_yith_wapo_individual_addons')){
    				$value_unserialized = maybe_unserialize(wc_get_order_item_meta(  $item_id, '_ywapo_meta_data'));
    				
    				$desired_index = array_key_first($value_unserialized[0]);
    				
    				$addon_id = explode("-", $value_unserialized[0][$desired_index]);
    				
    				$product = wc_get_product( $addon_id[1] );
    				
    				$sku = $product->get_sku();
    			} else {
    			    $sku = $this->pw_get_prod_sku($items->order_item_id, $items->product_id);
    			}
                
                if ($items->fee) {
                    $sku = 'Fee';
                }

                //Variation
                $pw_table_value = '';
                $variation_id   = $items->variation_id;
                if ($variation_id != 0) {
                    $variation  = new WC_Product_Variation($variation_id);
                    $attributes = $variation->get_attributes();
                    foreach ($attributes as $key => $value) {
                        $pw_table_value .= urldecode($attributes[$key]);
                    }
                }

                $qty = $items->product_quantity;
                if ($items->fee) {
                    $qty = 0;
                }

                if (wc_get_order_item_meta( $items->order_item_id, '_ywcars_item_refunded') == 'yes') {
					$qty = -1 * abs($qty);
				}
                $this->t_products[$sku][$pw_table_value] += $qty;
                $related = $pw_table_value;

                $has_serialized_data = maybe_unserialize(wc_get_order_item_meta(  $item_id, '_ywapo_meta_data'));
                $is_individually_sold = wc_get_order_item_meta(  $item_id, '_yith_wapo_individual_addons');

                if ($has_serialized_data && !$is_individually_sold) {
                    for ($x = 0; $x < count($has_serialized_data); $x++) {
                        $desired_index = array_key_first($has_serialized_data[$x]);
                        
                        $addon_id = explode("-", $has_serialized_data[$x][$desired_index]);

                        if ($addon_id[0] == "product") {
                            $product = wc_get_product( $addon_id[1] );
                            
                            $variation = new WC_Product_Variation($item['variation_id']);
                            $variationName = implode(" / ", $variation->get_variation_attributes());
                            $variationName = urldecode(str_replace('-', ' ', $variationName));
                                
                            $product_name = $product->get_name();
                            $product_sku = $product->get_sku();
                            $product_qtyy = wc_get_order_item_meta(  $item_id, '_qty');
                            $product_price = $product->get_price();
                            $total_price = $product_price * $product_qtyy;
                            
                            $related_product_tax = wc_get_price_including_tax( $product ) * $product_qtyy - $total_price;
                            $order_id = $items->order_id;
                            
                            //Variation
                            $pw_table_value = '';
                            $variation_id   = $items->variation_id;
                            if ($variation_id != 0) {
                                $variation  = new WC_Product_Variation($variation_id);
                                $attributes = $variation->get_attributes();
                                foreach ($attributes as $key => $value) {
                                    $pw_table_value .= urldecode($attributes[$key]);
                                }
                            }
                            $this->t_products[$sku][$product_sku][$related] += $product_qtyy;
                            $this->t_products[$sku][$related] -= $product_qtyy;
                            $related = '';
                        }
                    }
                }
            } else {
                    $has_serialized_data = maybe_unserialize(wc_get_order_item_meta(  $items->order_item_id, '_ywapo_meta_data'));
                    $is_individually_sold = wc_get_order_item_meta(  $items->order_item_id, '_yith_wapo_individual_addons');
                    $order_id = $items->order_id;
                    
                    //SKU
                    $display_class = '';$item_id = $items->order_item_id;
                    $sku = '';
                    if (wc_get_order_item_meta(  $item_id, '_yith_wapo_individual_addons')){
                        $value_unserialized = maybe_unserialize(wc_get_order_item_meta(  $item_id, '_ywapo_meta_data'));
                        
                        $desired_index = array_key_first($value_unserialized[0]);
                        
                        $addon_id = explode("-", $value_unserialized[0][$desired_index]);
                        
                        $product = wc_get_product( $addon_id[1] );
                        
                        $sku = $product->get_sku();
                    } else {
                        $sku = $this->pw_get_prod_sku($items->order_item_id, $items->product_id);
                    }
                    
                    if ($items->fee) {
                        $sku = 'Fee';
                    }
                    
                    //Variation
                    $pw_table_value = '';
                    $variation_id   = $items->variation_id;
                    if ($variation_id != 0) {
                        $variation  = new WC_Product_Variation($variation_id);
                        $attributes = $variation->get_attributes();
                        foreach ($attributes as $key => $value) {
                            $pw_table_value .= urldecode($attributes[$key]);
                        }
                    }

                    $qty = $items->product_quantity;
                    if ($items->fee) {
                        $qty = 0;
                    }

                    if (wc_get_order_item_meta( $items->order_item_id, '_ywcars_item_refunded') == 'yes') {
                        $qty = -1 * abs($qty);
                    }
                    $this->t_products[$sku][$pw_table_value] += $qty;
                    $related = $pw_table_value;
                    
                    $has_serialized_data = maybe_unserialize(wc_get_order_item_meta(  $item_id, '_ywapo_meta_data'));
                    $is_individually_sold = wc_get_order_item_meta(  $item_id, '_yith_wapo_individual_addons');
					$is_gift = wc_get_order_item_meta(  $item_id, '_fgf_gift_product');
    
                    if ($has_serialized_data && !$is_individually_sold && empty($is_gift)) {
                        for ($x = 0; $x < count($has_serialized_data); $x++) {
                            $desired_index = array_key_first($has_serialized_data[$x]);
                            
                            $addon_id = explode("-", $has_serialized_data[$x][$desired_index]);
    
                            if ($addon_id[0] == "product") {
                                $product = wc_get_product( $addon_id[1] );
                                
                                $variation = new WC_Product_Variation($item['variation_id']);
                                $variationName = implode(" / ", $variation->get_variation_attributes());
                                $variationName = urldecode(str_replace('-', ' ', $variationName));
                                    
                                $product_name = $product->get_name();
                                $product_sku = $product->get_sku();
                                $product_qtyy = wc_get_order_item_meta(  $item_id, '_qty');
                                $product_price = $product->get_price();
                                $total_price = $product_price * $product_qtyy;
                                
                                $related_product_tax = wc_get_price_including_tax( $product ) * $product_qtyy - $total_price;
                                $order_id = $items->order_id;

                                //Variation
                                $pw_table_value = '';
                                $variation_id   = $items->variation_id;
                                if ($variation_id != 0) {
                                    $variation  = new WC_Product_Variation($variation_id);
                                    $attributes = $variation->get_attributes();
                                    foreach ($attributes as $key => $value) {
                                        $pw_table_value .= urldecode($attributes[$key]);
                                    }
                                }
                                $this->t_products[$sku][$product_sku][$related] += $product_qtyy;
                                $this->t_products[$sku][$related] -= $product_qtyy;
                                $related = '';
                            }
                        }
                    }
            }
        }
    }

    if (!empty($this->t_products)) {
        foreach ($this->t_products as $key => $t_products) {
            if (is_array($t_products) && !empty($t_products)) {
                foreach ($t_products as $key1 => $products) {
                    $datatable_value .= ("<tr>");
                    $datatable_value .= ("<td>");
                    $datatable_value .= $key;
                    $datatable_value .= ("</td>");
                    $datatable_value .= ("<td>");
                    $datatable_value .= $key1;
                    $datatable_value .= ("</td>");
                    if (is_array($products) && !empty($products)) {
                        $counter = 0;
                        foreach ($products as $key2 => $r_products) {
                            $datatable_value .= ("<td>");
                            $datatable_value .= $key2;
                            $datatable_value .= ("</td>");
                            $datatable_value .= ("<td>");
                            $datatable_value .= $r_products;
                            $datatable_value .= ("</td>");
                            if ($counter == count( $products ) - 1) {
                                $datatable_value .= ("</tr>");
                            } else {
                                $datatable_value .= ("<tr>");
                                $datatable_value .= ("<td>");
                                $datatable_value .= $key;
                                $datatable_value .= ("</td>");
                                $datatable_value .= ("<td>");
                                $datatable_value .= $key1;
                                $datatable_value .= ("</td>");
                                $counter++;
                            }
                        }
                    } else {
                        $datatable_value .= ("<td>");
                        $datatable_value .= $key1;
                        $datatable_value .= ("</td>");
                        $datatable_value .= ("<td>");
                        $datatable_value .= $products;
                        $datatable_value .= ("</td>");
                        $datatable_value .= ("</tr>");
                    }
                }
            }
        }
    }

    $this->t_products = array();

    // echo "<pre>".print_r($t_products, true)."</pre>";

    // ////ADDED IN VER4.0
    // /// TOTAL ROW
    // $datatable_value_total = '';
    // $pw_detail_view        = $this->pw_get_woo_requests('pw_view_details', "no", true);
    // $pw_show_cog           = $this->pw_get_woo_requests('pw_show_cog', 'no', true);
    // $table_name_total      = "details_depot";
    // if ($pw_detail_view == "yes") {
    //     $table_name_total = $table_name . "_with_items";

    //     $this->table_cols_total = $this->table_columns_total($table_name_total);
    //     if ($pw_show_cog != 'yes') {
    //         ////ADDE IN VER4.0
    //         /// COST OF GOOD
    //         unset($this->table_cols_total[count($this->table_cols_total) - 1]);
    //         unset($this->table_cols_total[count($this->table_cols_total) - 1]);
    //     }

    //     $datatable_value_total .= ("<tr>");
    //     $datatable_value_total .= "<td>$order_count</td>";
    //     $datatable_value_total .= "<td>$product_count</td>";
    //     $datatable_value_total .= "<td>$product_qty</td>";
    //     $datatable_value_total .= "<td>" . (($total_rate) == 0 ? $this->price(0) : $this->price($total_rate)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($product_amnt) == 0 ? $this->price(0) : $this->price($product_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($product_discount) == 0 ? $this->price(0) : $this->price($product_discount)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($net_amnt) == 0 ? $this->price(0) : $this->price($net_amnt)) . "</td>";

    //     if ($pw_show_cog == 'yes') {
    //         $datatable_value_total .= "<td>" . (($cog_amnt) == 0 ? $this->price(0) : $this->price($cog_amnt)) . "</td>";
    //         $datatable_value_total .= "<td>" . (($profit_amnt) == 0 ? $this->price(0) : $this->price($profit_amnt)) . "</td>";
    //     }
    //     $datatable_value_total .= ("</tr>");

    //     echo "<pre>".print_r($this->t_products, true)."</pre>";
    // } else {
    //     $table_name_total = $table_name . "_no_items";

    //     $this->table_cols_total = $this->table_columns_total($table_name_total);
    //     if ($pw_show_cog != 'yes') {
    //         ////ADDE IN VER4.0
    //         /// COST OF GOOD
    //         unset($this->table_cols_total[count($this->table_cols_total) - 1]);
    //         unset($this->table_cols_total[count($this->table_cols_total) - 1]);
    //     }

    //     $datatable_value_total .= ("<tr>");
    //     $datatable_value_total .= "<td>$order_count</td>";
    //     $datatable_value_total .= "<td>" . (($gross_amnt) == 0 ? $this->price(0) : $this->price($gross_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($discount_amnt) == 0 ? $this->price(0) : $this->price($discount_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($shipping_amnt) == 0 ? $this->price(0) : $this->price($shipping_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($shipping_tax_amnt) == 0 ? $this->price(0) : $this->price($shipping_tax_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($order_tax_amnt) == 0 ? $this->price(0) : $this->price($order_tax_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($total_tax_amnt) == 0 ? $this->price(0) : $this->price($total_tax_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($part_refund_amnt) == 0 ? $this->price(0) : $this->price($part_refund_amnt)) . "</td>";
    //     $datatable_value_total .= "<td>" . (($net_amnt) == 0 ? $this->price(0) : $this->price($net_amnt)) . "</td>";

    //     if ($pw_show_cog == 'yes') {
    //         $datatable_value_total .= "<td>" . (($cog_amnt) == 0 ? $this->price(0) : $this->price($cog_amnt)) . "</td>";
    //         $datatable_value_total .= "<td>" . (($profit_amnt) == 0 ? $this->price(0) : $this->price($profit_amnt)) . "</td>";
    //     }

    //     $datatable_value_total .= ("</tr>");
    // }


} elseif ($file_used == "search_form") {
    ?>
    <form class='alldetails search_form_report' action='' method='post'>
        <input type='hidden' name='action' value='submit-form'/>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Date From', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
            <input name="pw_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick"/>
        </div>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Date To', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
            <input name="pw_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"/>
        </div>

        <?php
        //CUSTOM WORK - 11899
        if (is_array(__CUSTOMWORK_ID__) && in_array('11899', __CUSTOMWORK_ID__)) {

            ?>
            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php _e('Delivery Date From', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="pw_from_date_delivery" id="pwr_from_date_delivery" type="text" readonly='true'
                       class="datepick"/>
            </div>

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php _e('Delivery Date To', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="pw_to_date_delivery" id="pwr_to_date_delivery" type="text" readonly='true'
                       class="datepick"/>
            </div>
            <?php
        }
        ?>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Order ID', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
            <input name="pw_id_order" type="text" class="" placeholder="<?php _e('Separate IDs with (,) Example : 1,2',
                __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>"/>
        </div>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Customer', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-user"></i></span>
            <input name="pw_first_name_text" type="text" class=""/>
        </div>

        <?php
        $col_style        = '';
        $permission_value = $this->get_form_element_value_permission('pw_category_id');
        if ($this->get_form_element_permission('pw_category_id') || $permission_value != '') {

            if ( ! $this->get_form_element_permission('pw_category_id') && $permission_value != '') {
                $col_style = 'display:none';
            }
            ?>
            <div class="col-md-6" style=" <?php echo $col_style; ?>">
                <div class="awr-form-title">
                    <?php _e('Category', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-tags"></i></span>
                <?php
                $args = array(
                    'orderby'      => 'name',
                    'order'        => 'ASC',
                    'hide_empty'   => 1,
                    'hierarchical' => 0,
                    'exclude'      => '',
                    'include'      => '',
                    'child_of'     => 0,
                    'number'       => '',
                    'pad_counts'   => false

                );

                //$categories = get_categories($args);
                $current_category = $this->pw_get_woo_requests_links('pw_category_id', '', true);

                $categories = get_terms('product_cat', $args);
                $option     = '';
                foreach ($categories as $category) {

                    $selected = '';
                    //CHECK IF IS IN PERMISSION
                    if (is_array($permission_value) && ! in_array($category->term_id, $permission_value)) {
                        continue;
                    }

                    /*if(!$this->get_form_element_permission('pw_category_id') &&  $permission_value!='')
						$selected="selected";

					if($current_category==$category->term_id)
						$selected="selected";*/

                    $option .= '<option value="' . $category->term_id . '" ' . $selected . '>';
                    $option .= $category->name;
                    $option .= ' (' . $category->count . ')';
                    $option .= '</option>';
                }
                ?>
                <select name="pw_category_id[]" multiple="multiple" size="5" data-size="5" class="chosen-select-search">
                    <?php
                    if ($this->get_form_element_permission('pw_category_id') && (( ! is_array($permission_value)) || (is_array($permission_value) && in_array('all',
                                    $permission_value)))) {
                        ?>
                        <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <?php
                    }
                    ?>
                    <?php
                    echo $option;
                    ?>
                </select>

            </div>
            <?php
        }

        ////ADDED IN VER4.0
        //BRANDS ADDONS
        ////////////////BRANDS-ADDON////////////
        $col_style        = '';
        $permission_value = $this->get_form_element_value_permission('pw_brand_id');

        if (__PW_BRAND_SLUG__ && ($this->get_form_element_permission('pw_brand_id') || $permission_value != '')) {
            if ( ! $this->get_form_element_permission('pw_brand_id') && $permission_value != '') {
                $col_style = 'display:none';
            }

            //if(count($permission_value)==1) $col_style='display:none';
            ?>
            <div class="col-md-6" style=" <?php echo $col_style; ?>">
                <div class="awr-form-title">
                    <?php echo __PW_BRAND_LABEL__; ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-tags"></i></span>
                <?php
                $args = array(
                    'orderby'      => 'name',
                    'order'        => 'ASC',
                    'hide_empty'   => 1,
                    'hierarchical' => 0,
                    'exclude'      => '',
                    'include'      => '',
                    'child_of'     => 0,
                    'number'       => '',
                    'pad_counts'   => false

                );

                //$categories = get_categories($args);
                $current_category = $this->pw_get_woo_requests_links('pw_brand_id', '', true);

                $current_category = $permission_value;

                $categories = get_terms(__PW_BRAND_SLUG__, $args);
                $option     = '';
                foreach ($categories as $category) {
                    $selected = '';
                    //CHECK IF IS IN PERMISSION
                    if (is_array($permission_value) && ! in_array($category->term_id, $permission_value)) {
                        continue;
                    }

                    /*if($this->get_form_element_permission('pw_brand_id') &&  $permission_value!='')
						$selected="selected";


					if(is_array($current_category) && in_array($category->term_id,$current_category))
						$selected="selected";*/

                    $option .= '<option value="' . $category->term_id . '" ' . $selected . '>';
                    $option .= $category->name;
                    $option .= ' (' . $category->count . ')';
                    $option .= '</option>';
                }
                ?>
                <select name="pw_brand_id[]" multiple="multiple" size="5" data-size="5" class="chosen-select-search">
                    <?php
                    if ($this->get_form_element_permission('pw_brand_id') && (( ! is_array($permission_value)) || (is_array($permission_value) && in_array('all',
                                    $permission_value)))) {
                        ?>
                        <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <?php
                    }
                    ?>
                    <?php
                    echo $option;
                    ?>
                </select>

            </div>
            <?php
        }

        $col_style        = '';
        $permission_value = $this->get_form_element_value_permission('pw_product_id');
        if ($this->get_form_element_permission('pw_product_id') || $permission_value != '') {

            if ( ! $this->get_form_element_permission('pw_product_id') && $permission_value != '') {
                $col_style = 'display:none';
            }

            ?>

            <div class="col-md-6" style=" <?php echo $col_style; ?>">
                <div class="awr-form-title">
                    <?php _e('Product', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-gear"></i></span>
                <?php
                $products        = $this->pw_get_product_woo_data('all');
                $option          = '';
                $current_product = $this->pw_get_woo_requests_links('pw_product_id', '', true);
                //echo $current_product;

                foreach ($products as $product) {
                    $selected = '';
                    if (is_array($permission_value) && ! in_array($product->id, $permission_value)) {
                        continue;
                    }

                    /*if(!$this->get_form_element_permission('pw_product_id') &&  $permission_value!='')
						$selected="selected";*/


                    if ($current_product == $product->id) {
                        $selected = "selected";
                    }
                    $option .= "<option $selected value='" . $product->id . "' >" . $product->label . " </option>";
                }


                ?>
                <select name="pw_product_id[]" multiple="multiple" size="5" data-size="5" class="chosen-select-search">
                    <?php
                    if ($this->get_form_element_permission('pw_product_id') && (( ! is_array($permission_value)) || (is_array($permission_value) && in_array('all',
                                    $permission_value)))) {
                        ?>
                        <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <?php
                    }
                    ?>
                    <?php
                    echo $option;
                    ?>
                </select>

            </div>
            <?php
        }
        ?>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Customer', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-user"></i></span>
            <?php
            $customers = $this->pw_get_woo_customers_orders();
            $option    = '';
            foreach ($customers as $customer) {
                $option .= "<option value='" . $customer->id . "' >" . $customer->label . " ($customer->counts)</option>";
            }
            ?>
            <select name="pw_customers_paid[]" multiple="multiple" size="5" data-size="5" class="chosen-select-search">
                <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                <?php
                echo $option;
                ?>
            </select>

        </div>

        <?php
        $col_style        = '';
        $permission_value = $this->get_form_element_value_permission('pw_orders_status');
        if ($this->get_form_element_permission('pw_orders_status') || $permission_value != '') {

            if ( ! $this->get_form_element_permission('pw_orders_status') && $permission_value != '') {
                $col_style = 'display:none';
            }
            ?>

            <div class="col-md-6" style=" <?php echo $col_style; ?>">
                <div class="awr-form-title">
                    <?php _e('Status', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-check"></i></span>
                <?php
                $pw_order_status = $this->pw_get_woo_orders_statuses();

                ////ADDED IN VER4.0
                $shop_status_selected = '';
                /// APPLY DEFAULT STATUS AT FIRST
                if ($this->pw_shop_status) {
                    $shop_status_selected = explode(",", $this->pw_shop_status);
                }

                $option = '';
                foreach ($pw_order_status as $key => $value) {

                    $selected = "";
                    if (is_array($permission_value) && ! in_array($key, $permission_value)) {
                        continue;
                    }

                    /*if(!$this->get_form_element_permission('pw_orders_status') &&  $permission_value!='')
						$selected="selected";*/

                    ////ADDED IN VER4.0
                    /// APPLY DEFAULT STATUS AT FIRST
                    if (is_array($shop_status_selected) && in_array($key, $shop_status_selected)) {
                        $selected = "selected";
                    }

                    $option .= "<option value='" . $key . "' $selected >" . $value . "</option>";
                }
                ?>

                <select name="pw_orders_status[]" multiple="multiple" size="5" data-size="5"
                        class="chosen-select-search">
                    <?php
                    if ($this->get_form_element_permission('pw_orders_status') && (( ! is_array($permission_value)) || (is_array($permission_value) && in_array('all',
                                    $permission_value)))) {
                        ?>
                        <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <?php
                    }
                    ?>
                    <?php
                    echo $option;
                    ?>
                </select>
                <input type="hidden" name="pw_id_order_status[]" id="pw_id_order_status" value="-1">
            </div>
            <?php
        }
        $col_style        = '';
        $permission_value = $this->get_form_element_value_permission('pw_countries_code');
        if ($this->get_form_element_permission('pw_countries_code') || $permission_value != '') {
            if ( ! $this->get_form_element_permission('pw_countries_code') && $permission_value != '')
                $col_style = 'display:none'

            ?>
            <div class="col-md-6" style=" <?php echo $col_style; ?>">
                <div class="awr-form-title">
                    <?php _e('Country', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-globe"></i></span>
                <?php
                $country_data = $this->pw_get_paying_woo_state('billing_country');
                $country      = $this->pw_get_woo_countries();
                $option       = '';
                foreach ($country_data as $countries) {
                    $selected = '';
                    //CHECK IF IS IN PERMISSION
                    if (is_array($permission_value) && ! in_array($countries->id, $permission_value)) {
                        continue;
                    }

                    /*if(!$this->get_form_element_permission('pw_countries_code') &&  $permission_value!='')
						$selected="selected";	*/

                    $pw_table_value = $country->countries[$countries->id];
                    $option         .= "<option value='" . $countries->id . "' $selected >" . $pw_table_value . "</option>";
                }

                $country_states      = $this->pw_get_woo_country_of_state();
                $json_country_states = json_encode($country_states);
                //print_r($json_country_states);
                ?>
                <select id="pw_adr_country" name="pw_countries_code[]" multiple="multiple" size="5" data-size="5"
                        class="chosen-select-search">
                    <?php
                    if ($this->get_form_element_permission('pw_countries_code') && (( ! is_array($permission_value)) || (is_array($permission_value) && in_array('all',
                                    $permission_value)))) {
                        ?>
                        <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <?php
                    }
                    ?>
                    <?php
                    echo $option;
                    ?>
                </select>

                <script type="text/javascript">
                    "use strict";
                    jQuery(document).ready(function ($) {

                        var country_state = '';
                        country_state =<?php echo $json_country_states?>;

                        $("#pw_adr_country").change(function () {
                            var country_val = $(this).val();

                            if (country_val == null) {
                                return false;
                            }

                            var option_data = Array();
                            var optionss = '<option value="-1">Select All</option>';
                            var i = 1;
                            $.each(country_state, function (key, val) {

                                if (country_val.indexOf(val.parent_id) >= 0 || country_val == "-1") {
                                    optionss += '<option value="' + val.id + '">' + val.label + '</option>';
                                    option_data[val.id] = val.label;
                                }
                                i++;
                            });

                            $('#pw_adr_state').empty(); //remove all child nodes
                            $("#pw_adr_state").html(optionss);
                            $('#pw_adr_state').trigger("chosen:updated");
                        });


                    });

                </script>

            </div>

            <?php
        }
        $col_style        = '';
        $permission_value = $this->get_form_element_value_permission('pw_states_code');
        if ($this->get_form_element_permission('pw_states_code') || $permission_value != '') {
            if ( ! $this->get_form_element_permission('pw_states_code') && $permission_value != '') {
                $col_style = 'display:none';
            }
            ?>

            <div class="col-md-6" style=" <?php echo $col_style; ?>">
                <div class="awr-form-title">
                    <?php _e('State', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-map"></i></span>
                <?php
                //$state_codes = $this->pw_get_paying_woo_state('shipping_state','shipping_country');
                //$this->pw_get_woo_country_of_state();
                //$this->pw_get_woo_bsn($order->billing_country,$items->billing_state_code);

                $country_data = $this->pw_get_paying_woo_state('billing_country');


                $state_codes_country    = $this->pw_get_paying_woo_state('billing_state','billing_country');

                $state_codes_no_country = $this->pw_get_paying_woo_state('billing_state');

                $final_state = [];
                foreach ($state_codes_country as $states){
                    $final_state[$states->id] = $states->billing_country;
                }

                $default_country = get_option("woocommerce_default_country");
                $default_country = (explode(":", str_replace("", " ", $default_country)))[0];

                foreach ($state_codes_no_country as $states){
                    if (!array_key_exists($states->id,$final_state)){
                        $final_state[$states->id] = 'kKK';
                    }
                }

                $option = '';
                foreach ($final_state as $state => $country) {

                    $selected = "";
                    //CHECK IF IS IN PERMISSION
                    if (is_array($permission_value) && ! in_array($state, $permission_value)) {
                        continue;
                    }

                    $pw_table_value = $this->pw_get_woo_bsn($country, $state);
                    $option .= "<option $selected value='" . $state . "' >" . $pw_table_value . " ($country)</option>";
                }


                ?>

                <select id="pw_adr_state" name="pw_states_code[]" multiple="multiple" size="5" data-size="5"
                        class="chosen-select-search">
                    <?php
                    if ($this->get_form_element_permission('pw_states_code') && (( ! is_array($permission_value)) || (is_array($permission_value) && in_array('all',
                                    $permission_value)))) {
                        ?>
                        <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <?php
                    }
                    ?>
                    <?php
                    echo $option;
                    ?>
                </select>

            </div>
            <?php
        }
        ?>

        <?php
        ////ADDED IN V4.0
        ?>
        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Variations', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-bolt"></i></span>
            <?php
            $option        = '';
            $pw_variations = $this->pw_get_woo_pv_atts('yes');

            $all_value = array();
            foreach ($pw_variations as $key => $value) {
                $selected    = '';
                $new_key     = str_replace("wcv_", "", $key);
                $option      .= "<option value='" . $new_key . "' >" . $value . " </option>";
                $all_value[] = $new_key;
            }

            if ( ! is_array($all_value)) {
                $all_value = '-1';
            } else {
                $all_value = implode(",", $all_value);
            }
            ?>

            <select name="pw_variation_id[]" multiple="multiple" size="5" data-size="5"
                    class="chosen-select-search variation_elements">
                <option value="<?php echo $all_value ?>"><?php _e('Select All',
                        __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                <?php
                echo $option;
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Variation Only', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>

            <input name="pw_variation_only" type="checkbox" value="yes"/>

        </div>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Postcode(Zip)', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-map-marker"></i></span>
            <input name="pw_bill_post_code" type="text"/>
        </div>


        <!--<div class="col-md-6">
                    <div class="awr-form-title">
                        <?php _e('Min & Max By', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-arrows-h"></i></span>
                    <select name="order_meta_key[]" id="order_meta_key2" class="order_meta_key normal_view_only">
                        <option value="-1">Select All</option>
                        <option value="_order_total">Order Net Amount</option>
                        <option value="_order_discount">Order Discount Amount</option>
                        <option value="_order_shipping">Order Shipping Amount</option>
                        <option value="_order_shipping_tax">Order Shipping Tax Amount</option>
                    </select>
                    <br />
                    <span class="description"><?php _e("Enable this selection by uncheck 'Show Order Item Details'
", __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></span>
                </div>

                 <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php _e('Min Amount', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-battery-0"></i></span>
                    <input name="min_amount" type="text"/>
                    <br />
                    <span class="description"><?php _e("Enable this selection by uncheck 'Show Order Item Details'
", __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></span>
                </div>

                 <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php _e('Max Amount', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-battery-4"></i></span>
                    <input name="max_amount" type="text"/>
                    <br />
                    <span class="description"><?php _e("Enable this selection by uncheck 'Show Order Item Details'
", __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></span>
                </div>-->

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Email', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-envelope-o"></i></span>
            <input name="pw_email_text" type="text"/>
        </div>


        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Order By', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-sort-alpha-asc"></i></span>
            <div class="row">
                <div class="col-md-6">

                    <select name="sort_by" id="sort_by" class="sort_by">
                        <option value="order_id" selected="selected">Order ID</option>
                        <option value="billing_name">Name</option>
                        <option value="billing_email">Email</option>
                        <option value="order_date">Date</option>
                        <option value="post_status">Status</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="order_by" id="order_by" class="order_by">
                        <option value="ASC">Ascending</option>
                        <option value="DESC" selected="selected">Descending</option>
                    </select>
                </div>
            </div>
        </div>


        <!--CUSTOM WORK-->
        <div class="col-md-6">
            <div class="awr-form-title">
                <?php
                $pw_coupon_codes = $this->pw_get_woo_coupons_codes();
                $option          = '';
                foreach ($pw_coupon_codes as $coupon) {
                    $selected = '';
                    /*if($current_product==$product->id)
						$selected="selected";*/
                    $option .= "<option $selected value='" . $coupon->id . "' >" . $coupon->label . " </option>";
                }
                ?>
                <?php _e('Coupon Codes', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-key"></i></span>
            <select name="pw_codes_of_coupon[]" multiple="multiple" size="5" data-size="5" class="chosen-select-search">
                <option value="-1"><?php _e('Select All', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                <?php
                echo $option;
                ?>
            </select>
        </div>

        <!--<div class="col-md-6">
                    <div class="awr-form-title">
                        <?php _e('Discount Type', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-money"></i></span>
                    <select name="pw_coupon_discount_types" >
                        <option value="-1"><?php _e('Select One', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <option value="percent"><?php _e('Percentage Discount', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <option value="fixed_cart"><?php _e('Fixed Cart Discount',
            __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                        <option value="fixed_product"><?php _e('Fixed Product Discount',
            __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                    </select>
                </div>
-->


        <?php
        //CUSTOM WORK - 12679
        if (is_array(__CUSTOMWORK_ID__) && in_array('12679', __CUSTOMWORK_ID__)) {
            $clinic_type    = get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__ . 'clinic_type');
            $clinic_type    = explode(",", $clinic_type);
            $clinic_options = '';
            foreach ($clinic_type as $clinic) {
                $clinic_options .= '<option value="' . $clinic . '">' . $clinic . '</option>';
            }
            ?>
            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php _e('Clinic Type', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-money"></i></span>
                <select name="pw_clinic_type">
                    <option value="-1"><?php _e('Select One', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></option>
                    <?php echo $clinic_options; ?>
                </select>
            </div>
            <?php
        }
        ?>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Show Order Item Details', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>

            <input name="pw_view_details" type="checkbox" value="yes" checked/>

        </div>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php _e('Coupon Used Only', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
            </div>

            <input name="pw_use_coupon" type="checkbox" value="yes"/>

        </div>


        <?php
        if (__PW_COG__ != '') {
            ?>

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php _e('SHOW JUST INCLUDE C.O.G & PROFIT', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>
                    <br/>
                    <span class="description"><?php _e('Include just products with current Profit(Cost of good) plugin(Selected in Setting -> Add-on Settings -> Cost of Good).',
                            __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></span>
                </div>

                <input name="pw_show_cog" type="checkbox" value="yes"/>

            </div>
            <?php
        }
        ?>


        <div class="col-md-12">
            <?php
            $pw_hide_os         = $this->otder_status_hide;
            $pw_publish_order   = 'no';
            $pw_order_item_name = '';
            $pw_coupon_code     = '';
            $pw_coupon_codes    = '';
            $pw_payment_method  = '';

            $pw_variation_only = $this->pw_get_woo_requests_links('pw_variation_only', '-1', true);
            $pw_order_meta_key = '';

            $data_format = $this->pw_get_woo_requests_links('date_format', get_option('date_format'), true);


            $amont_zero = '';

            ?>

            <input type="hidden" name="pw_hide_os" value="<?php echo $pw_hide_os; ?>"/>
            <input type="hidden" name="publish_order" value="<?php echo $pw_publish_order; ?>"/>
            <input type="hidden" name="order_item_name" value="<?php echo $pw_order_item_name; ?>"/>
            <input type="hidden" name="coupon_code" value="<?php echo $pw_coupon_code; ?>"/>
            <input type="hidden" name="payment_method" value="<?php echo $pw_payment_method; ?>"/>


            <input type="hidden" name="date_format" value="<?php echo $data_format; ?>"/>

            <input type="hidden" name="table_names" value="<?php echo $table_name; ?>"/>
            <div class="fetch_form_loading search-form-loading"></div>
            <button type="submit" value="Search" class="button-primary"><i class="fa fa-search"></i>
                <span><?php echo esc_html__('Search', __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></span></button>
            <button type="button" value="Reset" class="button-secondary form_reset_btn"><i
                        class="fa fa-reply"></i><span><?php echo esc_html__('Reset Form',
                        __PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></span></button>
        </div>

    </form>
    <?php
}
?>