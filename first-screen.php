<?php

/*
Plugin Name: FCP Anti Revolution
Description: This plugin by FirmCatalyst is here instead of Slider Revolution, as it has holes
Version: 0.0.1
Requires at least: 4.7
Requires PHP: 7.0.0
Author: Firmcatalyst, Vadim Volkov
Author URI: https://firmcatalyst.com
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: fcpar
*/

namespace FCP\AntiRevolution;

defined( 'ABSPATH' ) || exit;

define( 'FCPAR', [
    'dev'            => true,
    'prefix'         => 'fcpar' . '-',
]);

if( !function_exists( 'get_plugin_data' ) ) { require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

define( 'FCPAR_VER', get_plugin_data( __FILE__ )[ 'Version' ] . ( FCPAR['dev'] ? time() : '' ) );


// admin meta boxes
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'anti-slider-revolution',
        'Page top hero texts',
        'FCP\AntiRevolution\meta_box_layout',
        ['page'],
        'normal',
        'high'
    );
});

function meta_box_layout() {
    global $post;

    $textarea = function ($a) {
        ?>
    <textarea
        name="<?php echo FCPAR['prefix'] . $a->name ?>"
        id="<?php echo FCPAR['prefix'] . $a->name ?>"
        rows="<?php echo isset( $a->rows ) ? $a->rows : '10' ?>" cols="<?php echo isset( $a->cols ) ? $a->cols : '50' ?>"
        placeholder="<?php echo isset( $a->placeholder ) ? $a->placeholder : '' ?>"
        class="<?php echo isset( $a->className ) ? $a->className : '' ?>"
    ><?php
        echo esc_textarea( isset( $a->value ) ? $a->value : '' )
    ?></textarea>
        <?php
    };

    $checkboxes = function ($a) {
        ?>
    <fieldset
        id="<?php echo FCPAR['prefix'] . $a->name ?>"
        class="<?php echo isset( $a->className ) ? $a->className : '' ?>"><?php

        foreach ( (array) $a->options as $k => $v ) {
            $checked = is_array( $a->value ) && in_array( $k, $a->value );
        ?><label>
            <input type="checkbox"
                name="<?php echo FCPAR['prefix'] . $a->name ?>[]"
                value="<?php echo esc_attr( $k ) ?>"
                <?php echo $checked ? 'checked' : '' ?>
            >
            <span><?php echo $v ?></span>
        </label><?php } ?>
    </fieldset>
        <?php  
    };

    $select = function ($a) {
        ?>
        <select
            name="<?php echo FCPAR['prefix'] . $a->name ?>"
            id="<?php echo FCPAR['prefix'] . $a->name ?>"
            class="<?php echo isset( $a->className ) ? $a->className : '' ?>"><?php

            if ( isset( $a->placeholder ) ) { ?>
                <option value=""><?php echo $a->placeholder ?></option>
            <?php } ?>

            <?php foreach ( $a->options as $k => $v ) { ?>
                <option
                    value="<?php echo esc_attr( $k ) ?>"
                    <?php echo isset( $a->value ) && $a->value === $k ? 'selected' : '' ?>
                ><?php echo esc_attr( $v ) ?></option>
            <?php } ?>
        </select>
        <?php
    };

    $input = function($a) {
        ?>
        <input type="text"
            name="<?php echo FCPAR['prefix'] . $a->name ?>"
            id="<?php echo FCPAR['prefix'] . $a->name ?>"
            placeholder="<?php echo isset( $a->placeholder ) ? $a->placeholder  : '' ?>"
            value="<?php echo isset( $a->value ) ? esc_attr( $a->value ) : '' ?>"
            class="<?php echo isset( $a->className ) ? $a->className : '' ?>"
        />
        <?php
    };

    $checkboxes( (object) [
        'name' => 'active',
        'options' => [ 'true' => 'Active' ],
        'value' => get_post_meta( $post->ID, FCPAR['prefix'] . 'active' )[0],
    ]);
    $input( (object) [
        'name' => 'headline',
        'placeholder' => 'Headline',
        'value' => get_post_meta( $post->ID, FCPAR['prefix'] . 'headline' )[0],
    ]);
    $input( (object) [
        'name' => 'headline-2',
        'placeholder' => 'second line of headline if needed',
        'value' => get_post_meta( $post->ID, FCPAR['prefix'] . 'headline-2' )[0],
    ]);
    $select( (object) [
        'name' => 'headline-tag',
        'placeholder' => 'Select Headline Tag',
        'options' => [
            'p' => '&lt;p&gt;',
            'h1' => '&lt;h1&gt;',
            'h2' => '&lt;h2&gt;',
        ],
        'value' => get_post_meta( $post->ID, FCPAR['prefix'] . 'headline-tag' )[0],
    ]);
    $input( (object) [
        'name' => 'description',
        'placeholder' => 'Paragraph text',
        'value' => get_post_meta( $post->ID, FCPAR['prefix'] . 'description' )[0],
    ]);
    $select( (object) [
        'name' => 'texts-position',
        'placeholder' => 'Select Texts Position',
        'options' => [
            'flex-start flex-start' => '&#8662;&nbsp;&nbsp;&nbsp; Left-Top',
            'flex-start center' => '&#8656;&nbsp;&nbsp;&nbsp; Left-Middle',
            'flex-start space-between' => '&#8656;&nbsp;&nbsp;&nbsp; Left-Spread',
            'flex-start flex-end' => '&#8665;&nbsp;&nbsp;&nbsp; Left-Bottom',
            'center flex-start' => '&#8657;&nbsp;&nbsp;&nbsp; Center-Top',
            'center center' => '&#9634;&nbsp;&nbsp;&nbsp; Center-Middle',
            'center space-between' => '&#8661;&nbsp;&nbsp;&nbsp; Center-Spread',
            'center flex-end' => '&#8659;&nbsp;&nbsp;&nbsp; Center-Bottom',
            'flex-end flex-start' => '&#8663;&nbsp;&nbsp;&nbsp; Right-Top',
            'flex-end center' => '&#8658;&nbsp;&nbsp;&nbsp; Right-Middle',
            'flex-end space-between' => '&#8658;&nbsp;&nbsp;&nbsp; Right-Spread',
            'flex-end flex-end' => '&#8664;&nbsp;&nbsp;&nbsp; Right-Bottom',
        ],
        'value' => get_post_meta( $post->ID, FCPAR['prefix'] . 'texts-position' )[0],
    ]);

    ?><p>The background image equals the featured image of the page</p><?php

    $select( (object) [
        'name' => 'image-position',
        'placeholder' => 'Select Image Position',
        'options' => [
            'center top' => '&#8657;&nbsp;&nbsp;&nbsp; Top',
            'center center' => '&#9632;&nbsp;&nbsp;&nbsp; Middle',
            'center bottom' => '&#8659;&nbsp;&nbsp;&nbsp; Bottom',
        ],
        'value' => get_post_meta( $post->ID, FCPAR['prefix'] . 'image-position' )[0],
    ]);


    wp_nonce_field( FCPAR['prefix'] . 'nounce-action', FCPAR['prefix'] . 'nounce-name' );

}

// style meta boxes
add_action( 'admin_footer', function() {
    //++post type filter
    ?><style type="text/css">
#anti-slider-revolution input:not([type=checkbox]),
#anti-slider-revolution select,
#anti-slider-revolution fieldset {
    width:100%;
    margin-bottom:18px;
    padding:10px 16px;
    font-size:18px;
}
#anti-slider-revolution fieldset {
    padding-left:0;
}
    </style><?php
});


add_action( 'save_post', function( $postID ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( !wp_verify_nonce( $_POST[ FCPAR['prefix'] . 'nounce-name' ], FCPAR['prefix'] . 'nounce-action' ) ) { return; }
    if ( !current_user_can( 'edit_post', $postID ) ) { return; }

    $post = get_post( $postID );
    if ( $post->post_type == 'revision' ) { return; }

    $fields = [ 'active', 'headline', 'headline-2', 'headline-tag', 'description', 'texts-position', 'image-position' ];

    foreach ( $fields as $f ) {
        $f = FCPAR['prefix'] . $f;
        if ( empty( $_POST[ $f ] ) ) {
            delete_post_meta( $postID, $f );
            continue;
        }
        update_post_meta( $postID, $f, $_POST[ $f ] );
    }

});


// printing
add_shortcode( 'anti-revolution-header', function() {
    global $post;

    $active = get_post_meta( $post->ID, FCPAR['prefix'].'active' );
    if ( !isset( $active ) ) { return; }

    $values = get_post_meta( $post->ID );
    $p = FCPAR['prefix'];
    $tag = isset( $values[ $p.'headline-tag' ] ) ? $values[ $p.'headline-tag' ][0] : 'p';

    ob_start();

    ?>
    <style type="text/css">
    <?php include_once( __DIR__ . '/style.css' ) ?>
    <?php if ( isset( $values[ $p . 'image-position' ] ) ) { ?>
        .fcpar-image img {
            object-position:<?php echo $values[ $p.'image-position' ][0] ?>;
        }
    <?php } ?>
    <?php if ( isset( $values[ $p . 'texts-position' ] ) ) { $position = explode( ' ', $values[ $p . 'texts-position' ][0] ) ?>
        .fcpar-hero {
            align-items:<?php echo $position[0] ?>;
            justify-content:<?php echo $position[1] ?>;
        }
    <?php } ?>
    </style>
    <section class="fcpar-hero">
        <<?php echo $tag ?> class="fcpar-headline">
            <?php echo isset( $values[ $p.'headline' ] ) ? $values[ $p.'headline' ][0] : '' ?>
            <?php echo isset( $values[ $p.'headline-2' ] ) ? '<br>'.$values[ $p.'headline-2' ][0] : '' ?>
        </<?php echo $tag ?>>
        <?php echo isset( $values[ $p.'description' ] ) ? '<p class="fcpar-description">'.$values[ $p.'description' ][0].'</p>' : '' ?>
        <?php if ( has_post_thumbnail() ) { ?>
            <div class="fcpar-image">
                <?php echo get_the_post_thumbnail( null, 'full' ) ?>
            </div>
        <?php } ?>
    </section>
    <?php

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

//++ excape the printing properly
//++ minify the css