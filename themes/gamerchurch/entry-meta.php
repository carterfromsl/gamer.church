<div class="entry-meta">
    <span class="author vcard"<?php if ( is_single() ) { echo ' itemprop="author" itemscope itemtype="https://schema.org/Person">'; } else { echo '>'; } ?>
        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-link">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); // Display the author's profile picture ?>
            <span class="author-name"<?php if ( is_single() ) { echo ' itemprop="name">'; } else { echo '>'; } ?>
                <?php the_author(); ?>
            </span>
        </a>
    </span>
    <span class="meta-sep"> | </span>
    <time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" title="<?php echo esc_attr( get_the_date() ); ?>" <?php if ( is_single() ) { echo 'itemprop="datePublished" pubdate'; } ?>>
        <?php the_time( get_option( 'date_format' ) ); ?>
    </time>
    <?php if ( is_single() ) { echo '<meta itemprop="dateModified" content="' . esc_attr( get_the_modified_date() ) . '">'; } ?>

<?php if ( comments_open() ) { ?>
    <span class="meta-sep"> | </span>
    <span class="comments-link">
        <a href="<?php echo esc_url( get_comments_link() ); ?>">
            <?php 
            $comments_number = get_comments_number();
            if ( $comments_number === 0 ) {
                esc_html_e( 'No Affirmations', 'gamerchurch' );
            } elseif ( $comments_number === 1 ) {
                esc_html_e( 'One (1) Amen', 'gamerchurch' );
            } else {
                printf( esc_html( _n( '%s Amen', '%s Hallelujahs!', $comments_number, 'gamerchurch' ) ), number_format_i18n( $comments_number ) );
            }
            ?>
        </a>
    </span>
<?php } ?>

</div>
