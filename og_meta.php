			<!-- OG协议 -->
	<meta property="og:site_name" content="<?php $this->options->title() ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php $this->permalink() ?>" />
	<meta property="og:title" content="<?php $this->title() ?>" />
	<meta property="og:description" content="<?php $this->description(); ?>" />
	<meta property="og:image" content="<?php echo get_ArticleThumbnail($this); ?>" />
	<meta property="og:category" content="<?php $this->category(',', false); ?>" />
	<meta property="article:author" content="<?php $this->author(); ?>" />
	<meta property="article:publisher" content="<?php $this->options->siteUrl(); ?>" />
	<meta property="article:published_time" content="<?php $this->date('c'); ?>" />
	<meta property="article:published_first" content="<?php $this->options->title() ?>, <?php $this->permalink() ?>" />
	<meta property="article:tag" content="<?php $this->keywords(',');?>" />