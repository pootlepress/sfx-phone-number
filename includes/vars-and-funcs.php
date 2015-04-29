<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 29/4/15
 * Time: 2:50 PM
 */
$SFX_Telephone_widget_fields = array(

	'bg-color' => array(
		'id' => 'bg-color',
		'label' => 'Widget background color',
		'type' => 'color',
	),

	'cta-text' => array(
		'id' => 'cta-text',
		'label' => 'Call to action Text',
		'type' => 'text',
		'description' => 'Text before phone number',
	),

	'phone' => array(
		'id' => 'phone',
		'label' => 'Phone number',
		'type' => 'text',
	),

	'skype' => array(
		'id' => 'skype',
		'label' => 'Skype id',
		'type' => 'text',
	),

	'fa-icon' => array(
		'id' => 'fa-icon',
		'label' => 'Font awesome icon combination',
		'type' => 'select',
		'default' => 'none',
		'options' => array(
			'none' => 'none',
			'fa-phone' => 'fa-phone',
			'fa-skype' => 'fa-skype',
		),
	),

	'i-border-radius' => array(
		'id' => 'i-border-radius',
		'label' => 'Icon Border Radius',
		'type' => 'text',
	),

	'i-border-width' => array(
		'id' => 'i-border-width',
		'label' => 'Icon Border Thickness',
		'type' => 'text',
	),

	'i-color' => array(
		'id' => 'icon-color',
		'label' => 'Icon color',
		'type' => 'color',
	),

	'i-hover-color' => array(
		'id' => 'icon-hover-color',
		'label' => 'Icon hover color',
		'type' => 'color',
	),

	'display-format' => array(
		'id' => 'display-format',
		'label' => 'Show as',
		'type' => 'select',
		'default' => 'none',
		'options' => array(
			'text' => 'text',
			'button' => 'button',
		),
	),

	//Header Options
	'only-mobile' => array(
		'id' => 'only-mobile',
		'label' => 'Only show on mobile device',
		'type' => 'checkbox',
		'default' => '',
	),

	'fa-icon-mobile' => array(
		'id' => 'fa-icon-mobile',
		'label' => 'Font awesome icon for mobile',
		'type' => 'select',
		'default' => 'desktop',
		'options' => array(
			'desktop' => 'Desktop icon',
			'none' => 'none',
			'fa-phone' => 'fa-phone',
			'fa-skype' => 'fa-skype',
		),
	),

);