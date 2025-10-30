<?php
global $pw_rpt_main_class;


global $wpdb;

$order_date="SELECT pw_posts.ID AS order_id, pw_posts.post_date AS order_date, pw_posts.post_status AS order_status FROM {$wpdb->prefix}posts as pw_posts WHERE pw_posts.post_type='shop_order' AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND pw_posts.post_status NOT IN ('trash') GROUP BY pw_posts.ID Order By pw_posts.post_date ASC LIMIT 5";
$results= $wpdb->get_results($order_date);

$first_date='';
$i=0;
while($i<5){

	if(count($results)>0 && $results[$i]->order_date!=0)
	{
		if(isset($results[$i]))
			$first_date=$results[$i]->order_date;
		break;
	}
	$i++;
}

$order_date="SELECT pw_posts.ID AS order_id, pw_posts.post_date AS order_date, pw_posts.post_status AS order_status FROM {$wpdb->prefix}posts as pw_posts WHERE pw_posts.post_type='shop_order' AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND pw_posts.post_status NOT IN ('trash') GROUP BY pw_posts.ID Order By pw_posts.post_date DESC LIMIT 1";
$results= $wpdb->get_results($order_date);

$pw_to_date='';
if(isset($results[0]))
	$pw_to_date=$results[0]->order_date;

if($first_date==''){
	$first_date= date("Y-m-d");

	if(get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$first_date=get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'from_date',$first_date);
	}

	$this->pw_from_date_dashboard=$first_date;
	$first_date=substr($first_date,0,4);
}else{

	if(get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$first_date=get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'from_date',$first_date);
	}

	$pw_from_date_dashboard=explode(" ",$first_date);
	$this->pw_from_date_dashboard=$pw_from_date_dashboard[0];

	$first_date=substr($first_date,0,4);
}

if($pw_to_date==''){
	$pw_to_date= date("Y-m-d");
	if(get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$pw_to_date=get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'to_date',$pw_to_date);
	}
	$this->pw_to_date_dashboard=$pw_to_date;
	$pw_to_date=substr($pw_to_date,0,4);
}else{
	if(get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$pw_to_date=get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'to_date',$pw_to_date);
	}
	$pw_to_date_dashboard=explode(" ",$pw_to_date);
	$this->pw_to_date_dashboard=$pw_to_date_dashboard[0];

	$pw_to_date=substr($pw_to_date,0,4);
}


?>

<div class="wrap">
    <div class="row">

        <div class="col-xs-12 col-md-12">
            <div class="awr-box">
			    <?php
			    $table_name='dashboard_report';
			    $pw_rpt_main_class->search_form_html($table_name);
			    ?>
            </div>
        </div>

        <div class="col-xs-12 col-md-12">
            <div class="awr-box">


                <div id="target">
                    <?php
                    $table_name='dashboard_report';
                    $pw_rpt_main_class->table_html($table_name);
                    ?>
                </div>
            </div>
        </div>


		<?php
		if ($this->dashboard($this->pw_plugin_status)){
			?>


			<?php
			$disbale_chart=get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_chart',"off");
			if($this->get_dashboard_capability('charts') && $disbale_chart=='off'){
				?>
                <!--CHARTS/TABS-->
                <div class="col-xs-12 col-md-12">
                    <div class="awr-box ">
                    <div class="tabs tabsA tabs-style-underline">
                        <nav>
                            <ul class="tab-uls">

								<?php
								$charts_config = array(
									array('cap' => 'sale_by_months_chart','section' => 'section-bar-1','target' => 'pwr_chartdiv_month','label' => esc_html__('Sales By Months',__PW_REPORT_WCREPORT_TEXTDOMAIN__),'icon' => 'fa fa-cogs'),
									array('cap' => 'sale_by_days_chart','section' => 'section-bar-2','target' => 'pwr_chartdiv_day','label' => esc_html__('Sales By Days',__PW_REPORT_WCREPORT_TEXTDOMAIN__),'icon' => 'fa fa-cogs'),
									array('cap' => '3d_month_chart_chart','section' => 'section-bar-3','target' => 'pwr_chartdiv_multiple','label' => esc_html__('3D Month Chart',__PW_REPORT_WCREPORT_TEXTDOMAIN__),'icon' => 'fa fa-cogs'),
									array('cap' => 'top_products_chart','section' => 'section-bar-4','target' => 'pwr_chartdiv_pie','label' => esc_html__('Top Products',__PW_REPORT_WCREPORT_TEXTDOMAIN__),'icon' => 'fa fa-columns'),
								);
								foreach($charts_config as $chart){
									if($this->get_dashboard_capability($chart['cap'])){
										?>
										<li><a href="#<?php echo $chart['section'];?>" class="" data-target="<?php echo $chart['target'];?>"> <i class="<?php echo $chart['icon'];?>"></i><span><?php echo $chart['label']; ?></span></a></li>
										<?php
									}
								}
								?>
                            </ul>
                        </nav>

                        <div class="awr-theme-chart">
                            <ul>
                                <li  class="awr-theme-chart-title">
                                    <span class=""><?php echo  esc_html__('Click to change theme',__PW_REPORT_WCREPORT_TEXTDOMAIN__); ?>:&nbsp;&nbsp;</span>
                                </li>

                                <li class="pw_switch_chart_theme " data-theme="light">
                                    <img width="36" height="35" src="<?php echo __PW_REPORT_WCREPORT_URL__?>/assets/images/theme_light2.png" alt="theme_light">
                                </li>

                                <li class="pw_switch_chart_theme " data-theme="dark">
                                    <img width="36" height="35" src="<?php echo __PW_REPORT_WCREPORT_URL__?>/assets/images/theme_dark2.png" alt="theme_dark">
                                </li>

                                <li class="pw_switch_chart_theme " data-theme="patterns">
                                    <img width="36" height="35" src="<?php echo __PW_REPORT_WCREPORT_URL__?>/assets/images/theme_pattern2.png" alt="theme_patterns">
                                </li>

                                <li class="pw_switch_chart_theme " data-theme="none">
                                    <img width="36" height="35" src="<?php echo __PW_REPORT_WCREPORT_URL__?>/assets/images/theme_none.png" alt="theme_none">
                                </li>

                            </ul>
                        </div>

                        <div class="content-wrap">

							<?php
							$chart_heights=array('pwr_chartdiv_month'=>'450px','pwr_chartdiv_day'=>'450px','pwr_chartdiv_multiple'=>'550px','pwr_chartdiv_pie'=>'450px');
							foreach($charts_config as $chart){
								if($this->get_dashboard_capability($chart['cap'])){
									$height= isset($chart_heights[$chart['target']]) ? $chart_heights[$chart['target']] : '450px';
									?>
									<section id="<?php echo $chart['section']; ?>">
										<div id="<?php echo $chart['target']; ?>" style="width: 100%; height: <?php echo $height; ?>;"></div>
									</section>
									<?php
								}
							}
							?>

                        </div>
                    </div>

                </div>
                </div>
			<?php } ?>


			<?php
			$disbale_map=get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_map',"off");
			if($this->get_dashboard_capability('map') && $disbale_map=='off'){
				?>
                <!--MAP--><!---->
                <div class="col-xs-12 col-md-12">
                    <div class="awr-box">
                        <div class="awr-title">
                            <h3><i class="fa fa-desktop"></i><?php _e('Map',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?></h3>
                            <div class="awr-title-icons">
                                <div class="awr-title-icon awr-toggle-icon"><i class="fa fa-arrow-up"></i></div>
                                <div class="awr-title-icon awr-setting-icon"><i class="fa fa-cog"></i></div>
                                <div class="awr-title-icon awr-close-icon"><i class="fa fa-times"></i></div>
                            </div>
                        </div>

                        <div class="awr-box-content container5 pw-map-content">
                            <div class="map">
                                <span>Alternative content for the map</span>
                            </div>


                            <div class="rightPanel">
                                <h2><?php echo  esc_html__('Select a year',__PW_REPORT_WCREPORT_TEXTDOMAIN__); ?></h2>
                                <div class="knobContainer">
                                    <input class="knob" data-width="80" data-height="80" data-min="<?php echo $first_date;?>" data-max="<?php echo $pw_to_date; ?>" data-cursor=true data-fgColor="#454545" data-thickness=.45 value="<?php echo $first_date;?>" data-bgColor="#c7e8ff" />
                                </div>
                                <div class="areaLegend">
                                    <span>Alternative content for the legend</span>
                                </div>
                                <div class="plotLegend"></div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
			?>



			<?php
			if($this->get_dashboard_capability('datagrids')){
				?>
                <!--DATA GRID-->


				<?php
				$grids = array(
					array('cap' => 'monthly_summary', 'table' => 'monthly_summary', 'col' => 'col-xs-12 col-md-12', 'clear_after' => false),
					array('cap' => 'order_summary', 'table' => 'order_summary', 'col' => 'col-xs-12 col-md-6', 'clear_after' => false),
					array('cap' => 'sale_order_status', 'table' => 'sale_order_status', 'col' => 'col-xs-12 col-md-6', 'clear_after' => true),
					array('cap' => 'top_products', 'table' => 'top_5_products', 'col' => 'col-xs-12 col-md-6', 'clear_after' => false),
					array('cap' => 'top_category', 'table' => 'top_5_category', 'col' => 'col-xs-12 col-md-6', 'clear_after' => true),
					array('cap' => 'top_billing_country', 'table' => 'top_5_country', 'col' => 'col-xs-12 col-md-6', 'clear_after' => false),
					array('cap' => 'top_biling_state', 'table' => 'top_5_state', 'col' => 'col-xs-12 col-md-6', 'clear_after' => true),
					array('cap' => 'recent_orders', 'table' => 'recent_5_order', 'col' => 'col-xs-12 col-md-12', 'clear_after' => false),
					array('cap' => 'top_customers', 'table' => 'top_5_customer', 'col' => 'col-xs-12 col-md-12', 'clear_after' => false),
					array('cap' => 'top_coupon', 'table' => 'top_5_coupon', 'col' => 'col-xs-12 col-md-6', 'clear_after' => false),
					array('cap' => 'top_payment_gateway', 'table' => 'top_5_gateway', 'col' => 'col-xs-12 col-md-6', 'clear_after' => false),
				);

				foreach($grids as $grid){
					if($this->get_dashboard_capability($grid['cap'])){
						?>
						<div class="<?php echo $grid['col']; ?>">
							<?php
							$table_name=$grid['table'];
							$pw_rpt_main_class->table_html($table_name);
							?>
						</div>
						<?php if($grid['clear_after']){ ?>
						<div class="awr-clearboth"></div>
						<?php } ?>
						<?php
					}
				}
				?>


			<?php
		}//END DASHBOARD FUNCITON CHECK
		?>

    </div><!--row -->
</div>

<?php

//echo $first_date.' - '.$pw_to_date;

$country_values=array();
$areas=array();

$all_country=array();
for($year=$first_date;$year<=$pw_to_date;$year++){
	$year=intval($year);
	$start_date=$year.'-01-01';
	$end_date=$year.'-12-30';
	$Country_sql=$wpdb->prepare(
		"SELECT SUM(pw_postmeta1.meta_value) AS Total ,pw_postmeta2.meta_value AS BillingCountry ,Count(*) AS OrderCount FROM {$wpdb->prefix}posts as pw_posts LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta1 ON pw_postmeta1.post_id=pw_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta2 ON pw_postmeta2.post_id=pw_posts.ID WHERE pw_posts.post_type\t=\t'shop_order' AND pw_postmeta1.meta_key =\t'_order_total' AND pw_postmeta2.meta_key\t=\t'_billing_country' AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(pw_posts.post_date) BETWEEN %s AND %s AND pw_posts.post_status NOT IN ('trash') GROUP BY pw_postmeta2.meta_value Order By Total DESC",
		$start_date,$end_date
	);

	//echo($Country_sql);

	$results= $wpdb->get_results($Country_sql);

	foreach($results as $items){

		if($items->BillingCountry=='')
			continue;

		$all_country[]=$items->BillingCountry;
	}
}

for($year=$first_date;$year<=$pw_to_date;$year++){
	$year=intval($year);
	$start_date=$year.'-01-01';
	$end_date=$year.'-12-30';
	$Country_sql=$wpdb->prepare(
		"SELECT SUM(pw_postmeta1.meta_value) AS Total ,pw_postmeta2.meta_value AS BillingCountry ,Count(*) AS OrderCount FROM {$wpdb->prefix}posts as pw_posts LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta1 ON pw_postmeta1.post_id=pw_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta2 ON pw_postmeta2.post_id=pw_posts.ID WHERE pw_posts.post_type\t=\t'shop_order' AND pw_postmeta1.meta_key =\t'_order_total' AND pw_postmeta2.meta_key\t=\t'_billing_country' AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(pw_posts.post_date) BETWEEN %s AND %s AND pw_posts.post_status NOT IN ('trash') GROUP BY pw_postmeta2.meta_value Order By Total DESC",
		$start_date,$end_date
	);

	$results= $wpdb->get_results($Country_sql);

	$this_year_country=array();

	foreach($results as $items){

		if($items->BillingCountry=='')
			continue;

		$country      	= $this->pw_get_woo_countries();//Added 20150225
		$pw_table_value = isset($country->countries[$items->BillingCountry]) ? $country->countries[$items->BillingCountry]: $items->BillingCountry;


		$country_values[]=round($items->Total,0);
		$areas[$year][$items->BillingCountry]= array(
			"value" => $items->Total,
			"href" => "http://en.wikipedia.org/w/index.php?search=".$pw_table_value,
			"tooltip" => array(
				"content" => "<span style=\"font-weight:bold;\">".$pw_table_value."</span><br /><span style=\"font-weight:bold;\">".  $this->price($items->Total) . " # " .$items->OrderCount."</span><br />Total : ".$items->Total
			));
		$this_year_country[]=$items->BillingCountry;
	}



	if(is_array($this_year_country) && is_array($all_country) && count($all_country)>0 && count($this_year_country)>0)
	{
		$diff_array=array_diff($all_country,$this_year_country);

		foreach($diff_array as $diff_country){
			$country      	= $this->pw_get_woo_countries();
			$pw_table_value = isset($country->countries[$diff_country]) ? $country->countries[$diff_country]: $diff_country;



			//$country_values[]=0;
			$areas[$year][$diff_country]= array(
				"value" => "0",
				"href" => "http://en.wikipedia.org/w/index.php?search=".$pw_table_value,
				"tooltip" => array(
					"content" => "<span style=\"font-weight:bold;\">".$pw_table_value."</span><br /><span style=\"font-weight:bold;\">".  $this->price(0) . " #0</span><br />Total : 0"
				));
		}
	}
}

$plots=array();
$state_values=array();
$state=array();

$all_states=array();

//GET ALL STATES
for($year=$first_date;$year<=$pw_to_date;$year++){
	$year=intval($year);
	$start_date=$year.'-01-01';
	$end_date=$year.'-12-01';
	$State_sql=$wpdb->prepare(
		"SELECT SUM(pw_postmeta1.meta_value) AS Total ,pw_postmeta2.meta_value AS billing_state ,pw_postmeta3.meta_value AS billing_country ,Count(*) AS OrderCount FROM {$wpdb->prefix}posts as pw_posts LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta1 ON pw_postmeta1.post_id=pw_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta2 ON pw_postmeta2.post_id=pw_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta3 ON pw_postmeta3.post_id=pw_posts.ID WHERE pw_posts.post_type =\t'shop_order' AND pw_postmeta1.meta_key =\t'_order_total' AND pw_postmeta2.meta_key\t=\t'_billing_state' AND pw_postmeta3.meta_key\t= '_billing_country' AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(pw_posts.post_date) BETWEEN %s AND %s AND pw_posts.post_status NOT IN ('trash') GROUP BY pw_postmeta2.meta_value Order By Total DESC",
		$start_date,$end_date
	);

	$results= $wpdb->get_results($State_sql);

	foreach($results as $items){

		if($items->billing_state=='' || $items->billing_country=='')
			continue;

		$pw_table_value=$this->pw_get_woo_bsn($items->billing_country,$items->billing_state);

		//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
		$this_state=explode("(",$pw_table_value);
		$this_state=str_replace("-","_",$this_state[0]);
		$pw_table_value=$this_state;

		$all_states[]=$pw_table_value;
	}
}

//print_r($all_states);

for($year=$first_date;$year<=$pw_to_date;$year++){
	$year=intval($year);
	$start_date=$year.'-01-01';
	$end_date=$year.'-12-01';
	$State_sql=$wpdb->prepare(
		"SELECT SUM(pw_postmeta1.meta_value) AS Total ,pw_postmeta2.meta_value AS billing_state ,pw_postmeta3.meta_value AS billing_country ,Count(*) AS OrderCount FROM {$wpdb->prefix}posts as pw_posts LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta1 ON pw_postmeta1.post_id=pw_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta2 ON pw_postmeta2.post_id=pw_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as pw_postmeta3 ON pw_postmeta3.post_id=pw_posts.ID WHERE pw_posts.post_type =\t'shop_order' AND pw_postmeta1.meta_key =\t'_order_total' AND pw_postmeta2.meta_key\t=\t'_billing_state' AND pw_postmeta3.meta_key\t= '_billing_country' AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(pw_posts.post_date) BETWEEN %s AND %s AND pw_posts.post_status NOT IN ('trash') GROUP BY pw_postmeta2.meta_value Order By Total DESC",
		$start_date,$end_date
	);

	$results= $wpdb->get_results($State_sql);

	$this_year_states=array();

	foreach($results as $items){

		if($items->billing_state=='' || $items->billing_country=='')
			continue;



		$pw_table_value=$this->pw_get_woo_bsn($items->billing_country,$items->billing_state);
		$pw_table_values=strtolower(str_replace(" ","",$pw_table_value));

		//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
		$this_state=explode("(",$pw_table_values);
		$this_state=str_replace("-","_",$this_state[0]);
		$pw_table_values=$this_state;

		$state[]=$pw_table_values;
		$state_values[]=round($items->Total,0);
		$plots[$year][$pw_table_values]= array(
			"value" => $items->Total,
			"tooltip" => array(
				"content" => "<span style=\"font-weight:bold;\">".$pw_table_value."</span><br /><span style=\"font-weight:bold;\">".  $this->price($items->Total) . " # " .$items->OrderCount."</span><br />Total : ".$items->Total
			));

		//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
		$this_state=explode("(",$pw_table_value);
		$this_state=str_replace("-","_",$this_state[0]);
		$pw_table_value=$this_state;

		$this_year_states[]=$pw_table_value;
	}

	if(is_array($this_year_states) && is_array($all_states)  && count($all_states)>0 && count($this_year_states)>0)
	{
		$diff_array=array_diff($all_states,$this_year_states);
		foreach($diff_array as $diff_state){

			//$state_values[]=0;

			$pw_table_values=strtolower(str_replace(" ","",$diff_state));


			//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
			$this_state=explode("(",$pw_table_values);
			$this_state=str_replace("-","_",$this_state[0]);
			$pw_table_values=$this_state;

			$state[]=$pw_table_values;
			$plots[$year][$pw_table_values]= array(
				"value" => "0",
				"tooltip" => array(
					"content" => "<span style=\"font-weight:bold;\">".$diff_state."</span><br /><span style=\"font-weight:bold;\">".  $this->price(0) . " # 0</span><br />Total : 0"
				));
		}
	}
}



//print_r($plots);
$map_date=array();

if($first_date!=$pw_to_date){
	for($year=$first_date;$year<=$pw_to_date;$year++){

		$a_years=isset($areas[$year]) ? $areas[$year]: "";
		$p_years=isset($plots[$year]) ? $plots[$year]: "";

		$map_date[$year]=array("areas" =>$a_years,"plots" =>$p_years);
	}
}else{
	$year=$first_date;
	$a_years=isset($areas[$year]) ? $areas[$year]: "";
	$p_years=isset($plots[$year]) ? $plots[$year]: "";

	$map_date[$year]=array("areas" =>$a_years,"plots" =>$p_years);
}

//print_r($map_date);


/////////SESARCH RANGES//////////
$first_limit_country=$two_limit_country=$first_limit_state=$two_limit_state=false;
if(is_array($country_values) && count($country_values)>0)
{
	sort($country_values);
	$max_counrty= max($country_values);
	$math=round($max_counrty/3,0);
	$first_limit_country=$math;
	$two_limit_country=$math+$math;
}

if(is_array($state_values) && count($state_values)>0)
{
	sort($state_values);
	$max_state= max($state_values);
	$math=round($max_state/3,0);
	$first_limit_state=$math;
	$two_limit_state=$math+$math;
}

/*

//--------------

*/


//////////////////


//print_r($map_date);

$arr=$map_date;
?>

<?php
$disbale_map=get_option(__PW_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_map',"off");
if($this->get_dashboard_capability('map') && $disbale_map=='off'){
	?>
    <script type="text/javascript">

        var data = <?php echo (json_encode($arr)=='' ? "''":json_encode($arr)) ; ?>;

        jQuery( document ).ready(function( $ ) {

            var myarray =[];
            var myJSON = new Object();
			<?php
			//die(print_r($state));
			foreach((array)$state as $state_name){
			if($state_name=='' || is_numeric($state_name))	continue;
			?>

            var geocoder = new google.maps.Geocoder();
            var address = "<?php echo $state_name?>";

            geocoder.geocode( { 'address': address}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    //confirm("<?php echo $state_name?>"+latitude+" - "+longitude);

                    var item = {
                        "latitude": latitude,
                        "longitude": longitude,
                        "text": {
                            "position": "left",
                            "content": "",
                        }
                    };

                    myJSON.<?php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $state_name)));?>=item;
                }

            });

			<?php
			}
			?>

            setTimeout(function(){

                // Default plots params
                var plots = myJSON;


                $(".knob").knob({
                    release : function (value) {
                        $(".container5").trigger('update', [data[value], {}, {}, {animDuration : 300}]);
                    }
                });

                // Mapael initialisation
                $world = $(".container5");
                $world.mapael({
                    map : {
                        name : "world_countries",
                        defaultArea: {
                            attrs : {
                                fill: "#eee",
                                stroke : "#aaa",
                                "stroke-width" : 0.3
                            }
                        },
                        defaultPlot: {
                            text : {
                                attrs: {
                                    fill:"#333"
                                },
                                attrsHover: {
                                    fill:"#fff",
                                    "font-weight":"bold"
                                }
                            }
                        }
                        , zoom : {
                            enabled : true
                            //,mousewheel :false
                            , step : 0.25
                            , maxLevel : 20
                        }
                    },
                    legend : {
						<?php
						global  $woocommerce;

						if($first_limit_country){
						?>
                        area : {
                            display : true,
                            title :"<?php _e('Country Orders Amount',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?>",
                            marginBottom : 7,
                            slices : [
                                {
                                    max :<?php echo $first_limit_country; ?>,
                                    attrs : {
                                        fill : "#6ECBD4"
                                    },
                                    label :'<?php _e('Less than',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?> <?php echo  ($first_limit_country).' '.get_woocommerce_currency(); ?>'
                                },
                                {
                                    min :<?php echo $first_limit_country; ?>,
                                    max :<?php echo $two_limit_country; ?>,
                                    attrs : {
                                        fill : "#3EC7D4"
                                    },
                                    label :'> <?php echo ($first_limit_country).' '.get_woocommerce_currency(); ?> <?php _e('and',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?> < <?php echo ($two_limit_country).' '.get_woocommerce_currency(); ?>'
                                },
                                {
                                    min :<?php echo $two_limit_country; ?>,
                                    attrs : {
                                        fill : "#01565E"
                                    },
                                    label :'<?php _e('More than',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?> <?php echo ($two_limit_country).' '.get_woocommerce_currency(); ?>'
                                }
                            ]
                        },
						<?php
						}
						if($first_limit_state){
						?>
                        plot :{
                            display : true,
                            title: "<?php _e('State Orders Amount',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?>",
                            marginBottom : 6,
                            slices : [
                                {
                                    type :"circle",
                                    max :<?php echo $first_limit_state; ?>,
                                    attrs : {
                                        fill : "#FD4851",
                                        "stroke-width" : 1
                                    },
                                    attrsHover :{
                                        transform : "s1.5",
                                        "stroke-width" : 1
                                    },
                                    label :"<?php _e('Less than',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?> <?php echo $first_limit_state.' '.get_woocommerce_currency();?>",
                                    size : 10
                                },
                                {
                                    type :"circle",
                                    min :<?php echo $first_limit_state; ?>,
                                    max :<?php echo $two_limit_state; ?>,
                                    attrs : {
                                        fill : "#FD4851",
                                        "stroke-width" : 1
                                    },
                                    attrsHover :{
                                        transform : "s1.5",
                                        "stroke-width" : 1
                                    },
                                    label :"> <?php echo $first_limit_state.' '.get_woocommerce_currency().' '; ?> <?php _e('and',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?> < <?php echo $two_limit_state.' '.get_woocommerce_currency(); ?>",
                                    size : 20
                                },
                                {
                                    type :"circle",
                                    min :<?php echo $two_limit_state; ?>,
                                    attrs : {
                                        fill : "#FD4851",
                                        "stroke-width" : 1
                                    },
                                    attrsHover :{
                                        transform : "s1.5",
                                        "stroke-width" : 1
                                    },
                                    label :"<?php _e('More than',__PW_REPORT_WCREPORT_TEXTDOMAIN__);?> <?php echo ' '.$two_limit_state.' '.get_woocommerce_currency(); ?>",
                                    size : 30
                                }
                            ]
                        }
						<?php
						}
						?>
                    },
                    plots : $.extend(true, {}, data[<?php echo $first_date;?>]['plots'], plots),
                    areas: data[<?php echo $first_date;?>]['areas']
                });

            },2000);

        });
    </script>
	<?php
	} // Close if from line 518
	} // Close if ($this->dashboard($this->pw_plugin_status)) from line 98
?>

<script>
    jQuery( document ).ready(function( $ ) {



        var toggle=true;
        $(".awr-news-read-oldest").on("click",function(){
            if(toggle){
                $(".awr-news-read-oldest").html("<?php echo esc_html__('Hide Oldest News !',__PW_REPORT_WCREPORT_TEXTDOMAIN__)?>");
            }else
            {
                $(".awr-news-read-oldest").html("<?php echo esc_html__('Show Oldest News !',__PW_REPORT_WCREPORT_TEXTDOMAIN__)?>");
            }

            $(".awr-news-oldest").toggle("slideUp");

            toggle=!toggle;
        });


        [].slice.call( document.querySelectorAll( ".tabsA" ) ).forEach( function( el ) {
            new CBPFWTabs( el );
        });

        [].slice.call( document.querySelectorAll( ".tabsB" ) ).forEach( function( el ) {
            new CBPFWTabs( el );
        });


    });
</script>
