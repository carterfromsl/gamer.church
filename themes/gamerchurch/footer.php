</main>
<?php $disable_sidebar = get_post_meta(get_the_ID(), 'disable_sidebar', true);
if (!$disable_sidebar) { get_sidebar(); } ?>
</div>
<footer id="footer" role="contentinfo">
	<div class="footer-widgets">
		<div class="footer-left">
			<?php dynamic_sidebar( 'footer-widget-left' ); ?>
		</div>
        <div class="footer-mid">
			<?php dynamic_sidebar( 'footer-widget-mid' ); ?>
		</div>
		<div class="footer-right">
			<?php dynamic_sidebar( 'footer-widget-right' ); ?>
		</div>
	</div>
<div id="copyright">
&copy; <?php echo esc_html( date_i18n( __( 'Y', 'gamerchurch' ) ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
	<a href="https://console.gamer.church/"><img src="/wp-content/uploads/2024/12/church-pixel.gif" alt="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" /></a>
	<span class="sep"> | </span> <a href="/privacy/">Privacy Policy</a>
	</div>
</footer>
</div>
<div id="bg"></div>
<?php wp_footer(); ?>
</body>
</html>
