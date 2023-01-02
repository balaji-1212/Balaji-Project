<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Twitter shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Twitter extends Shortcodes {

	function hooks() {}

	function twitter_shortcode( $atts, $content ) {
		if ( xstore_notice() )
			return;

			$atts = shortcode_atts( array(
				'title' => '',
				'username' => '',
				'consumer_key' => '',
				'consumer_secret' => '',
				'user_token' => '',
				'user_secret' => '',
				'limit' => 10,
				'design' => 'grid',
				'class' => 10,
				'is_preview' => false
			), $atts );

			$options = array();
			$options['box_id'] = rand(100,999);
		    $options['tweets'] = $this->get_tweets($atts);

    		etheme_enqueue_style( 'wpb-twitter' );

		    if (!$options['tweets']) return;
			ob_start();

			?>
	
			<div class="et-twitter-<?php echo esc_attr($atts['design']) . ' ' . esc_attr($atts['class']); ?> et-twitter-wpb">

				<?php 
				if( $atts['title'] != '' ) { ?>
					<h2 class="twitter-slider-title">
						<span><?php echo esc_html($atts['title']); ?></span>
					</h2>
				<?php } ?>

                <?php if($atts['design'] == 'slider'): ?>
                <div class="swiper-entry">
                    <div
                            class="swiper-container"
                            data-breakpoints="1"
                            data-xs-slides="1"
                            data-sm-slides="2"
                            data-md-slides="3"
                            data-lt-slides="3"
                            data-slides-per-view="3"
                            data-slides-per-group="1"
                            data-speed="300"
                    >
                <?php endif; ?>

                        <ul class="et-tweets <?php echo esc_attr($atts['design']) . esc_attr($options['box_id']); ?><?php if($atts['design'] == 'slider') echo ' swiper-wrapper'; ?>">

                        <?php
                        foreach ( $options['tweets'] as $tweet ) { ?>
                            <li class="et-tweet <?php if($atts['design'] == 'slider') echo ' swiper-slide'; ?>">
                                <div class="twitter-info">
                                    <a href="<?php echo esc_url($tweet['permalink']); ?>" class="active" target="_blank">
                                        <?php echo '@'.$tweet['name']; ?>
                                    </a>
                                    <div class="media"><div class="media-body"><?php echo $tweet['text']; ?></div></div>
                                    <?php echo date("l M j \- g:ia",strtotime($tweet['time'])); ?>
                                </div>
                            </li>
                        <?php } ?>
                        </ul>
		        <?php if($atts['design'] == 'slider'): ?>
                    </div>
                </div>
			    <?php endif; ?>
			</div>

        <style>

        </style>
			
        <?php

        unset($atts);
        unset($options);

        return ob_get_clean();
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

		$posts_data_transient_name = 'etheme-twitter-feed-wpb-posts-data-' . sanitize_title_with_dashes( $settings['tweets_type'] . $settings['username'] . $settings['limit'] );
		$readyTweets = maybe_unserialize( base64_decode( get_transient( $posts_data_transient_name ) ) );

		if ( ! $readyTweets || isset($_GET['et_clear_twitter_catch']) ) {

			if ( empty($settings['username']) ) {
				echo '<p>'.
				     esc_html__('Please, enter Username', 'xstore-core') .
				     '</p>';
				return false;
			}

			$readyTweets = $connection->get(
				'statuses/user_timeline',
				array(
					'count' => $settings['limit'],
					'screen_name' => $settings['username']
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
				'time'      => $tweet->created_at,
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
