<?php 
/**
 * Summary of namespace Imjolwp\Admin\Settings
 */
namespace Imjolwp\Admin\Settings;

class Imjolwp_Ai_Automation_For_Wordpress_Scheduled_Post_list{
    /**
	 * Display The Scheduled Post List.
	 *
	 * @since 1.0.0
	 */

     public function imjolwp_ai_scheduled_events_list() {
		?>
		<div class="wrap">
			<h1>ImjolWP AI Post Schedule Event List</h1>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th>Title</th>
						<th>Word Count</th>
						<th>Language</th>
						<th>Focus Keywords</th>
						<th>Post Status</th>
						<th>Post Type</th>
						<th>Author</th>
						<th>Scheduled Time</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$cron_jobs = _get_cron_array();
					$found = false;
	
					foreach ($cron_jobs as $timestamp => $events) {
						foreach ($events as $hook => $details) {
							if ($hook === 'ai_content_generate_event') {
								foreach ($details as $event) {
									$args = $event['args'];
									$found = true;
									?>
									<tr>
										<td><?php echo esc_html($args[0]); ?></td>
										<td><?php echo esc_html($args[1]); ?></td>
										<td><?php echo esc_html($args[2]); ?></td>
										<td><?php echo esc_html($args[3]); ?></td>
										<td><?php echo esc_html($args[4]); ?></td>
										<td><?php echo esc_html($args[5]); ?></td>
										<td><?php echo esc_html(get_userdata($args[6])->display_name); ?></td>
										<td><?php echo esc_html(gmdate('Y-m-d H:i:s', $timestamp + 6 * 60 * 60)); ?></td>
										<td>
											<a href="?page=imjolwp-ai-scheduled-posts&delete=<?php echo esc_attr($timestamp); ?>" class="button button-danger">Delete</a>
										</td>
									</tr>
									<?php
								}
							}
						}
					}
	
					if (!$found) {
						echo '<tr><td colspan="9">No scheduled posts found.</td></tr>';
					}
					?>
				</tbody>
			</table>
	
			<?php
			// Delete scheduled event
			if (isset($_GET['delete'])) {
				$delete_timestamp = intval($_GET['delete']);
				
				// Retrieve the scheduled event
				$cron_jobs = _get_cron_array();
				if (isset($cron_jobs[$delete_timestamp]['ai_content_generate_event'])) {
					foreach ($cron_jobs[$delete_timestamp]['ai_content_generate_event'] as $event) {
						wp_unschedule_event($delete_timestamp, 'ai_content_generate_event', $event['args']);
					}
				}
			
				echo '<div class="updated"><p>Scheduled post deleted.</p></div>';
				
				// Redirect to avoid duplicate deletion on refresh
				echo '<script>window.location.href="?page=imjolwp-ai-scheduled-posts";</script>';
			}			
			?>
		</div>
		<?php
	}
}