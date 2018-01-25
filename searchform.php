<form method="get" class="searchform input-group" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" value="<?php the_search_query(); ?>" placeholder="<?php _e( 'Search', 'gordo' ); ?>" name="s" class="s form-control" required="required" />
    <span class="input-group-addon">
        <button type="submit" class="searchsubmit">
            <i class="fa fa-search"></i><span><?php _e( 'Search', 'gordo' ); ?></span>
        </button>
    </span>

</form>