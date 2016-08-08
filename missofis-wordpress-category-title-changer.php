<?php
/*
	Plugin Name: Missofis Wordpress Category Title Changer
	Plugin URI: http://missofis.com
	Description: Adds new form text input to add category page form in admin panel.
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
		load_plugin_textdomain( 'missofis-wordpress-category-title-changer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// hook into add category form creation to create form input
		add_action( 'category_add_form_fields', array( $this, 'mso_add_title_input_to_add_category_form' ) );
		
		//
		add_action( 'category_edit_form_fields', array( $this, 'mso_add_title_input_to_edit_category_form' ), 10, 2 );

		// category created (save meta)
		add_action( 'edited_category', array( $this, 'mso_save_mso_custom_title' ), 10, 2 );
		add_action( 'create_category', array( $this, 'mso_save_mso_custom_title' ), 10, 2 );

	}

	// adds seo title input to form
	public function mso_add_title_input_to_add_category_form( $taxonomy ) {

		?>
		<div class="form-field">
			<label for="tag-mso-custom-title">Custom Title</label>
			<input name="mso-custom-title" id="tag-mso-custom-title" type="text" value="" size="100" aria-required="false">
			<p>Set a custom title attribute for category.</p>
		</div>
		<?php

	}

	// adds seo title input to form
	public function mso_add_title_input_to_edit_category_form( $tag, $taxonomy ) {

		// $term_meta = get_option( 'taxonomy_' . $tag->term_id );
		$t_id = $tag->term_id;
		$term_meta = get_term_meta( $t_id, 'hola_x_trial' );

		echo "<hr><code><pre>";
		print_r( $tag );
		echo "</pre></code><hr>";
		echo "<code><pre>";
		print_r( $taxonomy );
		echo "</pre></code>";
		echo "<hr><code><pre>";
		print_r( $term_meta );
		echo "</pre></code>";

		?>
		<tr class="form-field">
			<th scope="row"><label for="tag-mso-custom-title">Custom Title</label></th>
			<td><input name="mso-custom-title" id="tag-mso-custom-title" type="text" value="" size="100" aria-required="false" />
			<p class="description">Set a custom title attribute for category.</p></td>
		</tr>
		<?php

	}

	// save custom title data
	public function mso_edit_custom_title_field( $tag, $taxonomy ) {
		
		// todo :: show?

	}

	// save custom title data
	public function mso_save_mso_custom_title( $term_id, $tt_id ) {

		// if ( isset( $_POST[ 'term_meta' ] ) ) {

			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST[ 'term_meta' ] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST[ 'term_meta' ][ $key ] ) ) {
					$term_meta[ $key ] = $_POST[ 'term_meta' ][ $key ];
				}
			}
			// update_option( "taxonomy_$t_id", $term_meta );
			// update_option( 'blogname', 'Rumbadarumbarumba' );
		// }

		add_term_meta( $term_id, 'hola_x_trial', 'qazwsxedcrfv' );

	}

}

// build plugin
$mso_category_title_changer = new Missofis_Category_Title_Changer();

?>