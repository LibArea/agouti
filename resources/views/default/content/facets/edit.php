<?= insert('/_block/add-js-css');
$fs = $data['facet_inf'];
$url = url('redirect.facet', ['id' => $fs['facet_id']]);
?>

<main>
  <div class="indent-body">
    <div class="nav-bar">
      <ul class="nav">
        <?= insert(
          '/_block/navigation/nav',
          [
            'list' => [
              [
                'id'        => 'topic',
                'url'       => url('facet.form.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
                'title'     => 'app.edit_' . $data['type'],
              ], [
                'id'        => 'team',
                'url'       => url('team.form.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
                'title'     => 'app.team',
              ]
            ]
          ]
        ); ?>
      </ul>
      <a class="gray-600" href="<?= $url; ?>"><?= __('app.go_to'); ?></a>
    </div>

    <form class="max-w780" action="<?= url('edit.facet', ['type' => $fs['facet_type']], method: 'post'); ?>" method="post" enctype="multipart/form-data">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/facet-type', ['type' => $fs['facet_type']]); ?>

      <div class="file-upload mb10 mt15" id="file-drag">
        <div class="flex">
          <?= Img::image($fs['facet_img'], $fs['facet_title'], 'img-xl', 'logo', 'max'); ?>
          <img id="file-image" class="img-xl br-gray bg-white">
        </div>
        <div id="start" class="mt10">
          <input class="text-xs" id="file-upload" type="file" name="images" accept="image/*" />
          <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>
      <?php if ($fs['facet_type'] == 'blog') : ?>
        <div class="file-upload mb20" id="file-drag">
          <div class="flex3">
            <?php if ($fs['facet_cover_art']) : ?>
              <div class="mr20">
                <img src="<?= Img::cover($fs['facet_cover_art'], 'blog'); ?>" class="w-100 br-gray">
                <input type="hidden" name="cover" value="<?= $fs['facet_cover_art']; ?>">
              </div>
            <?php endif; ?>
            <div class="mt10" id="start">
              <input class="text-xs" id="file-upload" type="file" name="cover" accept="image/*" />
              <div id="notimage" class="none">Please select an image</div>
            </div>
          </div>
          <div id="response" class="hidden">
            <div id="messages"></div>
          </div>
        </div>
      <?php endif; ?>
      <div class="mb20">
        <?= Html::sumbit(__('app.download')); ?>
      </div>
      <hr>
      <fieldset>
        <label for="facet_title"><?= __('app.title'); ?><sup class="red">*</sup></label>
        <input minlength="3" maxlength="64" type="text" name="facet_title" value="<?= htmlEncode($fs['facet_title']); ?>">
        <div class="help">3 - 64 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_seo_title"><?= __('app.title'); ?> (SEO)<sup class="red">*</sup></label>
        <input minlength="4" maxlength="255" type="text" name="facet_seo_title" value="<?= $fs['facet_seo_title']; ?>">
        <div class="help">> 3 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_slug"><?= __('app.slug'); ?><sup class="red">*</sup></label>
        <input minlength="3" maxlength="32" type="text" name="facet_slug" value="<?= $fs['facet_slug']; ?>">
        <div class="help">3 - 32 <?= __('app.characters'); ?> (a-z-0-9)</div>
      </fieldset>

      <?php if ($fs['facet_type'] != 'blog' && $container->user()->admin()) : ?>

        <?= insert('/_block/form/select/low-facets', [
          'data'          => $data,
          'action'        => 'edit',
          'type'          => $fs['facet_type'],
          'title'         => __('app.children'),
          'help'          => __('app.necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php endif; ?>

      <?php if (!empty($data['high_arr'])) : ?>
        <h4 class="uppercase-box"><?= __('app.parents'); ?></h4>
        <?php foreach ($data['high_arr'] as $high) : ?>
          <a class="flex relative mt5 mb10 items-center hidden gray" href="<?= $url; ?>">
            <?= Img::image($high['facet_img'], $high['value'], 'img-base mr5', 'logo', 'max'); ?>
            <?= $high['value']; ?>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>

      <fieldset>
        <label for="facet_description"><?= __('app.meta_description'); ?><sup class="red">*</sup></label>
        <textarea class="add max-w780" rows="6" minlength="3" name="facet_description"><?= $fs['facet_description']; ?></textarea>
        <div class="help">> 3 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_short_description"><?= __('app.short_description'); ?><sup class="red">*</sup></label>
        <input minlength="11" maxlength="120" value="<?= $fs['facet_short_description']; ?>" type="text" required="" name="facet_short_description">
        <div class="help">> 3 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <?= __('app.information'); ?> (sidebar / info)<sup class="red">*</sup>
        <textarea class="add max-w780 block" rows="6" name="facet_info"><?= $fs['facet_info']; ?></textarea>
        <div class="mb20 help">Markdown, > 14 <?= __('app.characters'); ?></div>

        <?php if ($fs['facet_type'] != 'blog') : ?>
          <?= insert('/_block/form/select/related-posts', ['data' => $data]); ?>

          <?= insert('/_block/form/select/low-matching-facets', [
            'data'          => $data,
            'action'        => 'edit',
            'type'          => $fs['facet_type'],
            'title'         => __('app.bound_children'),
            'help'          => __('app.necessarily'),
            'red'           => 'red'
          ]); ?>
      </fieldset>
    <?php endif; ?>

    <?php if ($fs['facet_type'] == 'topic') : ?>
      <fieldset>
        <input type="checkbox" name="facet_is_comments" <?php if ($fs['facet_is_comments'] == 1) : ?>checked <?php endif; ?>> <?= __('app.facet_comments_disabled'); ?>
      </fieldset>
    <?php endif; ?>

    <?php if ($container->user()->admin()) : ?>
      <?= insert('/_block/form/select/user', ['user' => $data['user']]); ?>
    <?php endif; ?>

    <fieldset>
      <input type="hidden" name="facet_id" value="<?= $fs['facet_id']; ?>">
      <?= Html::sumbit(__('app.edit')); ?>
    </fieldset>
    </form>
  </div>
</main>
<aside>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.edit_' . $data['type']); ?>
  </div>
</aside>