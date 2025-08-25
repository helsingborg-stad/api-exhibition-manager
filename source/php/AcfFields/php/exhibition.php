<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_68a837bcde3c7',
    'title' => __('Exhibition', 'api-exhibition-manager'),
    'fields' => array(
        0 => array(
            'key' => 'field_68a837bd20b10',
            'label' => __('Namn', 'api-exhibition-manager'),
            'name' => 'name',
            'aria-label' => '',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'maxlength' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'permalink',
        1 => 'the_content',
        2 => 'excerpt',
        3 => 'discussion',
        4 => 'comments',
        5 => 'slug',
        6 => 'format',
        7 => 'page_attributes',
        8 => 'featured_image',
        9 => 'categories',
        10 => 'tags',
        11 => 'send-trackbacks',
    ),
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
));
}