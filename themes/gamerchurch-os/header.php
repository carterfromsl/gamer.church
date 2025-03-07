<?php ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>html,body{min-height:100%;overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;font-size:16px;line-height:1.25em;margin:0}
</style>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>><?php wp_body_open(); ?>
	<div class="page-wrap">

<div id="page">
	<div id="t-head" class="site-title">
		<div id="t-info"><span>?</span>
			<div class="t-infobox">
				<h1><?php bloginfo( 'name' ); ?></h1>
				<?php $description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-desc"><?php echo $description; ?></p>
				<?php endif; ?>
				<?php echo tos_custom_logo() ?>
			</div>
		</div>
		<div id="t-load"><span>Load</span>
			<ul>
				<li class="dropdown">Index
					<ul>
						<li><a href="#c-terminal" onclick="openCol('c-terminal')">Terminal</a></li>
						<li><a href="#c-minegame" onclick="openCol('c-minegame')">MineGame</a></li>
						<li><a href="#c-system" onclick="openCol('c-system')">System Info</a></li>
					</ul>
				</li>
				<li class="index"><a href="/">Index</a></li>
				<li><a href="/gallery/">Gallery</a></li>
				<li><a href="/music/">Music</a></li>
				<li><a href="#c-debug" onclick="openCol('c-debug')">Debug</a></li>
				<li id="btnDark" ><a href="#" onclick="setCookie('darkMode','1')">Dark Mode</a></li>
				<li id="btnLight" class="hidden"><a href="#" onclick="setCookie('darkMode','0')" >Light Mode</a></li>
			</ul>
		</div>
		<span id="getDate"></span><span id="clock"></span><span id="kFps"></span><span id="keyStroke"></span>
	</div>
					<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<p id="breadcrumbs">','</p>' ); }?>
