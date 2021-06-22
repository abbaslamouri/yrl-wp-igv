<?php

/**
 * AXL-WP-Ultimate
 *
 * @package AXL-WP-Ultimate
 * @author Abbas Lamouri
 * @since 1.0.0
 **/

namespace AXL_WP\Includes;

// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');

// Declare main class if it does not exist
if (!class_exists('Dashboard')) {

	class Dashboard {

		public $name = AXL_WP_NAME; // title of the plugin
		public $path = AXL_WP_PATH; // Path to the plugin directory
		public $url = AXL_WP_URL; // URL to the plugin directory
		public $base = AXL_WP_BASE; // represents plugin-dir/plugin-file
		public $version = "1.0.0"; // module version
		public $prefix = AXL_WP_PREFIX; // prefix (axl_wp)
		public $plugin = AXL_WP_PLUGIN; // plugin (axl-wp)
		public $plugin_options = "";

		public $namespace; // Rest API namespace
		public $rest_base; // Rest API base name
		//public $module; //
		public $rest_version = "1"; // Rest API version

		/**
		 *Magic constructor.  Gets called when an object is instantiated
			*/
		public function __construct() {

      // Start session
      if (!session_id()) session_start();
			
      // Initialize defaults
      $this->plugin_options = array(
				'version' => $this->version,
			);
			$this->namespace = "{$this->plugin}/v{$this->rest_version}";
      $this->rest_base = 'settings';

      if (class_exists('AXL_WP\Includes\\ContactForm')) {
				//new ContactForm();
      }

      	//Instantiate activation Classes
			if (class_exists('AXL_WP\Includes\\Activate')) {
			  new Activate();
      }
      	// Intialize other initialization tidbits
      add_action('plugins_loaded', array($this, 'plugins_loaded'));

    
      
			// Save plugin options and do other activation tasks
			//register_activation_hook($this->base, array($this, 'activate'));

			// Register styles and scripts
			//add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
			//add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts' ) );

			// API Settings
			add_action('rest_api_init', array($this, "register_routes"));

		

    } // END public function __construct()

    public function mandatory_pages() {
      return array(
        array( 
          "id" => "user-registration", 
          "title" => "User Registration",
          "shortcode" => "[{$this->plugin}-registration]"
        ),
        array( 
          "id" => "user-login",
          "title" => "User Login",
          "shortcode" => "[{$this->plugin}-login]"
        ),
        array(
          "id" => "user-profile", 
          "title" => "User Profile",
          "shortcode" => "[{$this->plugin}-profile]"
        ),
        array( 
          "id" => "user-lost-pwd", 
          "title" => "User Lost Password", 
          "shortcode" => "[{$this->plugin}-lost-pwd]"
        ),
        array( 
          "id" => "user-reset-pwd", 
          "title" => "User Reset Password", 
          "shortcode" => "[{$this->plugin}-reset-pwd]"
        ),
        array( 
          "id" => "user-expired-pwd", 
          "title" => "User Expired Password", 
          "shortcode" => "[{$this->plugin}-expired-pwd]"
          )
      );
    }




    // public function settings_defaults() {

    
    //   return array(
    //     array(
    //       "id" => "moduleEnable",
    //       "label" => __("Module Enable", $this->plugin),
    //       "type" => "switch-group",
    //       "items" => array(
    //         array("id" => "members", "class_name" => "Members", "title" => "Members" ),
    //         array("id" => "contactForm", "class_name" => "ContactForm", "title" => "Contact Form"),
    //         array("id" => "cpt", "class_name" => "CPT", "title" => "Custom Post Type" ),
    //         array("id" => "menuCart", "class_name" => "WooMenuCart", "title" => "Menu Cart" ),
    //         array("id" => "wooPDF", "class_name" => "wooPDF", "title" => "Woocommerce Packing Slip & Invoice PDF"),
    //         array("id" => "smtpEmail", "class_name" => "SMTPEmail", "title" => "SMTP Email" ),
    //         array("id" => "wooTidbits", "class_name" => "WooTidbits", "title" => "Woocommerce Tidbits")
    //       ),
    //       "value" => array()
    //     ) 
    //   );
      
    // }





    public function extract_options($options) {

      $temp = array();
     foreach($this->settings_options()[$options] as $item) {
       $temp[$item["id"]] = $item["value"];
     }
     return $temp;

    }


    /**
		 * Return settings options
		 */
		public function settings_options() {


      $options = maybe_unserialize(get_option("{$this->plugin}-dashboard"));
      //var_dump($options);die;
      // Retreive settings
      $settings = ($options && $options["main"]) ? $options["main"] : [];
      return array("main" => $settings);
  }




	


		/**
		* Register and Enque stylesheet and scripts.  Hooks into WP's wp_enqueue_scripts
		**/
		public function enqueue_scripts() {

			// Enqueue Javascript
			wp_register_script("{$this->plugin}-public-js", $this->url . "assets/js/public.js", array("jquery"), null, true);
			//wp_enqueue_script( "{$this->plugin}-public-js");

			// Enqueue Stylesheet
			wp_register_style("{$this->plugin}-public-css", $this->url . "assets/css/public.css", array(), null, 'screen');
			wp_enqueue_style("{$this->plugin}-public-css");

		} // END public function styles()


			/**
		 * A shortcode for rendering the new user registration form.
		 * @param  array   $attributes  Shortcode attributes.
		 * @param  string  $content     The text content for shortcode. Not used.
		 * @return string  The shortcode output
		 */
		public function render_shortcode($module_id, $atts = null){

			// Render the logout template
			if (is_user_logged_in()) return $this->get_template_html('logout');
			
			// Retreive form values from the session variable in case of errors then unset session valiables
			$atts["fields"] = (!empty($_SESSION["{$this->plugin}-{$module_id}-fields"])) ? $_SESSION["{$this->plugin}-{$module_id}-fields"] : array();
			$atts["hints"] = (!empty($_SESSION["{$this->plugin}-{$module_id}-hints"])) ? $_SESSION["{$this->plugin}-{$module_id}-hints"] : array();
			$atts["email"] = (!empty($_SESSION["{$this->plugin}-{$module_id}-email"])) ? $_SESSION["{$this->plugin}-{$module_id}-email"] : array();
			$atts["success"] = (!empty($_SESSION["{$this->plugin}-success"])) ? $_SESSION["{$this->plugin}-success"] : "";
			unset($_SESSION["{$this->plugin}-{$module_id}-fields"]);
			unset($_SESSION["{$this->plugin}-{$module_id}-hints"]);
			unset($_SESSION["{$this->plugin}-{$module_id}-email"]);

			// Render Registration form
			return $this->get_template_html("members-form", $atts);

		} // END public function shortcode( $attributes, $content = null ) {




		/*
		* Checks if required pages are set
		*/
		public function fetch_page($name) {
			
      $pages = (maybe_unserialize(get_option("{$this->plugin}-members")) && maybe_unserialize(get_option("{$this->plugin}-members"))["pages"]) ? maybe_unserialize(get_option("{$this->plugin}-members"))["pages"] : [];
      // echo "<pre>";
      // var_dump($pages);die;
     
      if($pages && is_array($pages)){
        foreach($pages as $page) {
          if ($page && isset($page["id"]) && isset($page["page"]["name"]) && $page["id"] == "$name") {
            $found = $page["page"]["name"];
            break;
          }
        }
      }

			return (isset($found) && $found) ? site_url($found) : site_url();

		} // END public static function  admin_menus (){



		/**
		 * Renders front end form fields by type
		 * @param  array $field input field
		 * @param  string $value input value if any
		 * @return strin        htnl input field
		 */
		public function render_field_type($field, $value) {

      ob_start();
      
      if($field["type"] !== "hidden" && $field['type'] !== "checkbox" && $field['type'] !== "switch") :?>
        <label for="<?php echo $field['id'] ?>"><?php _e($field['label'], $this->plugin)?><?php echo (isset($field['required']) && $field['required']) ? "<strong>*</strong>" : "" ?></label>
      <?php endif; ?>

			<?php if ($field['type'] == "text" || $field['type'] == "email" || $field['type'] == "password" || $field['type'] == "hidden") { ?>
				<input type="<?php echo $field['type'] ?>" id="<?php echo $field['id']; ?>" name="<?php echo $field['id'] ?>" <?php echo ($value) ? "value='" . $value . "'" : "" ?> >
				<?php if (!is_admin()) echo $this->replace_bracketed_values($field['description']);	
			} elseif ($field['type'] == "textarea") { ?>
				<textarea name="<?php echo $field['id'] ?>" id="<?php echo $field['id'] ?>" rows=10 ><?php echo $value ?></textarea><?php
			} elseif ($field['type'] == "checkbox" || $field['type'] == "switch") {?>
        <input type="checkbox" id="<?php echo $field['id'] ?>" name="<?php echo $field['id'] ?>" value="1" <?php checked(1, $value, true)?> <?php echo is_admin() ? "onclick='return false' readonly" : ""; ?> >
        <label for="<?php echo $field['id'] ?>"><?php _e($field['label'], $this->plugin)?><?php echo (isset($field['required']) && $field['required']) ? "<strong>*</strong>" : "" ?></label>
			<?php } elseif ($field['type'] == "select") { ?>
				<select id="<?php echo $field['id'] ?>" name="<?php echo $field['id'] ?>" >
					<option value="0">Select Option</option>
					<?php foreach ($field["items"] as $item) { ?>
						<option value="<?php echo $value ?>" <?php selected($value, $item, true);?> ><?php echo $item ?></option>
					<?php }?>
				</select>
			<?php } elseif ($field['type'] == "radio") {
				foreach ($field["items"] as $item) {?>
					<input type="<?php echo $field['type'] ?>" id="<?php echo $field['id'] ?>" name="<?php echo $field['id'] ?>" value="<?php echo $item ?>" <?php checked($item, $value, true);?> >
					<?php echo $item;
				}
			} else {
					_e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['type'] . ".php does not exist</div>", $this->plugin);
			}

			return ob_get_clean();

		} // END public function render_field_type ($field, $value) {



		/**
		 * Renders templates
		 * @param string $template The name of the template to render (without .php)
		 * @param array  $attributes    The PHP variables for the template
		 * @return string               The contents of the template.
		 */
		public function get_template_html($template, $atts = array()) {

			ob_start();

			do_action($this->prefix . '_before_' . $template);
			require $this->path . "templates/" . $template . ".php";
			do_action($this->prefix . '_after_' . $template);

			$html = ob_get_contents();
			ob_end_clean();

			return $html;

		} // END public function get_template_html($template, $atts = array() ) {

		/**
		 * display_user_messages on the front end
		 * @return string html text
		 */
		public function display_user_messages() {

			// Retreive user errors if any
			$errors = (isset($_SESSION[$this->plugin . '-errors'])) ? $_SESSION[$this->plugin . '-errors'] : array();

			// Retreive user errors sucess if any
			$success = (isset($_SESSION[$this->plugin . '-success'])) ? $_SESSION[$this->plugin . '-success'] : array();

			if (!empty($errors)) {
					$messages = $errors;
					$css_class = "user-errors";
					unset($_SESSION[$this->plugin . '-errors']);
			} elseif (!empty($success)) {
					$messages = $success;
					$css_class = "user-success";
					unset($_SESSION[$this->plugin . '-success']);
			} else {
					$messages = array();
					$css_class = "";
			}

			$html = "";

			if (!empty($messages)) {
					foreach ($messages as $code => $message) {
							$output = ($this->fetch_error_from_code($code) !== "unknown") ? $this->fetch_error_from_code($code) : $message;
							$html .= "<div class = '" . $css_class . "' >" . wpautop($output, true) . "</div>";
					}
			}

			return $html;

		} // END 	public function display_user_messages() {



		/**
		 * Finds and returns a matching error message for the given error code.
		 * @param  string $error_code error code
		 * @return string            error message
		 */
		public function fetch_error_from_code($error_code) {
      $members = maybe_unserialize(get_option("{$this->plugin}-members"));
      $temp = ($members && isset($members["dialogs"]))? $members["dialogs"] : array();
      $dialogs = array();
      foreach($temp as $item) {
        $dialogs[$item["id"]] = $item["value"];
      }
      extract($dialogs);
     
			switch ($error_code) {
				case 'incorrect_password':
					$err = __("{$incorrectPassword} <a href='%s'>Did you forget your password?</a>", $this->plugin);
					return sprintf($err, wp_lostpassword_url());
				case 'invalid_key':
					$err = __("{$invalidKey} <a href='%s'> please try again?</a>", $this->plugin);
          return sprintf($err, wp_lostpassword_url());
        case 'invalid_email':
					$err = __("{$invalidEmail} <a href='%s'>Did you forget your password?</a>", $this->plugin);
					return sprintf($err, wp_lostpassword_url());
				default:
					return __('unknown', $this->plugin);
					break;
			}

		} // END public function fetch_error_from_code( $error_code ) {



		/**
		 * Relaces brackets with options
		 * @param array $options bracketed options
		 * @param string $text text to be modofied
		 * @return string $text with bracketed items replaced by actual option values
		 */
		public function replace_bracketed_values($text, $user = null) {

			$options = array(
					'[blog-name]' => get_bloginfo('name'),
					'[first-name]' => (isset($user) && !empty($user)) ? $user->first_name : '',
					'[last-name]' => (isset($user) && !empty($user)) ? $user->last_name : '',
					'[user-name]' => (isset($user) && !empty($user)) ? $user->user_login : '',
					'[login-page]' => wp_login_url(),
			);

			foreach ($options as $key => $value) {
					$text = str_replace($key, $value, $text);
			}

			return $text;

		} // END public function replace_bracketed_values ($text) {



		/**
		 * Loads all enabled modules
		 */
		public function plugins_loaded() {

      $options = maybe_unserialize(get_option("{$this->plugin}-dashboard"));
      $main = $options["main"];
      if (!$main) return;
    // echo "<pre>";var_dump($main);die;

      
      //  $settings = ($this->settings_options() && isset($this->settings_options()["settings"]) )? $this->settings_options()["settings"] : $this->settings_defaults();


      foreach($main as $item) {
        if ($item["id"] === "moduleEnable") {
          $module_enable = $item["value"];
          break;
        }
      }
      // echo "<pre>";var_dump($module_enable);die;


      if(empty($module_enable)) {
        add_action ('admin_notices', function () {  
          ob_start();?>
          <div class="notice notice-error is-dismissible">
            <p><?php  echo "Plugin {$this->name} is activated but none of the modules are enabled"; ?></p>
          </div>
          <?php
          $html = ob_get_contents();
          ob_end_clean();
          echo  $html;
        });
        return;
      }

      //echo "<pre>";var_dump($module_enable);die;

      //var_dump($module_enable);die;
			
      foreach ($module_enable as $item) {
              //echo "<pre>";var_dump($item);die;

        //$options = $this->settings_options();
            //echo "<pre>ddd";var_dump($options);die;

        if(isset($item["class_name"]) && class_exists('AXL_WP\Includes\\'.$item["class_name"])) {

          //die("PPPPP");
          $class = 'AXL_WP\\Includes\\' . $item["class_name"];
          $instance = new $class;
        }
      }
      //die;
				//	if (isset($class_name) && $class_name && class_exists("AXL_WP\\Includes\\{$class_name}") ) {

                //var_dump($class_name);die;
                //(maybe_unserialize(get_option("{$this->plugin}-members")

//       echo "<pre>";

//       foreach($this->settings_options()["pages"] as $page) {
//         var_dump($page);
//       }
// die;
			// 						$class = 'AXL_WP\\Includes\\' . $class_name;
			// 						$instance = new $class;
			// 				}
			// 		}
			// }

		} // END public function plugins_loaded () {


        /**
     * Displays admin notices
     * @param  object $error WP Error
     * @return string $html  List of admin errors
     */
    public function display_admin_notices($notice) {

      // Loop through all the errors/success
      if (empty($notice) ) return;

      ob_start();
        ?><div class="notice notice-error is-dismissible"><p><?php  echo "$notice"; ?> </p></div><?php
      

      $html = ob_get_contents();
      ob_end_clean();
      return  $html;
      
    }  // END public function display_admin_notices() {




   

		public function register_routes() {


       // Get registered nav menus;
       $this->menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );

      // Get all pages
       $args = array('post_type' => 'page', 'post_status' => 'publish');
       $this->pages = get_pages($args);

      // Get custom post Types
      $args = array(
         'public'   => true,
        '_builtin' => false
      );
      $output = 'object'; // names or objects, note names is the default
      $operator = 'and'; // 'and' or 'or'           
      $post_types = get_post_types( $args, $output, $operator );
      if($post_types) {
        $this->custom_posts = array();
        foreach($post_types as $key => $post) {
           array_push( $this->custom_posts, array("id" => $key, "title" => $post->label));
        }
      }

			// *************************** Dashboard
			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/dashboard",
				array(
					'methods' => \WP_REST_SERVER::READABLE,
					'callback' => function () {
            return array("options" => maybe_unserialize(get_option("{$this->plugin}-dashboard")));
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check"),
				)
			);

			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/dashboard",
				array(
					'methods' => \WP_REST_SERVER::CREATABLE,
					'callback' => function ($data) {

             // Retreive saved settings options 
             $options = maybe_unserialize(get_option("{$this->plugin}-dashboard"));

            // Validate/Sanitize incoming data
            $sanitized = $this->prepare_for_database( $data["fields"]); 
            if($sanitized["errors"]) return array("results" => "wp_error", "messages" => $sanitized["errors"]->get_error_messages(), "options" => array($data["tabID"] => $sanitized["fields"]),  "menus" => $this->menus);
            
            // Update settings
            $fields = $this->reduce_fields($sanitized["fields"]);

            $options[$data["tabID"]] = $fields;
            $results = update_option("{$this->plugin}-dashboard", maybe_serialize($options));
            return array("results" => $results, "options" => $options);

					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
			);

			// ****************************** Members
			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/members",
				array(
					'methods' => \WP_REST_SERVER::READABLE,
					'callback' => function () {
            return array("options" => maybe_unserialize(get_option("{$this->plugin}-members")), "pages" => $this->pages, "adminEmailRecipeints" => array(get_bloginfo("admin_email")), "mandatoryPages" => $this->mandatory_pages());
						},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
			);

			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/members",
				array(
					'methods' => \WP_REST_SERVER::CREATABLE,
					'callback' => function ($data) {


             // Retreive saved settings options 
             $options = maybe_unserialize(get_option("{$this->plugin}-members"));

            // Validate/Sanitize incoming data
            if ($data["tabID"] !== "registration" && $data["tabID"] !== "login" && $data["tabID"] !== "lostPWD" && $data["tabID"] !== "resetPWD" && $data["tabID"] !== "expiredPWD" ) {
              $sanitized = $this->prepare_for_database( $data["fields"]); 
              if($sanitized["errors"]) return array("results" => "wp_error", "messages" => $sanitized["errors"]->get_error_messages(), "options" => array($data["tabID"] => $sanitized["fields"]),  "menus" => $this->menus);
              $fields = $this->reduce_fields($sanitized["fields"]);
              $options[$data["tabID"]] = $fields;
            } else {
              $options[$data["tabID"]] = $data["fields"];
            }
          
            // Update settings
            //$options[$data["tabID"]] = $fields;
            $results = update_option("{$this->plugin}-members", maybe_serialize($options));
            return array("results" => $results, "options" => $options, "pages" => $this->pages, "adminEmailRecipeints" => array(get_bloginfo("admin_email")), "mandatoryPages" => $this->mandatory_pages());


						// $options = get_option("{$this->plugin}-members") ? maybe_unserialize(get_option("{$this->plugin}-members")) : [];
						// $options[$data["payload"]["id"]] = $data["payload"]["options"];
						// return update_option("{$this->plugin}-members", maybe_serialize($options));
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
      );
      
      // ************************ Custom Post Types
			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/cpt",
				array(
					'methods' => \WP_REST_SERVER::READABLE,
					'callback' => function () {
            return array("options" => maybe_unserialize(get_option("{$this->plugin}-cpt")));
						},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
			);

			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/cpt",
				array(
					'methods' => \WP_REST_SERVER::CREATABLE,
					'callback' => function ($data) {

             // Retreive saved settings options 
             $options = maybe_unserialize(get_option("{$this->plugin}-cpt"));

            // Validate/Sanitize incoming data
            if ($data["tabID"] !== "cpts") {
              $sanitized = $this->prepare_for_database( $data["fields"]); 
              if($sanitized["errors"]) return array("results" => "wp_error", "messages" => $sanitized["errors"]->get_error_messages(), "options" => array($data["tabID"] => $sanitized["fields"]),  "menus" => $this->menus);
              $fields = $this->reduce_fields($sanitized["fields"]);
              $options[$data["tabID"]] = $fields;
            } else {
              $options[$data["tabID"]] = $data["fields"];
            }
          
            // Update settings
            //$options[$data["tabID"]] = $fields;
            $results = update_option("{$this->plugin}-cpt", maybe_serialize($options));
            return array("results" => $results, "options" => $options);



						// $options = get_option("{$this->plugin}-cpt") ? maybe_unserialize(get_option("{$this->plugin}-cpt")) : [];
						// $options[$data["payload"]["id"]] = $data["payload"]["options"];
						// return update_option("{$this->plugin}-cpt", maybe_serialize($options));
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
      );


      
      // ************************** Contact Form
			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/contactForm",
				array(
					'methods' => \WP_REST_SERVER::READABLE,
					'callback' => function () {

            return array("options" => maybe_unserialize(get_option("{$this->plugin}-contactForm")), "emailRecipients" => array(get_bloginfo("admin_email")), "customPostTypes" => $this->custom_posts);

					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
			);

			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/contactForm",
				array(
					'methods' => \WP_REST_SERVER::CREATABLE,
					'callback' => function ($data) {

             // Retreive saved settings options 
             $options = maybe_unserialize(get_option("{$this->plugin}-contactForm"));

            // Validate/Sanitize incoming data
            if ($data["tabID"] !== "formFields") {
              $sanitized = $this->prepare_for_database( $data["fields"]); 
              if($sanitized["errors"]) return array("results" => "wp_error", "messages" => $sanitized["errors"]->get_error_messages(), "options" => array($data["tabID"] => $sanitized["fields"]),  "menus" => $this->menus);
              $fields = $this->reduce_fields($sanitized["fields"]);
              $options[$data["tabID"]] = $fields;
            } else {
              $options[$data["tabID"]] = $data["fields"];
            }
          
            // Update settings
            //$options[$data["tabID"]] = $fields;
            $results = update_option("{$this->plugin}-contactForm", maybe_serialize($options));
            return array("results" => $results, "options" => $options, "emailRecipients" => array(get_bloginfo("admin_email")), "customPostTypes" => $this->custom_posts);

					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
      );



      // ************************** SMTP Email
			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/SMTPEmail",
				array(
					'methods' => \WP_REST_SERVER::READABLE,
					'callback' => function () {
              return array("options" => maybe_unserialize(get_option("{$this->plugin}-SMTPEmail")));
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check"),
				)
			);

			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/SMTPEmail",
				array(
					'methods' => \WP_REST_SERVER::CREATABLE,
					'callback' => function ($data) {

             // Retreive saved settings options 
             $options = maybe_unserialize(get_option("{$this->plugin}-SMTPEmail"));

            // Validate/Sanitize incoming data
            $sanitized = $this->prepare_for_database( $data["fields"]); 
            if($sanitized["errors"]) return array("results" => "wp_error", "messages" => $sanitized["errors"]->get_error_messages(), "options" => array($data["tabID"] => $sanitized["fields"]));
            
            // Upsate settings options
            $fields = $this->reduce_fields($sanitized["fields"]);
            $options[$data["tabID"]] = $sanitized["fields"];
            $results = update_option("{$this->plugin}-SMTPEmail", maybe_serialize($options));
            return array("results" => $results, "options" => $options);
          
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
      );
      


      // ************************* Menu Cart
			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/menuCart",
				array(
					'methods' => \WP_REST_SERVER::READABLE,
					'callback' => function () {
						return array("options" => maybe_unserialize(get_option("{$this->plugin}-menuCart")), "menus" => $this->menus);
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check"),
				)
			);

			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/menuCart",
				array(
					'methods' => \WP_REST_SERVER::CREATABLE,
					'callback' => function ($data) {

             // Retreive saved settings options 
             $options = maybe_unserialize(get_option("{$this->plugin}-menuCart"));

            // Validate/Sanitize incoming data
            $sanitized = $this->prepare_for_database( $data["fields"]); 
            if($sanitized["errors"]) return array("results" => "wp_error", "messages" => $sanitized["errors"]->get_error_messages(), "options" => array($data["tabID"] => $sanitized["fields"]),  "menus" => $this->menus);
            
            // Update settings
            $fields = $this->reduce_fields($sanitized["fields"]);
            $options[$data["tabID"]] = $fields;
            $results = update_option("{$this->plugin}-menuCart", maybe_serialize($options));
            return array("results" => $results, "options" => $options, "menus" => $this->menus);
           
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
      );
      


      	// *************** Woo PDF (Invoice & packing Slip)
			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/wooPDF",
				array(
					'methods' => \WP_REST_SERVER::READABLE,
					'callback' => function () {
            // $instance = new WooPDF;
            // $response = array();
            $options = maybe_unserialize(get_option("{$this->plugin}-wooPDF"));
            // $settings =  ($options && isset($options["settings"]))? $options["settings"] : $instance->settings_defaults();
            // $response["settings"] = $settings;
            //return array("options" => $response);
            return array("options" => $options);

					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check"),
				)
			);

			register_rest_route(
				"{$this->namespace}/{$this->rest_base}",
				"/wooPDF",
				array(
					'methods' => \WP_REST_SERVER::CREATABLE,
					'callback' => function ($data) {
							//return $data["payload"]["id"];
							$options = maybe_unserialize(get_option("{$this->plugin}-wooPDF")) ? maybe_unserialize(get_option("{$this->plugin}-wooPDF")) : [];
							$options[$data["payload"]["id"]] = $data["payload"]["options"];
							return update_option("{$this->plugin}-wooPDF", maybe_serialize($options));
					},
					'args' => array(),
					'permission_callback' => array($this, "permissions_check")
				)
			);

      




    } // END public function register_routes ()
    

    public function prepare_for_database ($fields) {
      $errors = new \WP_Error;
      $new_fields = array();
      foreach ($fields as $key => $field) {
        $new_fields[$key] = $field;
        $new_fields[$key]["error"] = false;

        // Sanitize text, email fields
        if (isset($field["type"]) && $field["type"] == "text") {
          $new_fields[$key]["value"] = sanitize_text_field($field["value"]);
        } elseif (isset($field["type"]) && $field["type"] == "email") {
          $new_fields[$key]["value"] = sanitize_email($field["value"]);
        } else {
          $new_fields[$key]["value"] = $field["value"];
        }

        // Check required fields
        if (isset($field["required"]) && empty($field["value"])) {
          $new_fields[$key]["error"] = true;
          $errors->add('required_field', __(wp_kses_post("{$field['label']} is a required field"), $this->plugin));
        }
         // Check email fields
         if (isset($field["type"]) && ($field["type"] === "email") && !is_email($field["value"])) {
            $new_fields[$key]["error"] = true;
            $errors->add('invalid_email', __(wp_kses_post("{$field['label']} is not a valid email"), $this->plugin));
        }

        // Check regular Expression
        if (isset($field["regEx"]) && !preg_match("/{$field["regEx"]}/", $field["value"]))  {
          $new_fields[$key]["error"] = true;
          $errors->add('invalid_field', __(wp_kses_post("{$field['label']} is not valid"), $this->plugin));
        }

        // Check MaxLength
        if (isset($field["maxLength"]) && (strlen($field["value"]) > $field["maxLength"])) {
          $new_fields[$key]["error"] = true;
          $errors->add('max_length_exceeded', __(wp_kses_post("{$field['label']} maximum length must be less than {$field['maxLength']}"), $this->plugin));
        }      

      }

      return array("errors" => (!empty($errors->errors))? $errors : false, "fields" => $new_fields);
    }

    public function reduce_fields ($fields) {
      // Upsate settings options
      $new_fields= array();
      foreach($fields as $key => $field) {
        $new_fields[$key]["id"] = $field["id"];
        $new_fields[$key]["value"] = $field["value"];
      }
      return $new_fields;
    }



		public function permissions_check() {

			if (!current_user_can('administrator')) 
			return new \WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this content.', $this->plugin), array('status' => 401));
			
			return true;

		} // END 	public function permissions_check() {






    /**
    * Adds errors/messages to the debug log
    * @param object/array/string $message
    */
    public function log_message($message) {
      if ( WP_DEBUG === true ) {
        if ( is_array($message) || is_object($message) ) {
            error_log( print_r($message, true) );
        } else {
            error_log( $message );
        }
      }
    }




  } // END class Dashboard {

} // END if (!class_exists('Dashboard'))
