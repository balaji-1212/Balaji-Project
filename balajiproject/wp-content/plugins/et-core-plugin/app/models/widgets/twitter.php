<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Twitter Widget.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Twitter extends Widgets {
	function __construct() {
		$widget_ops = array( 'classname' => 'etheme_twitter', 'description' => esc_html__('Display most recent Twitter feed', 'xstore-core') );
		$control_ops = array( 'id_base' => 'etheme-twitter' );
		parent::__construct( 'etheme-twitter', '8theme - '.esc_html__('Twitter Feed', 'xstore-core'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		if (parent::admin_widget_preview(esc_html__('Twitter Feed', 'xstore-core')) !== false) return;
		if ( xstore_notice() ) return;
		if (!is_null($args)) {
			extract($args);
		}
		$ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

		if (apply_filters('et_ajax_widgets', $ajax)){
			echo et_ajax_element_holder( 'Twitter', $instance, '', '', 'widget', $args );
			return;
		}
		echo (isset($before_widget)) ? $before_widget : '';
		echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		$attr = array( 'usernames' => $instance['usernames'], 'limit' => $instance['limit'], 'interval' => $instance['interval'] );
		$attr['interval'] = (integer) $attr['interval'];
		$attr['interval'] = $attr['interval'] * 10;
		$instance['limit'] = intval($instance['limit']);

		$tweets = $this->get_tweets($instance);

		$html = '';
		if( is_array($tweets) && count($tweets) > 0 && empty($tweets['errors'])) {
			$html = '<ul class="twitter-list">';
			foreach ($tweets as $tweet) {
				$html .= '<li><div class="media"><i class="pull-left et-icon et-twitter"></i><div class="media-body">' . $tweet['text'] . '</div></div></li>';
			}
			$html .= '</ul>';
		}

		echo $html;

		echo (isset($after_widget)) ? $after_widget : '';
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']           = ( ! isset( $new_instance['title'] ) ) ? '' : strip_tags( $new_instance['title'] );
		$instance['usernames']       = ( ! isset( $new_instance['usernames'] ) ) ? '' : strip_tags( $new_instance['usernames'] );
		$instance['consumer_key']    = ( ! isset( $new_instance['consumer_key'] ) ) ? '' : strip_tags( $new_instance['consumer_key'] );
		$instance['consumer_secret'] = ( ! isset( $new_instance['consumer_secret'] ) ) ? '' : strip_tags( $new_instance['consumer_secret'] );
		$instance['limit']           = ( ! isset( $new_instance['limit'] ) ) ? '' : strip_tags( $new_instance['limit'] );
		$instance['interval']        = ( ! isset( $new_instance['interval'] ) ) ? '' : strip_tags( $new_instance['interval'] );
		$instance['ajax'] = (bool) $new_instance['ajax'];
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'usernames' => 'WooCommerce', 'limit' => '2', 'consumer_key' => 'Ev0u7mXhBvvVaLOfPg2Fg', 'consumer_secret' => 'SPdZaKNIeBlUo99SMAINojSJRHr4EQXPSkR0Dw97o', 'interval' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false;

		parent::widget_input_text( esc_html__( 'Title:', 'xstore-core' ), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		parent::widget_input_text( esc_html__( 'Username:', 'xstore-core' ), $this->get_field_id( 'usernames' ), $this->get_field_name( 'usernames' ), $instance['usernames'] );
		parent::widget_input_text( esc_html__( 'Customer Key:', 'xstore-core' ), $this->get_field_id( 'consumer_key' ), $this->get_field_name( 'consumer_key' ), $instance['consumer_key'] );
		parent::widget_input_text( esc_html__( 'Customer Secret:', 'xstore-core' ), $this->get_field_id( 'consumer_secret' ), $this->get_field_name( 'consumer_secret' ), $instance['consumer_secret'] );
		parent::widget_input_text( esc_html__( 'Number of tweets:', 'xstore-core' ), $this->get_field_id( 'limit' ), $this->get_field_name( 'limit' ), $instance['limit'] );
		parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 );
	}

	public function get_tweets($settings) {

		if ( !$settings['consumer_key'] || !$settings['consumer_secret'] ) {
			echo '<p>'.
			     sprintf(esc_html__('Please, enter %1$sConsumer key%2$s and %1$sConsumer secret%2$s', 'xstore-core'), '<strong>', '</strong>') .
			     '</p>';
			return;
		}

		$connection = new \TwitterOAuth(
			$settings['consumer_key'],    // Consumer key
			$settings['consumer_secret']  // Consumer secret
		);

		$settings['tweets_type'] = 'account';

		$posts_data_transient_name = 'etheme-twitter-feed-widget-posts-data-' . sanitize_title_with_dashes( $settings['tweets_type'] . $settings['usernames'] . $settings['limit'] );
		$readyTweets = maybe_unserialize( base64_decode( get_transient( $posts_data_transient_name ) ) );

		if ( ! $readyTweets || isset($_GET['et_clear_twitter_catch']) ) {

			if ( empty($settings['usernames']) ) {
				echo '<p>'.
				     esc_html__('Please, enter Username', 'xstore-core') .
				     '</p>';
				return false;
			}

			$readyTweets = $connection->get(
				'statuses/user_timeline',
				array(
					'count' => $settings['limit'],
					'screen_name' => $settings['usernames']
				)
			);

			if ( $connection->http_code != 200 ) {
				echo '<p class="elementor-panel-alert elementor-panel-alert-danger">'.
				     esc_html__('Twitter not return 200', 'xstore-core') .
				     '</p>';
				return false;
			}

			$encode_posts = base64_encode( maybe_serialize( $readyTweets ) );
			set_transient( $posts_data_transient_name, $encode_posts, apply_filters( 'etheme_twitter_feed_cache_time', HOUR_IN_SECONDS * 2 ) );
		}

		if ( ! $readyTweets ) {
			echo '<p class="elementor-panel-alert elementor-panel-alert-warning">'.
			     esc_html__('Twitter did not return any data', 'xstore-core') .
			     '</p>';
			return false;
		}

		$tweets = array();

		foreach ($readyTweets as $tweet) {
			$screen_name = $tweet->user->screen_name;
			$text = $this->parse_tweet( $tweet, $settings );

			$id_str = $tweet->id_str;
			$permalink = 'https://twitter.com/' . $screen_name . '/status/' . $id_str;

			$tweets[] = array(
				'text'      => $text,
				'name'      => $tweet->user->name,
				'screen_name'      => $screen_name,
				'verified' => $tweet->user->verified,
				'id_str' => $id_str,
				'permalink' => $permalink,
				'time'      => false,
				'favorite_count' => $tweet->favorite_count,
				'retweet_count' => $tweet->retweet_count,
			);
		}

		return $tweets;
	}

	public function parse_tweet($tweet, $settings) {
		// If the Tweet a ReTweet - then grab the full text of the original Tweet
		if( isset( $tweet->retweeted_status ) ) {
			// Split it so indices count correctly for @mentions etc.
			$rt_section = current( explode( ":", $tweet->text ) );
			$text = $rt_section.": ";
			// Get Text
			$text .= $tweet->retweeted_status->text;
		} else {
			// Not a retweet - get Tweet
			$text = $tweet->text;
		}

		// Link Creation from clickable items in the text
		$text = preg_replace( '/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank" rel="nofollow noopener">$0</a>', $text );
		// Clickable Twitter names
		$text = preg_replace( '/[@]+([A-Za-z0-9-_]+)/', '<a href="https://twitter.com/$1" target="_blank" rel="nofollow noopener">@\\1</a>', $text );
		// Clickable Twitter hash tags
		$text = preg_replace( '/[#]+([A-Za-z0-9-_]+)/', '<a href="https://twitter.com/hashtag/$1?src=hashtag_click" target="_blank" rel="nofollow noopener">$0</a>', $text );
		return $text;
	}
}
