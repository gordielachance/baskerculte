<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" value="<?php the_search_query(); ?>" placeholder="<?php _e( 'Search form', 'gordo' ); ?>" name="s" class="s" />
    <button type="submit" class="searchsubmit">
        <i class="fa fa-search"></i><span><?php _e( 'Search', 'gordo' ); ?></span>
    </button>
</form>