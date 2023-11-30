<div id="rightside">
  <div id="rightside-config-hide" class="">
    <button id="font-plus" type="button" title="放大字体"><i class="fas fa-plus"></i></button>
    <button id="font-minus" type="button" title="缩小字体"><i class="fas fa-minus"></i></button>
    <?php if ($this->is('post')) : ?>
      <button id="readmode" type="button" title="阅读模式">
        <i class="fas fa-book-open"></i>
      </button>
    <?php endif ?>
    <button id="translateLink" type="button" title="简繁转换">
      繁
    </button>
    <button id="darkmode" type="button" title="浅色和深色模式转换">
      <i class="fas fa-adjust">
      </i>
    </button>
    <button id="hide-aside-btn" type="button" title="单栏和双栏切换">
      <i class="fas fa-arrows-alt-h">
      </i>
    </button>
  </div>
  <div id="rightside-config-show">
    <button id="rightside_config" type="button" title="设置">
      <i class="fas fa-cog fa-spin">
      </i>
    </button>
    <?php if ($this->is('post')) : ?>
      <button class="close" id="mobile-toc-button" type="button" title="目录">
        <i class="fas fa-list-ul">
        </i>
      </button>
    <?php endif ?>
    <?php if ($this->is('post') && $this->allow('comment') || $this->is('page') && $this->allow('comment')) : ?>
      <a id="to_comment" href="#comments" title="直达评论">
        <i class="fas fa-comments">
        </i>
      </a>
    <?php endif ?>
    <button id="go-up" type="button" title="回到頂部" class="show-percent">
      <span class="scroll-percent"></span>
      <i class="fas fa-arrow-up">
      </i>
    </button>
  </div>
</div>