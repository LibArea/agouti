<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7">
  <div class="bg-white br-rd-5 border-box-1 mb15 pt5 pr15 pb5 pl15 space-tags">
    <?php if ($data['link']['link_title']) { ?>
      <div class="right heart-link">
        <?= votes($uid['user_id'], $data['link'], 'link'); ?>
      </div>
      <h1><?= $data['link']['link_title']; ?>
        <?php if ($uid['user_trust_level'] > 4) { ?>
          <a class="size-14 ml5" title="<?= lang('edit'); ?>" href="<?= getUrlByName('link-edit', ['id' => $data['link']['link_id']]); ?>">
            <i class="icon-pencil size-15"></i>
          </a>
        <?php } ?>
      </h1>
      <div class="gray">
        <?= $data['link']['link_content']; ?>
      </div>
      <div class="gray">
        <a class="green" rel="nofollow noreferrer ugc" href="<?= $data['link']['link_url']; ?>">
          <?= favicon_img($data['link']['link_id'], $data['link']['link_url_domain']); ?>
          <?= $data['link']['link_url']; ?>
        </a>
        <span class="right"><?= $data['link']['link_count']; ?></span>
      </div>
    <?php } else { ?>
      <h1><?= lang('domain') . ': ' . $data['domain']; ?></h1>
    <?php } ?>
  </div>

  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('domain', ['domain' => $data['link']['link_url_domain']])); ?>
</main>
<aside class="col-span-3">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15 space-tags">
    <?php if (!empty($data['domains'])) { ?>
      <div class="uppercase mb5 mt5 size-14"><?= lang('domains'); ?></div>
      <?php foreach ($data['domains'] as  $domain) { ?>
        <a class="size-14 gray" href="<?= getUrlByName('domain', ['domain' => $domain['link_url_domain']]); ?>">
          <i class="icon-link middle"></i> <?= $domain['link_url_domain']; ?>
          <sup class="size-14"><?= $domain['link_count']; ?></sup>
        </a><br>
      <?php } ?>
    <?php } else { ?>
      <p><?= lang('there are no domains'); ?>...</p>
    <?php } ?>
  </div>
</aside>