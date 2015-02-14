<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">

<title><?php if(isset($data['titlebar'])) {
	echo $data['titlebar'];
} elseif(isset($data['title'])) {
	echo $data['title'] . " - " . "Samoan Language Resources";
} else {
	echo "Samoan Language Resources";
} ?>
</title>
<link rel="stylesheet" type="text/css" media="all"
	href="<?php echo self::$config['webroot']; ?>style/style.min.css">
<link rel="stylesheet" type="text/css" media="all"
	href="<?php echo self::$config['webroot']; ?>style/ui-lightness/jquery-ui-1.9.2.custom.min.css">
<script type="text/javascript"
	src="<?php echo self::$config['webroot']; ?>js/jquery-1.8.3.js"></script>
</head>

<body class="two-column content-sidebar">

	<div id="wrapper">
		<div id="header">
			<div id="header_inner">
				<ul class="nav sf-menu">
					<li class="current_page_item"><a
						href="<?php  echo self::$config['webroot']; ?>">Home</a></li>
					<li class="page_item"><a
						href="<?php  echo self::$config['webroot']; ?>guide">Language
							guide</a>
						<ul class="children">
							<li class="page_item"><a
								href="<?php  echo self::$config['webroot']; ?>pronounce">Pronunciation</a>
							</li>
							<li class="page_item"><a
								href="<?php  echo self::$config['webroot']; ?>grammar">Grammar</a>
								<ul class="children">
									<li><a
										href="<?php echo self::$config['webroot']; ?>02-describing">Describing
											Objects</a></li>
									<li><a href="<?php echo self::$config['webroot']; ?>03-verbs">Using
											Verbs</a></li>
									<li><a
										href="<?php echo self::$config['webroot']; ?>04-questions">Questions</a>
									</li>
									<li><a
										href="<?php echo self::$config['webroot']; ?>05-numbers-time">Numbers
											and Time</a></li>
									<li><a
										href="<?php echo self::$config['webroot']; ?>06-advanced">Advanced</a>
									</li>
									<li><a href="<?php echo self::$config['webroot']; ?>07-respect">Respect</a>
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
					<li class="page_item"><a
						href="<?php  echo self::$config['webroot']; ?>about">About</a></li>
				</ul>

				<form id="search-form" method="get"
					action="<?php  echo self::$config['webroot']; ?>word/search/">
					<input value="Search for a word"
						onfocus="if (this.value == 'Search for a word' ) {this.value = '';}"
						onblur="if (this.value == '' ) {this.value = 'Search for a word';}"
						name="s" id="s" type="text"> <input id="search-submit"
						value="Search" type="submit">
				</form>
			</div>
		</div>

		<div id="content">
			<div id="title">
				<h1>
					<a href="<?php  echo self::$config['webroot']; ?>"><?php
					if(isset($data['title'])) {
						echo core::escapeHTML($data['title']);
					} else {
						echo "Samoan Language Resources";
		} ?>
					</a>
				</h1>
			</div>


			<div id="maincontent">
				<div id="maincontent_inner">
					<div class="post">
						<? include($view_template); ?>
					</div>
				</div>

			</div>
			<div id="sidebar" class="sidebar">

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
								<li><a
									href="<?php echo self::$config['webroot']; ?>books">Books</a>
								</li>
								<li><a href="<?php echo self::$config['webroot']; ?>about">About</a>
								</li>
							</ul>
						</div>
						<div class="widget">
							<h3 class="widget-title">Meta</h3>
							<ul>
								<li><a href="<?php echo self::$config['webroot']; ?>contribute">How to contribute</a></li>
								<li><a href="<?php echo self::$config['webroot']; ?>todo">Todo list</a></li>
								<li><a href="<?php echo self::$config['webroot']; ?>dev">Development page</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div id="footer">
			<div>
				<a href="#wrapper" id="top-link">↑ Top</a> <a
					href="<?php  echo core::constructURL('page', 'view', array('about'), 'html'); ?>">about
					this project</a> | <a href="http://bitrevision.com">bitrevision</a>
				|
				<?php 
				if($user = session::getUser()) {
					echo "logged in as ".core::escapeHTML($user['user_name']) . " (<a href=\"" . core::constructURL('user', 'logout', array(''), 'html') . "\">logout</a>)";
					} else {
						echo "<a href=\"" . core::constructURL('user', 'login', array(''), 'html') . "\">log in</a>";
					}
					
					
					?>
			</div>
		</div>

	</div>
	<script type="text/javascript"
		src="<?php echo self::$config['webroot']; ?>js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript">
		function audio_play(id) {
			document.getElementById(id).play();
		}
	</script>
</body>
</html>

