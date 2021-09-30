<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?= lang('sign in'); ?></title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body>
  <div class="login-nav-home white-box">
    <div class="p15">
      <a title="<?= lang('home'); ?>" class="logo" href="/">AGOUTI</a>
    </div>
    <div class="pt15 pr15 pb5 pl15 max-width">
      <form class="" action="/login" method="post">
        <?php csrf_field(); ?>
        <div class="mb20">
          <label for="email" class="size-15">E-mail</label>
          <input type="text" placeholder="<?= lang('enter'); ?>  e-mail" name="email" id="email">
        </div>
        <div class="mb20">
          <label for="password" class="size-15"><?= lang('password'); ?></label>
          <input type="password" placeholder="<?= lang('enter your password'); ?>" name="password" id="password">
        </div>
        <div class="mb20">
          <input type="checkbox" id="rememberme" name="rememberme" value="1">
          <label id="rem-text" class="form-check-label size-15" for="rememberme">
            <?= lang('remember me'); ?>
          </label>
        </div>
        <div class="mb20">
          <button type="submit" class="button-primary pt10 pr15 pb10 pl15 size-13 white">
            <?= lang('sign in'); ?>
          </button>
        </div>
      </form>
      <br>
    </div>
  </div>
</body>

</html>