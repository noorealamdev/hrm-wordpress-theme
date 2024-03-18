<?php

new \Kirki\Panel(
    'panel_id',
    [
        'priority'    => 10,
        'title'       => esc_html__( 'Theme Settings', 'cm-hrm' )
    ]
);

// header_logo_section
function logo_section(){
    // header_logo_section section 
    new \Kirki\Section(
        'logo_section',
        [
            'title'       => esc_html__( 'Logo', 'cm-hrm' ),
            'panel'       => 'panel_id',
            'priority'    => 40,
        ]
    );

    // header_logo_section section 
    new \Kirki\Field\Image(
        [
            'settings'    => 'logo',
            'label'       => esc_html__( 'Logo', 'cm-hrm' ),
            'description' => esc_html__( 'Theme Default/Primary Logo Here', 'cm-hrm' ),
            'section'     => 'logo_section',
            'default'     => get_template_directory_uri() . '/assets/img/logo/logo.png',
        ]
    );
}

// color_section
function color_section(){
	// color section
	new \Kirki\Section(
		'color_section',
		[
			'title'       => esc_html__( 'Colors', 'cm-hrm' ),
			'panel'       => 'panel_id',
			'priority'    => 40,
		]
	);

	new \Kirki\Field\Color(
		[
			'settings'    => 'bg_color',
			'label'       => __( 'Background Color', 'cm-hrm' ),
			'description' => esc_html__( 'Body background color', 'cm-hrm' ),
			'section'     => 'color_section',
			'default'     => '#5e8fe4',
		]
	);
}

// footer layout
function footer_section(){

	new \Kirki\Section(
		'footer_section',
		[
			'title'       => esc_html__( 'Footer', 'nilos' ),
			'description' => esc_html__( 'Footer Settings.', 'cm-hrm' ),
			'panel'       => 'panel_id',
			'priority'    => 190,
		]
	);

	new \Kirki\Field\Checkbox_Switch(
		[
			'settings'    => 'enable_footer',
			'label'       => esc_html__( 'Footer', 'kirki' ),
			'section'     => 'footer_section',
			'default'     => 'on',
			'choices'     => [
				'on'  => esc_html__( 'Enable', 'kirki' ),
				'off' => esc_html__( 'Disable', 'kirki' ),
			],
		]
	);

	new \Kirki\Field\Text(
		[
			'settings' => 'copyright_text',
			'label'    => esc_html__( 'Copyright Text', 'cm-hrm' ),
			'section'  => 'footer_section',
			'default'  => esc_html__( 'Copyright & Developed By Noor E Alam - 2024', 'cm-hrm' ),
			'priority' => 10,
		]
	);
}

logo_section();
color_section();
footer_section();
