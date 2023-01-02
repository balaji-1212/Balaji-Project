<div class="error">

	<p><?php echo esc_html( "Your environment doesn't meet the system requirements listed below. Therefore Xstore Core has been deactivated.", 'xstore-core'); ?></p>

	<ul class="ul-disc">
		<?php foreach ( $errors as $error ) : ?>
			<li>
				<strong><?php echo esc_html( $error->error_message ); ?></strong>
				<em><?php echo esc_html( $error->supportive_information ); ?></em>
			</li>
		<?php endforeach; ?>
	</ul>

</div>
