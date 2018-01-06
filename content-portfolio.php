    <figure>
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail('post-thumbnail-portfolio'); ?>
        <?php endif; ?>
        <figcaption>
            <a class="post-title" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><h3><?php the_title(); ?></h3></a>

            <div class="post-metas portfolio-post-metas">
                <p class="term-metas">
                    <?php 
                    if ( has_category() ) {
                        ?>
                        <p class="post-meta post-categories"><?php the_category(', '); ?></p>
                        <?php
                    }
                    if ( has_tag() ) {
                        ?>
                        <p class="post-meta post-tags"><?php the_tags('', ', '); ?></p>
                        <?php 
                    } 
                    ?>
                </p>
            </div>
        </figcaption>
    </figure>