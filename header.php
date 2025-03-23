<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header_com.php'); ?>
<style>
#page-header{
    background-image: url(<?php $this->options->headerimg();?>);
} 
<?php if (!empty($this->options->mobileHeaderImg)): ?>
@media screen and (max-width: 768px) {
    #page-header{
        background-image: url(<?php $this->options->mobileHeaderImg();?>);
    }
}
<?php endif; ?>
</style>
<body style="zoom: 1;">
    <div id="web_bg"></div>
<div class="page" id="body-wrap">
<?php if (is_array($this->options->beautifyBlock) && in_array('ShowTopimg',$this->options->beautifyBlock)): ?>
<header class="full_page" id="page-header">
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