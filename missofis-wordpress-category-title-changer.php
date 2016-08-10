<?php
/*
	Plugin Name: Missofis Wordpress Category Title Changer
	Plugin URI: http://missofis.com
	Description: Adds new form text input to add category page form in admin panel. Requires WordPress version 4.4.0+
	Version: 1.0
	Author: Kemal Yılmaz
	Author URI: http://kemalyilmaz.com
*/

class Missofis_Category_Title_Changer {

	// constructor
	public function __construct() {

		// todo :: add activation/deactivation hooks to cleanup older category titles?

		add_action( 'init', array( $this, 'init' ) );

	}

	// initialize plugin
	public function init() {

		// load text domain for plugin
		load_plugin_textdomain( 'missofis-category-title-changer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// hook into add category form creation to create form input
		add_action( 'category_add_form_fields', array( $this, 'mso_add_title_input_to_add_category_form' ) );
		
		// add custom field to edit category form
		add_action( 'category_edit_form_fields', array( $this, 'mso_add_title_input_to_edit_category_form' ), 10, 2 );

		// category created (save meta)
		add_action( 'edited_category', array( $this, 'mso_save_mso_custom_title' ), 10, 2 );
		add_action( 'create_category', array( $this, 'mso_save_mso_custom_title' ), 10, 2 );

		// alter page title
		add_filter( 'single_cat_title', array( $this, 'mso_alter_category_page_title' ) );

		// todo :: get_the_archive_title, to change category title in page

	}

	// adds seo title input to form
	public function mso_add_title_input_to_add_category_form( $taxonomy ) {

		?>
		<div class="form-field">
			<label for="tag-mso-custom-title"><?php _e( 'Custom Title', 'missofis-category-title-changer' ); ?></label>
			<input name="mso-custom-title" id="tag-mso-custom-title" type="text" value="" size="100" aria-required="false">
			<p><?php _e( 'Set a custom title attribute for category.', 'missofis-category-title-changer' ); ?></p>
		</div>
		<?php

	}

	// adds seo title input to form
	public function mso_add_title_input_to_edit_category_form( $tag, $taxonomy ) {

		$term_meta = get_term_meta( $tag->term_id, 'mso_custom_title', true );

		?>
		<tr class="form-field">
			<th scope="row"><label for="tag-mso-custom-title"><?php _e( 'Custom Title', 'missofis-category-title-changer' ); ?></label></th>
			<td><input name="mso-custom-title" id="tag-mso-custom-title" type="text" value="<?php echo esc_attr( $term_meta ); ?>" size="100" aria-required="false" />
			<p class="description"><?php _e( 'Set a custom title attribute for category.', 'missofis-category-title-changer' ); ?></p></td>
		</tr>
		<?php

	}

	// save custom title data
	public function mso_save_mso_custom_title( $term_id, $tt_id ) {

		$custom_title = trim( $_POST[ 'mso-custom-title' ] );

		if ( !empty( $custom_title ) ) {

			update_term_meta( $term_id, 'mso_custom_title', $custom_title );

		}


	}

	// change category title
	public function mso_alter_category_page_title( $term_name ) {

		if( is_category() ) {
			$term_id = get_query_var( 'cat' );
		}

		$seo_title = get_term_meta( $term_id, 'mso_custom_title', true );

		return $seo_title ? $seo_title : $term_name;

	}

}

// build plugin
$mso_category_title_changer = new Missofis_Category_Title_Changer();

?>