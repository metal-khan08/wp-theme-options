Skip to content
Search or jump to…
Pull requests
Issues
Marketplace
Explore
 
@metal-khan08 
metal-khan08
/
Test-Plugin
Public
Code
Issues
Pull requests
Actions
Projects
Wiki
Security
Insights
Settings
Test-Plugin/admin/class-jobs-board-admin.php /
@metal-khan08
metal-khan08 updated the settings page and other
Latest commit dd51d75 on Jan 20
 History
 1 contributor
781 lines (704 sloc)  25.3 KB

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       def.com
 * @since      1.0.0
 *
 * @package    Jobs_Board
 * @subpackage Jobs_Board/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jobs_Board
 * @subpackage Jobs_Board/admin
 * @author     Talha <talha@wpminds.com>
 */
class Jobs_Board_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jobs_Board_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jobs_Board_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$stylesheet_valid_pages=array('jobs-board-settings','settings-page-2', 'application-settings');
		$page=isset($_REQUEST['page']) ? $_REQUEST['page'] :"";
		if(in_array($page, $stylesheet_valid_pages)){
		wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jobs-board-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jobs_Board_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jobs_Board_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jobs-board-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script('jquery-form');

		$javascript_valid_pages=array('create-cpt','cpt-settings', 'application-settings');
		$page=isset($_REQUEST['page']) ? $_REQUEST['page'] :"";
		if(in_array($page, $javascript_valid_pages)){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

		}
	}

	/**
	 * call back function for jobs board custom post type.
	 *
	 * @since    1.0.0
	 */

	function create_jobsboard_cpt() {
		$labels = array(
			  'name' 		 =>'Jobs Board',
			  'add_new_item' =>'New Job',
			  'edit_item' 	 =>'Edit Job',
			  'all_items'	 => 'All Jobs',
			  'Singular_name'=> 'Job'
		);
		$args = array(
			'label' 		=> __( 'jobs_board', 'textdomain' ),
			'description' 	=> __( 'A detail of the jobs available', 'textdomain' ),
			'labels' 		=> $labels,
			'menu_icon' 	=> 'dashicons-groups',
			'supports' 		=> array('title'),
			'taxonomies' 	=> array(),
			'public' 		=> true,
			'show_ui' 		=> true,
			'show_in_menu' 	=> true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'has_archive' 	=> true,
			'hierarchical' 	=> false,
			'show_in_rest' 	=> true,
			'capability_type' 	=> 'post',
		);
		register_post_type( 'jobs', $args );
	}

	/**
	 * call back function for jobs board meta boxes.
	 *
	 * @since    1.0.0
	 */

	function location_metabox(){
		add_meta_box(
			'pdetails',
			'Job Details',
			 array($this,'location_meta_input_box'),
			'jobs',
			'normal',
			'high'
		);
	}

	/**
	 * call back function for jobs board meta boxes tempelate.
	 *
	 * @since    1.0.0
	 */

	function location_meta_input_box($post){ 
		$jobpostId= $post->ID;
			
		$isOpen =get_post_meta($jobpostId, 'vacancy', true );
		?>
			<!----- this is html to show fields of meta box ----->
			<ul>
				<h3>Location</h3>
				<input type="text" name="meta_job_location" id="meta_job_location" value="<?php echo get_post_meta($jobpostId, 'meta_job_location', true ); ?>"/>
				<h3>Salary</h3>
				<input type="range" min="10000" max="100000" name="meta_number" id="meta_number" value="<?php echo get_post_meta($jobpostId, 'meta_number', true ); ?>"/>
				<h3>Timings</h3>
				<input type="text" name="meta_timings" id="meta_timings" value="<?php echo $timval=get_post_meta($jobpostId, 'meta_timings', true ); ?>"/>
				<h3>Benefits</h3>
				<input type="text" name="custom_benefits" id="custom_benefits" value="<?php echo get_post_meta($jobpostId, 'custom_benefits', true ); ?>"/>
				<h3>Have Vacancy?</h3>
				<input type="checkbox" name="vacancy"<?php echo ($isOpen=='on') ? "checked":""; ?> id="vacancy">
				
			</ul>
			<?php
	}

	/**
	 * call back function for updating the meta fields for the jobsboard.
	 *
	 * @since    1.0.0
	 */

	function pdetails_save($post_id){
		//saving the meta data into individual variables 
		$jobLocation=isset($_POST["meta_job_location"]) ? $_POST["meta_job_location"] : 'Enter Location';
		$jobSalary= isset($_POST["meta_number"]) ? $_POST["meta_number"] : 'Enter Salary';
		$jobTimings=isset($_POST["meta_timings"]) ? $_POST["meta_timings"] : 'Enter Timings';
		$jobBenefits=isset($_POST["custom_benefits"]) ? $_POST["custom_benefits"] : 'No benefits';
		$vacancy=isset($_POST["vacancy"]) ? $_POST["vacancy"] : '';
		//updating the data into the database using the above captured values
		update_post_meta($post_id, "meta_job_location", $jobLocation);
		update_post_meta($post_id, "meta_number", $jobSalary);
		update_post_meta($post_id, "meta_timings", $jobTimings);
		update_post_meta($post_id, "custom_benefits", $jobBenefits);
		update_post_meta($post_id, "vacancy", $vacancy);
		}

	/**
	 * call back function for jobs category taxonomy.
	 *
	 * @since    1.0.0
	 */
	function job_boards_taxonomy(){
		$labels = array(
			'name' =>  'Job Category',
	);
		// Now register the non-hierarchical taxonomy like tag
		  register_taxonomy('jobs','jobs',array(
			'hierarchical'		 => false,
			'labels' 			 => $labels,
			'show_ui' 			 => true,
			'show_in_rest' 		 => true,
			'show_admin_column'  => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' 		 => true,
			'rewrite' => array( 'slug' => 'topic' ),
		  ));
	}

	/**
	 * call back function for application CPT.
	 *
	 * @since    1.0.0
	 */

	function application_custom_post_type(){
		$applabels = array(
			  'name' 		 =>'Application',
			  'add_new_item' =>'New Application',
			  'edit_item' 	 =>'Edit Application',
			  'all_items'	 => 'All Applications',
			  'Singular_name'=> 'Application'
		);
		$appargs = array(
			'label' 		=> __( 'Application', 'textdomain' ),
			'labels' 		=> $applabels,
			'menu_icon' 	=> 'dashicons-portfolio',
			'supports' 		=> array('title', ),
			'public' 		=> true,
			'show_ui' 		=> true,
			'show_in_menu'	=> true,
			'menu_position'	=> 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'has_archive' 	=> true,
			'hierarchical' 	=> false,
			'show_in_rest' 	=> true,
			'capability_type' 	=> 'post'
		);
		register_post_type( 'application', $appargs );
	}

	/**
	 * call back function for application status taxonomy.
	 *
	 * @since    1.0.0
	 */

	 function application_taxonomy(){
		  $labels = array(
			'name'              => _x( 'Application Status', 'taxonomy general name' ),
			'singular_name'     => _x( 'status', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Application Status' ),
			'all_items'         => __( 'All Application Status' ),
			'edit_item'         => __( 'Edit status' ),
			'update_item'       => __( 'Update status' ),
			'add_new_item'      => __( 'Add New status' ),
			'new_item_name'     => __( 'New status Name' ),
			'menu_name'         => __( 'status' ),
		);
		$args   = array(
			'hierarchical'      => true, // make it hierarchical (like categories)
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'status' ],
		);
		register_taxonomy( 'application_status', [ 'application' ], $args );
	}
	 
	/**
	 * call back function for application metaboxes to show data.
	 *
	 * @since    1.0.0
	 */
	function application_metabox(){
		add_meta_box(
			'appdetails',
			'Application Details',
			 array($this,'application_meta_input_box'),
			'application',
			'normal',
			'default'
		);
	}

	/**
	 * call back function for application metaboxes template.
	 *
	 * @since    1.0.0
	 */

	function application_meta_input_box($post){ 
		//fetch the value from the database and saved in to variables
		$postId =$post->ID;
		$fnameval=get_post_meta($postId, 'fname', true );
		$snameval=get_post_meta( $postId, 'sname', true );
		$bdateval=get_post_meta( $postId, 'birthdate', true );
		$emailval=get_post_meta( $postId, 'email', true );
		$pnumberval=get_post_meta( $postId, 'pnumber', true );
		$caddresslval=get_post_meta( $postId, 'caddress' , true);
		$jobname=get_post_meta( $postId, 'jobname', true );
		$personResume=get_post_meta( $postId, 'resume', true);
		?>
		<!-- this the fields of meta box -->
		<ul>
			<label for="name">Full Name</label><br>
			<input type="text" name="fname" id="name" value="<?php echo $fnameval; ?>" placeholder="First name" /><input type="text" name="sname" id="name" value="<?php echo $snameval; ?>" placeholder="second name"/><br>
			<label for="birthdate">Birth date</label><br>
			<input type="date" id="birthdate" name="birthdate" value=""/><br>
			<label for="email">Email Address</label><br>
			<input type="text" name="email" id="email" value="<?php echo $emailval; ?>"/><br>
			<label for="pnumber">Phone Number</label><br>
			<input type="text" name="pnumber" id="pnumber" value="<?php echo $pnumberval; ?>"/><br>
			<label for="caddress">Complete Address</label><br>
			<input type="text" name="caddress" id="caddress" value="<?php echo $caddresslval; ?>"/><br>
			the name of the job applied is <strong ><?php echo $jobname; ?></strong>
		</ul>
		<?php
	  $resumeUrl= $personResume['url']; ?>
	  click to download resume <button><a download="<?php echo $fnameval; ?> resume" href="<?php echo $resumeUrl; ?>">Download Resume</a></button>
<?php }

	/**
	 * call back function for application columns.
	 *
	 * @since    1.0.0
	 */

	function application_post_type_columns($columns){

        return array(
			'cb'			  => __( '<input type="checkbox" />' ),
            'title'           => __( 'Title', 'application' ),
            'author'          => __( 'Author', 'application' ),
            'status'       	  => __( 'Application Status','Application' ),
            'job_name'        => __( 'Job Name','Application' ),
			'date'            => __( 'Date','Application'  )
        );		
	}
	/**
	 * call back function for application columns filing data.
	 *
	 * @since    1.0.0
	 */
	function application_fill_post_type_columns( $column, $post_id){

		switch ( $column ) {
            case 'author':
                    echo get_the_author( $post_id ) ;
                break;
            case 'status':
				$terms = wp_get_object_terms( $post_id, 'application_status');
				$output ='';
            	foreach ( $terms as $term ) {
                $output=$term->name; 
        } 
				echo $output;
                break;
            case 'job_name':
				echo get_post_meta( $post_id, 'jobname', true );
                break;
		}
	}

	/**
	 * function when the status is changed the email is sent to the user .
	 *
	 * @since    1.0.0
	 */

	 function send_mail_when_status_changed($data, $postarr, $unsanitized_postarr){
		if($data['post_type']!='application'){ //checking if the post type is application
			return $data;//return if the post type is not appplication
		}
		$post_ID=!empty($postarr['post_id']) ? $postarr['post_id'] :'';
		//getting the updated taxonomy ID
		$updated_status_ID=!empty($postarr['tax_input']['application_status'][1]) ? $postarr['tax_input']['application_status'][1] : '';
		//getting the name of the updated status
		$updated_status_term=get_term($updated_status_ID );
		$updated_status_name=!empty($updated_status_term->name) ?$updated_status_term->name: '';
		$updated_status_name=strtolower($updated_status_name);
		//getting the id of the post author
		$post_user_id=$data['post_author'];
		//getting the email of the post author
		$user_email = get_the_author_meta( 'user_email',$post_user_id);
		//fethcing the previous taxonomy status ID from the database
        $terms = wp_get_object_terms( $post_ID, 'application_status');
		$old_status_ID='';
		foreach ( $terms as $term ) {
			$old_status_ID=$term->term_id;
		} 
		if($old_status_ID!=$updated_status_ID){
			if($updated_status_name=='accepted'){
				// Email subject"
				$subject = 'Your Application Status';

				// Email body
				$message = 'Your Application for the Job was accepted ';

				wp_mail( $user_email, $subject, $message );
			}else if($updated_status_name=='rejected'){
				// Email subject, "New {post_type_label}"
				$subject = 'Your Application Status';

				// Email body
				$message = 'Your Application for the Job was Rejected ';

				wp_mail( $user_email, $subject, $message );
			}else if($updated_status_name=='pending'){
				// Email subject, "New {post_type_label}"
				$subject = 'Your Application Status';

				// Email body
				$message = 'Your Application for the Job was Pending ';

				wp_mail( $user_email, $subject, $message );
			}
		}
		return $data;
	}

	/**
	 * call back for adding settings menu for the jobs and application .
	 *
	 * @since    1.0.0
	 */

	function jobs_board_settings_menu(){
		add_submenu_page( 'edit.php?post_type=jobs', 'J-Board Settings', 'J-Board Settings','manage_options', 'jobs-board-settings',array($this ,'jobs_board_menu_callback_fnc')  );
		add_submenu_page( 'edit.php?post_type=application', 'Application Settings', 'Application Settings','manage_options', 'application-settings',array($this ,'application_menu_callback_fnc')  );
	}
	function jobs_board_menu_callback_fnc(){
		require_once 'partials/jobs-board-admin-display.php';//cal back for the jobs board settings page
	}
	function application_menu_callback_fnc(){
		require_once 'partials/application-settings-page.php';//call back for the application settings page
		
	}

	/**
	 * settings section for jobs  Board.
	 *
	 * @since    1.0.0
	 */
	
	function jobs_board_custom_settings(){
		  // Register a new setting for "jobs-board-settings" page.
		  register_setting( 'jobs-board-settings', 'jobs_board_options' );
		  register_setting( 'jobs-board-settings', 'jobs_board_text_settings' );
		  register_setting( 'jobs-board-settings', 'jobs_board_search_settings' );
		  register_setting( 'jobs-board-settings', 'jobs_board_checkbox_settings' );
 
		  // Register a new section in the "jobs-board-settings" page.
		  add_settings_section(
			  'jobs_board_settings_section',
			  __( 'Jobs Board Settings.', 'jobs-board-settings' ), '',
			  'jobs-board-settings'
		  );
	   
		  //settings field to display no of jobs
		  add_settings_field(
			  'number_of_jobs', 
				  __( 'No of Jobs.', 'jobs-board-settings' ),
			  array($this,'jobs_board_number_jobs_settings_field'),
			  'jobs-board-settings',
			  'jobs_board_settings_section',
		  );
		  //settings field to display in place of form when a job vacancy is closed
		  add_settings_field(
			'text_to_display', 
				__( 'Enter Text', 'jobs-board-settings' ),
			array($this,'jobs_board_text_settings_field'),
			'jobs-board-settings',
			'jobs_board_settings_section',
		);
		  //settings field to display Text for search button

		add_settings_field(
			'text_for_search', 
				__( 'Text for search', 'jobs-board-settings' ),
			array($this,'jobs_board_search_settings_field'),
			'jobs-board-settings',
			'jobs_board_settings_section',
		);
		  //settings field to display checkboxes to hide birthdate and current address fields from application form

		add_settings_field(
			'checkbox_to_display', // .
				__( 'checkbox', 'jobs-board-settings' ),
			array($this,'jobs_board_checkbox_settings_field'),
			'jobs-board-settings',
			'jobs_board_settings_section',
		);
	}
	
	//call back for settings field to display no of jobs
	function jobs_board_number_jobs_settings_field($args){
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'jobs_board_options' );
		?>
		<input type="number" min="1" max="10" name="jobs_board_options" id="jobs_board_options" value="<?php echo $options;?>">
		<p>Enter the number of jobs to display on jobs baord(max no 10)</p>
		<?php
	}
	//call back for settings field to display in place of form when a job vacancy is closed
	function jobs_board_text_settings_field(){
		// Get the value of the setting we've registered with register_setting()
		$options2 = get_option( 'jobs_board_text_settings' );
		?>
		<input type="text" name="jobs_board_text_settings" id="jobs_board_text_settings" value="<?php echo $options2;?>">
		<p>Text to display in place of form when a job vacancy is closed</p>
		<?php
	}
	//call back for settings field to display Text for search button
	function jobs_board_search_settings_field(){
		$options3 = get_option( 'jobs_board_search_settings' );
		?>
		<input type="text" name="jobs_board_search_settings" id="jobs_board_search_settings" value="<?php echo $options3;?>">
		<p>Text for search button</p>
		<?php
	}
	//call back for settings field to display checkboxes to hide birthdate and current address fields from application form
	function jobs_board_checkbox_settings_field(){
		$options4 = get_option( 'jobs_board_checkbox_settings' );
		
		?>
		<input type="checkbox" name="jobs_board_checkbox_settings"<?php echo ($options4=='on') ? "checked":""; ?> id="jobs_board_checkbox_settings">
		<p>checkboxes to hide birthdate and current address fields from application form</p><br>
		
		<?php
}

	/**
	 * call back for the ajax request and to create the csv file for application export .
	 *
	 * @since    1.0.0
	 */
function func_export_all_posts() {
        $args = array(
            'post_type' 	 => 'application',
            'post_status' 	 => 'publish',
            'posts_per_page' => -1,
        );

		//query to get the applications
		$application = new WP_Query( $args );
		$path 		   = wp_upload_dir();
		$application_content = array();
		$filename 	   = "/applications.csv";
		$file 		   = fopen( $path['path'].$filename, 'w');

		$exampleStartDate= $_POST['startDate'];//get the start date if set
		$exampleEndDate=$_POST['EndDate'];//get the end date if set
		$examplejobName=$_POST['jobname'];//get the job name if set
		while ( $application->have_posts() ){
			$application->the_post();

			$currentDate= get_the_date('Y-m-d');
			//filter the data through start and ending date
				if($exampleStartDate!=0 and $exampleEndDate!=0){
					if (($currentDate < $exampleStartDate  and $currentDate > $exampleEndDate)or ($currentDate < $exampleStartDate) or ($currentDate > $exampleEndDate)) {
						continue;
					}
				}
				else if($exampleStartDate != 0){
					if($currentDate < $exampleStartDate){
						continue;
					}
				}else if ($exampleEndDate!=0){
					if($currentDate > $exampleEndDate){
						continue;
					}
				}
				//filter the data through the job name
			$post_ID=get_the_ID();
			$job_name=get_post_meta( $post_ID, 'jobname', true );
			if($examplejobName != ''){
			if($examplejobName != $job_name){
				continue;
				}
			}
			//getting the status of the application
				$terms = wp_get_object_terms( $post_ID, 'application_status');
				$status = array();
				foreach ( $terms as $term ) {
				$status[] =$term->name; 
			}
			//geting the post meta
			$Pnumber = get_post_meta( $post_ID, 'pnumber', true );
			$birthDay=get_post_meta( $post_ID, 'birthdate', true );
			$uemailval=get_post_meta( $post_ID, 'email', true );
			$pnumberval=get_post_meta( $post_ID, 'pnumber', true );
			$ucaddresslval=get_post_meta( $post_ID, 'caddress' , true);

			$applicationDate=get_the_date('Y,m,d');

			$post_title=get_the_title();
			//saving the application data into the array
			$application_content[] = array (
				'Full Name' => $post_title,
				'status'	=> implode(",", $status),
				'num'		=> $Pnumber,
				'Job name'	=> $job_name,
				'birthdate'	=> $birthDay,
				'email'		=> $uemailval,
				'caddress'	=> $ucaddresslval,
				'Publish Data'	=> $applicationDate,
			);
		}
		$keys = array_keys( $application_content[0] );
		//creating a csv file on the basis of the array
		fputcsv( $file, $keys );
		foreach ($application_content as $key => $application_info) {
			fputcsv( $file, $application_info );
		}
			fclose( $file );
			$fileUrl = $path['url'].$filename;	
			//rerutn the data in the form of json 
			wp_send_json( $fileUrl);
		die();
	}

	/**
	 * call back function to export jobsboard csv.
	 *
	 * @since    1.0.0
	 */

	function jobs_board_csv(){	
		$args = array(
            'post_type' 	 => 'jobs',
            'post_status' 	 => 'publish',
            'posts_per_page' => -1,
        );
		//meta query to get the jobs
		$application = new WP_Query( $args );
		$path 		   = wp_upload_dir();
		$application_content = array();
		$filename 	   = "/jobs.csv";//file name with the jobs are exported
		$file 		   = fopen( $path['path'].$filename, 'w');
		while ( $application->have_posts() ){ 
			$application->the_post();
				//getting the jobs type
				$post_ID=get_the_ID();
				$terms = wp_get_object_terms( $post_ID, 'jobs');
				$status = array();
				foreach ( $terms as $term ) {
				$status[] =$term->name; 
				$post_URL=get_the_permalink();
				}
				//getting the jobs meta
				$getJobLocation = get_post_meta( $post_ID, 'meta_job_location', true );
				$getJobSalary = get_post_meta( $post_ID, 'meta_number', true );
				$getJobTimings = get_post_meta( $post_ID, 'meta_timings', true );
				$getJobBenefits = get_post_meta( $post_ID, 'custom_benefits', true );
				$post_title=get_the_title();
				//jobs content
				$application_content[] = array (
					'title' => $post_title,
					'URL'		 => $post_URL,
					'category'	 => implode(",", $status),
					'city'		 => $getJobLocation,
					'salary'	 => $getJobSalary,
					'timings'	 => $getJobTimings,
					'benefits'	 => $getJobBenefits
				);
		}
		$keys = array_keys( $application_content[0] );

		fputcsv( $file, $keys );
		foreach ($application_content as $key => $application_info) {
			fputcsv( $file, $application_info );
		}
			fclose( $file );
			$fileUrl = $path['url'].$filename;		
			wp_send_json( $fileUrl);
		die();
	}

	/**
	 * call back for the import ajax of jobs board.
	 *
	 * @since    1.0.0
	 */
	function jobs_board_import_csv(){
		//check if the user uploaded the file 
		if (!file_exists($_FILES['import']['tmp_name']) || !is_uploaded_file($_FILES['import']['tmp_name'])) {
			echo'<div style="margin-left:50px;"><h3>File not uploaded</h3></div>';
			die;
			}
		//check file type and upload file data, if it is not correct exit the function
		$supported_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
		$arr_file_type = wp_check_filetype(basename($_FILES['import']['name']));
		$uploaded_type = $arr_file_type['type'];
		$upload = wp_upload_bits($_FILES['import']['name'], null, file_get_contents($_FILES['import']['tmp_name']));
		$fileurl=$upload['url'];
		$jobsFileName=$upload['file'];

		if(in_array($uploaded_type, $supported_types)) {
			if(isset($upload['error']) && $upload['error'] != 0) {   
				echo '<div style="margin-left:50px;"><h3>there was an error uploading your file</h3></div>';
				die();
			}
				else{
					// Check if file is writable, then open it in 'read only' mode
					$_file = fopen( $fileurl, "r" );
						//  row, column by column, saving all the data
						$post = array();

						// Get first row in CSV, which is of course the headers
						$header = fgetcsv( $_file );
						while ( $row = fgetcsv( $_file ) ) {
							foreach ( $header as $i => $key ) {
								$post[$key] = $row[$i];
							}
							$posts[] =  $post;
						}
						fclose( $_file );

		foreach ( $posts as $post ) {

			// Insert the post into the database
			$post["id"] = wp_insert_post( array(
				"post_title" => $post["title"],
				"post_type" => 'jobs',
				"post_status" => "publish"
			));

			// Update post's custom meta fields
			update_post_meta($post["id"], "meta_job_location", $post["city"]);
			update_post_meta($post["id"], "meta_number", $post["salary"]);
			update_post_meta($post["id"], "meta_timings", $post["timings"]);
			update_post_meta($post["id"], "custom_benefits", $post["benefits"]);
			wp_set_object_terms( $post["id"],$post["category"] , 'jobs' );
		}
		echo '<h3>Jobs imported</h3>';
		unlink($jobsFileName);
				}
		} else {
				echo '<div style="margin-left:50px;"><h3>File Type Not supported</h3></div>';
				die() ;
			}
		die();
	}



}
Footer
© 2022 GitHub, Inc.
Footer navigation
Terms
Privacy
Security
Status
Docs
Contact GitHub
Pricing
API
Training
Blog
About
You have no unread notifications