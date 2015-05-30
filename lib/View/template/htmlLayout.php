<?php
namespace SmWeb;

/* All of site */
$webroot = self::$config['webroot'];
$siteTitle = "Samoan Language Resources";
/* This page only */
$pageTitle = $data['title'];
$pagePermalink = $data['url'];
/* For the tab title */
$titleLong = isset($data['titlebar']) ? $data['titlebar'] : (isset($data['title']) ? $data['title'] . " - $siteTitle" : $siteTitle);

function text($text) {
	echo Core::escapeHTML($text);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width"/><!-- for mobile -->
	<title><?php text($titleLong); ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php text(self::$config['webroot']); ?>style/style.min.css" />
</head>
<body class="home blog">
	<div id="nav">
		<div class="nav-inside">
			<div id="menus">
				<ul id="menus-dt" class="menus-dt">
					<li class="current_page_item"><a href="<?php text($webroot); ?>">Home</a></li>
										<li class="page_item"><a
						href="<?php  echo self::$config['webroot']; ?>guide">Language
							guide</a>
						<ul class="children">
							<li class="page_item"><a
								href="<?php  echo self::$config['webroot']; ?>pronounce">Pronunciation</a>
							</li>
							<li class="page_item page_item_has_children"><a
								href="<?php  echo self::$config['webroot']; ?>grammar">Grammar</a>
								<ul class="children">
									<li class="page_item"><a
										href="<?php echo self::$config['webroot']; ?>02-describing">Describing
											Objects</a></li>
									<li class="page_item"><a href="<?php echo self::$config['webroot']; ?>03-verbs">Using
											Verbs</a></li>
									<li class="page_item"><a
										href="<?php echo self::$config['webroot']; ?>04-questions">Questions</a>
									</li>
									<li class="page_item"><a
										href="<?php echo self::$config['webroot']; ?>05-numbers-time">Numbers
											and Time</a></li>
									<li class="page_item"><a
										href="<?php echo self::$config['webroot']; ?>06-advanced">Advanced</a>
									</li>
									<li class="page_item"><a href="<?php echo self::$config['webroot']; ?>07-respect">Respect</a>
									</li>
								</ul>
							</li>
							<li class="page_item"><a
								href="<?php  echo self::$config['webroot']; ?>phrases">Phrases</a>
							</li>
							<li class="page_item"><a
								href="<?php  echo self::$config['webroot']; ?>word/">Vocabulary</a>
							</li>

						</ul></li>
					<li class="page_item"><a href="<?php text($webroot . "about"); ?>">About</a></li>
				</ul>
				<ul id="menus-m" class="menus-m">
					<li>Menu</li>
				</ul>
			</div>
			<div id="search">
				<form id="searchform" method="get" action="<?php text($webroot) ?>word/search/">
					<input type="text" value="Search for a word"
						onfocus="if (this.value == 'Search for a word') {this.value = '';}"
						onblur="if (this.value == '') {this.value = 'Search for a word';}"
						size="35" maxlength="50" name="s" id="s" /> <input type="submit"
						id="searchsubmit" value="SEARCH" />
				</form>
			</div>
		</div>
	</div>
	<div id="header">
		<div class="site_title">
			<h1>
				<a href="<?php text($webroot); ?>"><?php text($siteTitle); ?></a>
			</h1>
			<div class="clear"></div>
		</div>
	</div>
	<div id="wrapper">
		<div id="content">
			<div class="post post-19 type-post status-publish format-standard hentry category-uncategorized tag-boat tag-lake" id="post-19">
				<!-- post div -->
				<?php if($pagePermalink != $webroot) { ?>
				<h2 class="title">
					<a href="<?php text($pagePermalink); ?>" title="Permalink to <?php text($pageTitle); ?>"><?php text($pageTitle); ?></a>
				</h2>
				<?php } ?>
				<div class="clear"></div>
				<div class="entry">
					<?php include($view_template); ?>
				</div>
				<!-- END entry -->
				<?php
				if(isset(self::$config['footer']) && self::$config['footer'] !== false) {
					include(self::$config['footer']);
				} ?>
			</div>
			<!-- END post -->
		</div>
		<!--content-->
		<div id="sidebar-border">
			<div id="sidebar">
				<div class="sidebar-border active" id="primary-widget-area">
					<div class="sidebar-inner">

						<div class="widget">
							<h3 class="widget-title">Navigate</h3>
							<ul>
								<li><a href="<?php echo self::$config['webroot']; ?>home">Home</a>
								</li>
								<li><a href="<?php echo self::$config['webroot']; ?>guide">Language
										guide</a>
									<ul>
										<li class="page_item"><a
											href="<?php  echo self::$config['webroot']; ?>pronounce">Pronunciation</a>
										</li>
										<li class="page_item"><a
											href="<?php  echo self::$config['webroot']; ?>grammar">Grammar</a>
										</li>
										<li class="page_item"><a
											href="<?php  echo self::$config['webroot']; ?>phrases">Phrases</a>
										</li>
										<li class="page_item"><a
											href="<?php  echo self::$config['webroot']; ?>word/">Vocabulary</a>
										</li>
									</ul>
								</li>
								<li><a href="<?php echo self::$config['webroot']; ?>songs">Songs</a>
								</li>
								<li><a href="<?php echo self::$config['webroot']; ?>books">Books</a>
								</li>
								<li><a href="<?php echo self::$config['webroot']; ?>about">About</a>
								</li>
							</ul>
						</div>
						<div class="widget">
							<h3 class="widget-title">Meta</h3>
							<ul>
								<li><a href="<?php echo self::$config['webroot']; ?>contribute">How
										to contribute</a></li>
								<li><a href="<?php echo self::$config['webroot']; ?>todo">Todo
										list</a></li>
								<li><a href="<?php echo self::$config['webroot']; ?>dev">Development
										page</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- end: #sidebar -->
		</div>
		<!-- end: #sidebar-border -->
	</div>
	<!--wrapper-->
	<div class="clear"></div>
<div id="footer">
	<div id="footer-inside">
		<p>
		<a href="<?php text(Core::constructURL('page', 'view', array('about'), 'html')); ?>">about this project</a> |
		<?php 
			if($user = Session::getUser()) {
				echo "logged in as ";
				text(Core::escapeHTML($user['user_name']));
				echo " (<a href=\"";
				text(Core::constructURL('user', 'logout', array(''), 'html'));
				echo "\">logout</a>)";
			} else {
				echo "<a href=\"" . Core::constructURL('user', 'login', array(''), 'html') . "\">log in</a>";
			}
		?>
		</p>
		<span id="back-to-top">&uarr; <a href="#" rel="nofollow" title="Back to top">Top</a></span>
	</div>
</div><!--footer-->

<script type="text/javascript">
	//////// Handles toggling the navigation menu for small screens
	( function() {
		var nav = document.getElementById( 'menus' ), button = document.getElementById( 'menus-m' ), menu = document.getElementById( 'menus-dt' );
		if ( ! nav ) {
			return;
		}
		if ( ! button ) {
			return;
		}
		// Hide button if menu is missing or empty.
		if ( ! menu || ! menu.childNodes.length ) {
			button.style.display = 'none';
			return;
		}
		button.onclick = function() {
			if ( -1 !== button.className.indexOf( 'b-toggled-on' ) ) {
				button.className = button.className.replace( ' b-toggled-on', '' );
				menu.className = menu.className.replace( ' toggled-on', '' );
			} else {
				button.className += ' b-toggled-on';
				menu.className += ' toggled-on';
			}
		};
	} )();

	/* Custom code */
	function audio_play(id) {
		document.getElementById(id).play();
	}
</script>
</body>
</html>