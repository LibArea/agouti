<?php $post   = $data['post']; ?>
<main class="col-two">
  <div class="box">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red"><?= __('edit.option', ['name' => __('pages')]); ?></span>

    <form action="<?= getUrlByName('content.change', ['type' => 'page']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <fieldset>
        <label for="post_title"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" value="<?= $post['post_title']; ?>" type="text" required="" name="post_title">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="post_slug"><?= __('Slug (URL)'); ?></label>
        <input minlength="3" maxlength="32" value="<?= $post['post_slug']; ?>" type="text" required="" name="post_slug">
        <div class="help">3 - 32 <?= __('characters'); ?></div>
      </fieldset>

      <?= Tpl::insert('/_block/form/select/blog', [
        'data'        => $data,
        'action'      => 'edit',
        'type'        => 'blog',
        'title'       => __('blogs'),
      ]); ?>

      <?php if (UserData::checkAdmin()) : ?>
        <?= Tpl::insert('/_block/form/select/section', [
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'section',
          'title'         => __('section'),
          'required'      => false,
          'maximum'       => 1,
          'help'          => __('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php endif; ?>

      <?= Tpl::insert('/_block/editor/editor', ['height'  => '300px', 'content' => $post['post_content'], 'type' => 'page-telo', 'id' => $post['post_id']]); ?>


      <?= Tpl::insert('/_block/form/select/user', [
        'uid'           => $user,
        'user'          => $data['user'],
        'action'        => 'user',
        'type'          => 'user',
        'title'         => __('author'),
        'help'          => __('necessarily'),
      ]); ?>

      <div class="mb20">
        <?php if ($post['post_draft'] == 1) : ?>
          <input type="hidden" name="draft" id="draft" value="1">
        <?php endif; ?>
        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
        <?= Html::sumbit(__('edit')); ?>
      </div>
    </form>
  </div>
</main>