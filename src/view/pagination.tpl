<div class="pagination flex">
	{$first_url}<a href="{$first_url}" class="paging-button">First</a>{/$first_url}
  {$prev_url}<a href="{$prev_url}" class="paging-button">Previous</a>{/$prev_url}
	<!-- START BLOCK: page -->
	<a href="{$url}"{$current} class="current"{/$current}>{$page}</a>
	<!-- END BLOCK: page -->
  {$next_url}<a href="{$next_url}" class="paging-button">Next</a>{/$next_url}
	{$last_url}<a href="{$last_url}" class="paging-button">Last</a>{/$last_url}
</div>
