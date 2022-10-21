<?php  $this->need('header_com.php'); ?>
<body style="zoom: 1;">
    <div id="web_bg"></div>
<div class="page" id="body-wrap">
<?php if (is_array($this->options->beautifyBlock) && in_array('ShowTopimg',$this->options->beautifyBlock)): ?>
<header class="full_page" id="page-header"  style="background-image: url(<?php $this->options->headerimg() ?>)">
        <div id="site-info">
            <h1 id="site-title"><?php $this->options->description() ?></h1>
            <div id="site-subtitle">
                <span id="subtitle"></span>
            </div>
        </div>
        <div id="scroll-down"><i class="fas fa-angle-down scroll-down-effects"></i></div>
<?php else: ?>        
<header class="not-top-img" id="page-header">        
<?php endif; ?>      
<?php  $this->need('public/nav.php'); ?>
</header>