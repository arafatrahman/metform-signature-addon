<?php
namespace MFSA\View;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) exit;

class Signature_Widget_View extends Widget_Base {
    public function get_name() {
        return 'signature_widget';
    }

    public function get_title() {
        return __('Signature', 'metformsa');
    }

    public function get_icon() {
        return 'fa fa-signature';
    }

    public function get_categories() {
        return ['metform'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Signature', 'metformsa'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Label Text Control
        $this->add_control(
            'label_text',
            [
                'label' => __('Label Text', 'metformsa'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Sign Here', 'metformsa'),
            ]
        );

        // Show/Hide Label Control
        $this->add_control(
            'show_label',
            [
                'label' => __('Show Label', 'metformsa'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'metformsa'),
                'label_off' => __('Hide', 'metformsa'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Label Position Control
        $this->add_control(
            'label_position',
            [
                'label' => __('Label Position', 'metformsa'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => __('Top', 'metformsa'),
                    'left' => __('Left', 'metformsa'),
                ],
                'default' => 'top',
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style section for label
        $this->start_controls_section(
            'label_style_section',
            [
                'label' => __('Label', 'metformsa'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        // Label Typography Control
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Typography', 'metformsa'),
                'selector' => '{{WRAPPER}} .signature-selector-wrapper label',
            ]
        );

        // Label Text Color Control
        $this->add_control(
            'label_color',
            [
                'label' => __('Text Color', 'metformsa'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .signature-selector-wrapper label' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Label Margin Control
        $this->add_responsive_control(
            'label_margin',
            [
                'label' => __('Margin', 'metformsa'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .signature-selector-wrapper label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Label Padding Control
        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __('Padding', 'metformsa'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .signature-selector-wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
    
        // Apply the label position class
        $label_class = $settings['label_position'] === 'left' ? 'label-left' : 'label-top';
    
        echo "<div class='signature-selector-wrapper {$label_class}'>";
        if ($settings['show_label'] === 'yes') {
            echo "<label for='mf-signature-canvas' class='signature-label'>{$settings['label_text']}</label>";
        }
    
            // Canvas element for drawing the signature
        echo "<canvas id='mf-signature-canvas' style='border: 1px solid #000; width: 100%; max-width: 100%; height: 150px;'></canvas>";
        echo "<button type='button' id='mf-save-signature'>Save</button>";

        // Clear button
        echo "<button type='button' id='mf-clear-signature'>Clear</button>";
        
        // Hidden input to store signature data URL
        echo "<input type='hidden' name='mf-signature-data' id='mf-signature-data'/>";
        echo '</div>';
    
    }

    

    protected function content_template() {}
    
    
}
