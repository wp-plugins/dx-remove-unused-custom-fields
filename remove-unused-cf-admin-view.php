<div class="wrap">
<h2>Remove Unused Custom Fields</h2>

<?php if( !empty( $message ) ) {?>
<div class="ruc-message" style="background: #FFFBCC; width: 100%;"><?php echo $message; ?></div>
<?php } ?>

<p>Delete all custom fields from non-existing posts</p>

<h4>Warning: clicking "Delete" would remove all post meta for the post IDs below permanently from the database!</h4>

<?php if( ! empty( $post_ids ) && is_array( $post_ids ) ) { 
	$wp_nonce = wp_nonce_field('delete-cfs', 'delete-cfs-nonce');
	?>
	<p>Post IDs found in the <?php $wpdb->postmeta ?> table without corresponding posts in <?php echo $wpdb->posts; ?>:</p>
	<div class="ruc-posts-list">
	<?php echo implode(',', array_values( $post_ids ) ); ?>
	
	<p>Press the "Delete" button if you want to remove all postmeta for these post IDs.</p>
	<form action="" method="POST">
		<div><input type="submit" name="delete" value="Delete" /></div>
		<?php echo $wp_nonce; ?>
	</form> 
	</div>					
<?php  } ?>
</div>
	  