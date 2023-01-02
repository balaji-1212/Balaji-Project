<?php 

/**
* 
*/
class Etheme_Control_Sortable extends WP_Customize_Control{

    /**
     * The control type.
     *
     * @access public
     * @var string
     */
    public $type = 'etheme-sortable';

    /**
     * Data type
     *
     * @access public
     * @var string
     */
    public $option_type = 'theme_mod';


    /**
     * Used to automatically generate all CSS output.
     *
     * @access public
     * @var array
     */
    public $output = [];


/**
   * Option name (if using options).
   *
   * @access public
   * @var string
   */
  public $option_name = false;


  public $kirki_config = 'global';


  public $required = [];


  public $css_vars = '';


    /**
     * Enqueue control related scripts/styles.
     *
     * @access public
     */
    public function enqueue() {

        // Enqueue the script and style.
        wp_enqueue_script( 'etheme-sortable', apply_filters( 'etheme_sortable_url', plugins_url( __FILE__ ) ) . '/script.js', [ 'jquery', 'customize-base', 'customize-controls', 'jquery-ui-draggable' ], '1.0', false );
        wp_enqueue_style( 'etheme-sortable', apply_filters( 'etheme_sortable_url', plugins_url( __FILE__ ) ) . '/style.css', [], '1.0' );
    }



        /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @see WP_Customize_Control::to_json()
     */
    public function to_json() {

        // Get the basics from the parent class.
        parent::to_json();

        // Default value.
        $this->json['default'] = $this->setting->default;
        if ( isset( $this->default ) ) {
            $this->json['default'] = $this->default;
        }

        // Required.
        $this->json['required'] = $this->required;

        // Output.
        $this->json['output'] = $this->output;

        // Value.
        $this->json['value'] = $this->value();

        // Choices.
        $this->json['choices'] = $this->choices;

        // The link.
        $this->json['link'] = $this->get_link();

        // The ID.
        $this->json['id'] = $this->id;

        // The kirki-config.
        $this->json['kirkiConfig'] = $this->kirki_config;

        // The option-type.
        $this->json['kirkiOptionType'] = $this->option_type;

        // The option-name.
        $this->json['kirkiOptionName'] = $this->option_name;

        // The CSS-Variables.
        $this->json['css-var'] = $this->css_vars;
    }


    /**
     * An Underscore (JS) template for this control's content (but not its container).
     *
     * Class variables for this control class are available in the `data` JS object;
     * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
     *
     * @see WP_Customize_Control::print_template()
     *
     * @access protected
     */
    protected function content_template() {
        ?>
        <!-- Label. -->
        <# if ( data.label ) { #>
            <label><span class="customize-control-title">{{{ data.label }}}</span></label>
        <# } #>
        <!-- Description. -->
        <# if ( data.description ) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>

       

        <!-- Sortable container. -->
        <div class="etheme-sortable">
            <ul class="etheme-sortable--sortable">
                
                <input class="hidden-value" type="hidden" {{{ data.link }}}/>

                <# // Object.assign(data.value, data.default) #>

                <# _.each( data.value, function(choice, choiceID) { #>

                    <# var visibility = ( choice.visibility ) ? 'visible' : 'invisible'; #>

                  <li {{{ data.inputAttrs }}} class='kirki-sortable-item {{{visibility}}}' data-id='{{ choiceID }}'>
                        <span>
                            <i class="dashicons dashicons-visibility visibility visible"></i>
                            <span>{{{choice.label}}}</span>
                            <i class="dashicons dashicons-arrow-down-alt2 et_opener"></i>
                        </span>

                        {{{ data.choices[ choice ] }}}

                        <ul class="et_options hidden">
                            <# _.each( choice.fields, function( field, id ) { #>
               
                                <# if ( field.type == 'input' ) { #>
                                    <li class="et_option-type-input">
                                        <label for="{{{id}}}"><span class="customize-control-title">{{{field.label}}}</span></label>
                                        <input id="{{{id}}}" type="text" value="{{{field.value}}}">
                                    </li>
                                <# }; #>

                                <# if ( field.type == 'select' ) { #>

                                    <li class="et_option-type-select">
                                        <label for="{{{id}}}"><span class="customize-control-title">{{{field.label}}}</span></label>
                                        <select name="{{{id}}}" id="{{{id}}}">
                                            <# _.each( field.options, function( option, kay ) { #>
                                                <# if ( field.value == kay ) { #>
                                                    <option value="{{{kay}}}" selected="true">{{{option}}}</option>
                                                <# } else {  #>
                                                    <option value="{{{kay}}}">{{{option}}}</option>
                                                <# } #>        
                                            <# }); #>
                                        </select>
                                    </li>
                                <# }; #>

                                <# if ( field.type == 'toggle' ) { #>
                                     <li class="et_option-type-toggle">
                                        <label for="{{{id}}}">
                                            <span class="customize-control-title">{{{field.label}}}</span>
                                            <input
                                                class="screen-reader-text"
                                                name="{{{id}}}"
                                                id="{{{id}}}"
                                                type="checkbox"
                                                value="{{{field.value}}}"    
                                            >
                                            <span class="switch"></span>
                                        </label>
                                    </li>
                                <# }; #>
                                <# if ( field.type == 'range' ) { #>
                                   <li class="et_option-type-range">
                                        <label>
                                            <span class="customize-control-title">{{{field.label}}}</span>

                                            <# var value = ( ! field.value ) ? field.default : field.value ; #>
                                            <# var defaultv = ( ! field.default ) ? field.value : field.default ; #>
                                            
                                            <div class="wrapper">
                                                <input
                                                    type="range"
                                                    min="{{{field.min}}}"
                                                    max="{{{field.max}}}"
                                                    step="{{{field.step}}}"
                                                    value="{{{value}}}"
                                                    data-default="{{{defaultv}}}"
                                                >
                                                <span class="slider-reset dashicons dashicons-image-rotate">
                                                </span>
                                                <span class="value">
                                                   <input id="{{{id}}}" type="text" value="{{{value}}}">
                                                    <span class="suffix"></span>
                                                </span>
                                            </div>
                                        </label>
                                    </li>
                                <# }; #>

                                <# if ( field.type == 'box_modal' ) { #>
                                   <li class="et_option-type-box-modal" id="{{{id}}}">
                                        <label>
                                            <span class="customize-control-title">{{{field.label}}}</span>
                                        </label>
                                            <div class="box">
                                                <# 
                                                    var labelsTitles = {
                                                        margin: '<?php esc_html_e( 'Margin', 'xstore-core' ); ?>',
                                                        border: '<?php esc_html_e( 'Border', 'xstore-core' ); ?>',
                                                        padding: '<?php esc_html_e( 'Padding', 'xstore-core' ); ?>',
                                                        'margin-top': '<?php esc_html_e( 'Margin Top', 'xstore-core' ); ?>',
                                                        'margin-right': '<?php esc_html_e( 'Margin Right', 'xstore-core' ); ?>',
                                                        'margin-bottom': '<?php esc_html_e( 'Margin Bottom', 'xstore-core' ); ?>',
                                                        'margin-left': '<?php esc_html_e( 'Margin Left', 'xstore-core' ); ?>',
                                                        'border-top': '<?php esc_html_e( 'Border Top', 'xstore-core' ); ?>',
                                                        'border-right': '<?php esc_html_e( 'Border Right', 'xstore-core' ); ?>',
                                                        'border-bottom': '<?php esc_html_e( 'Border Bottom', 'xstore-core' ); ?>',
                                                        'border-left': '<?php esc_html_e( 'Border Left', 'xstore-core' ); ?>',
                                                        'padding-top': '<?php esc_html_e( 'Padding Top', 'xstore-core' ); ?>',
                                                        'padding-right': '<?php esc_html_e( 'Padding Right', 'xstore-core' ); ?>',
                                                        'padding-bottom': '<?php esc_html_e( 'Padding Bottom', 'xstore-core' ); ?>',
                                                        'padding-left': '<?php esc_html_e( 'Padding Left', 'xstore-core' ); ?>',
                                                    };

                                                    var levels = {
                                                    margin  : {},
                                                    border  : {},
                                                    padding : {},
                                                    };
                                                    _.each( field.fields, function( option, kay ) {
                                                        if( kay.includes( 'margin' ) ){
                                                            levels['margin'][kay] = option;
                                                        } else if( kay.includes( 'border' ) ){
                                                            levels['border'][kay] = option;
                                                        } else if( kay.includes( 'padding' ) ){
                                                            levels['padding'][kay] = option;
                                                        }
                                                    }); 
                                                #>

                                                <# var i = 0;
                                                    _.each(levels, function( context, level ) { #>
                                                    <div class="{{ level }} level-{{ i }}">
                                                        <span class="label">{{ labelsTitles[level] }}</span>
                                                        <# _.each( [ 'top', 'right', 'bottom', 'left' ], function( side ) { #>
                                                            <# var key = ( 'border' === level ) ? level + '-' + side + '-width' : level + '-' + side; #>
                                                            <label class="screen-reader-text" for="{{ data.id }}-{{ key }}">{{ labelsTitles[ level + '-' + side ] }}</label>
                                                            <input data-id="{{{key}}}" id="{{ data.id }}-{{ key }}" class="{{ side }} {{ key }}" type="text" value="{{{levels[level][key]}}}" size="1">
                                                        <# 
                                                    }); #>
                                                    </div>
                                                <#  i++;
                                                    }); #>
                                            
                                        </div>
                                    </li>
                                <# }; #>

                                <# if ( field.type == 'color' ) { #>
                                     <li class="et_option-type-color">
                                        <label for="{{{id}}}"><span class="customize-control-title">{{{field.label}}}</span> </label>
                                        <input
                                            name="{{{id}}}"
                                            class="et_color-field-holder"
                                            id="{{{id}}}"
                                            type="text"
                                            value="{{{field.value}}}" 
                                            data-alpha="true"  
                                        >
                                    </li>
                                <# }; #>

                                <# if ( field.type == 'custom' ) { #>
                                    <li class="et_option-type-custom">
                                        {{{field.value}}}
                                    </li>
                                <# }; #>

                            <# }); #>
                        </ul>
                    </li>
                <# }); #>
            </ul>
          </div>
        <?php
    }

    /**
     * Adding an empty function here prevents PHP errors from to_json() in the parent class.
     *
     * @access protected
     * @since 1.0
     * @return void
     */
    protected function render_content() {}

	
	// function __construct(argument){
	// 	# code...
	// }
}