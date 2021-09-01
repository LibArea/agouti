<?php if ($uid['user_id'] == 0) { ?>
  <div class="banner bg-blue-100 hidden size-15">
    <div class="pt15 pb5">
      <h1 class="red size-21"><?= Lori\Config::get(Lori\Config::PARAM_BANNER_TITLE); ?></h1>
      <div class="pb15"><?= Lori\Config::get(Lori\Config::PARAM_BANNER_DESC); ?>...</div>
    </div>
  </div>
<?php } ?>

<div class="wrap">
  <main class="telo">
    <?php
    $pages = array(
      array('id' => 'feed', 'url' => '/', 'content' => lang('Feed')),
      array('id' => 'all', 'url' => '/all', 'content' => lang('All'), 'auth' => 'yes'),
      array('id' => 'top', 'url' => '/top', 'content' => lang('Top')),
    );
    echo tabs_nav($pages, $data['sheet'], $uid);
    ?>

    <?php if (Request::getUri() == '/' && $uid['user_id'] > 0 && empty($data['space_user'])) { ?>
      <div class="white-box">
        <div class="pt5 pr15 pb5 pl15 big center gray">
          <i class="icon-lightbulb middle red"></i>
          <span class="middle"><?= lang('space-subscription'); ?>...</span>
        </div>
      </div>
    <?php } ?>

    <?php includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  </main>
  <aside>
    <?php if ($uid['user_id']) { ?>
      <?php if (!empty($data['space_user'])) { ?>
        <div class="white-box pt5 pr15 pb5 pl15">
          <a class="right" title="<?= lang('Spaces'); ?>" href="/spaces">
            <i class="icon-right-open-big middle size-13"></i>
          </a>
          <div class="uppercase mb5 mt5 size-13">
            <?= lang('Signed'); ?>
          </div>
          <?php foreach ($data['space_user'] as  $sig) { ?>
            <a class="flex relative pt5 pb5 hidden gray" href="/s/<?= $sig['space_slug']; ?>" title="<?= $sig['space_name']; ?>">
              <?= spase_logo_img($sig['space_img'], 'small', $sig['space_name'], 'ava-24 mr5'); ?>
              <span class="ml5 size-13"><?= $sig['space_name']; ?></span>
              <?php if ($sig['space_user_id'] == $uid['user_id']) { ?>
                <sup class="red mr5 ml5">+</sup>
              <?php } ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?php includeTemplate('/_block/login'); ?>
    <?php } ?>

    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm white-box sticky pt5 pr15 pb5 pl15">
        <?php foreach ($data['latest_answers'] as  $answer) { ?>
          <div class="mt15 mr0 mb15 ml0 pl15" style="border-left: 2px solid <?= $answer['space_color']; ?>;">
            <div class="size-13">
              <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava'); ?>
              <span class="ml5"></span>
              <?= $answer['answer_date']; ?>
            </div>
            <a class="gray" href="<?= post_url($answer); ?>#answer_<?= $answer['answer_id']; ?>">
              <?= $answer['answer_content']; ?>...
            </a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </aside>
</div>