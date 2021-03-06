<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title><?= isset($title) ? $title : "" ?> &laquo; Room Booking Services</title>

  <link rel="stylesheet" href="<?= site_url('asset/css/bootstrap.min.css') ?>" type="text/css"/>
  <link rel="stylesheet" href="<?= site_url('asset/css/style_base.css') ?>" type="text/css"/>
  <script type="text/javascript" src="<?= site_url('asset/js/layout/jquery-1.9.1.min.js') ?>"></script>
  <script type="text/javascript" src="<?= site_url('asset/js/layout/bootstrap.min.js') ?>"></script>
  <?php
  if (!empty($stylesheets))
    foreach ($stylesheets as $stylesheet) {
      echo link_tag($stylesheet)."\n";
    }

  if (!empty($jsscripts))
    foreach ($jsscripts as $jsscript) {
      echo "<script type=\"text/javascript\" src=\"$jsscript\"></script>\n";
    }
  ?>
</head>

<body>
<nav id="mainmenu" class="navbar navbar-fixed-top navbar-inner">
  <div class="container">
    <div class="pull-left">
      <a class="brand" href="<?= site_url() ?>">Room Booking Service</a>
      <ul class="nav-collapse nav">
        <li <?= uri_string() == '' ? 'class="active"' : '' ?>><a href="<?= site_url() ?>">Room Finding</a></li>
        <?php if ($this->session->userdata('userrole_id') != null) { ?>
        <li <?= preg_match('/^bookings/', uri_string()) ? 'class="active"' : '' ?>><a
            href="<?= site_url('bookings') ?>">Bookings</a></li>
          <?php if ($this->session->userdata('userrole_id') == 1) { ?>
        <li <?= preg_match('/^structure/', uri_string()) ? 'class="active"' : '' ?>><a href="<?= site_url('structure') ?>">Structure
          Config</a></li>
          <?php } ?>
        <?php } ?>
      </ul>
    </div>
    <div id="accountPanel" class="pull-right">
      <ul class="nav">
        <?php if ($this->session->userdata('logined')) { ?>
        <li>
          <a href="http://gravatar.com/emails/">
            <img id="gravatar" alt="<?= $this->session->userdata('name') ?>'s avatar"
                 src="<?= gravatar($this->session->userdata('email'), 64, false, 'mm') ?>"
                 title="Change your avatar at gravatar.com"/>
            <span class="text-info"><?= $this->session->userdata('name') ?></span>
          </a>
        </li>
        <li><a href="<?= site_url('logout') ?>">Logout</a></li>
        <?php } else { ?>
        <li <?= uri_string() == 'login' ? 'class="active"' : '' ?>><a href="<?= site_url('login') ?>">Login</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div id="content" class="container">
  <?php
  $flashMsgs = $this->session->flashdata('msg');
  if (is_array($flashMsgs)) {
    $this->load->helper('flashmsg');
    foreach ($flashMsgs as $msg) {
      echo flashmsg($msg['type'], $msg['head'], $msg['msg']);
    }
  }
  ?>

  <?= $content ?>
</div>

<footer id="footer" class="container">
  <p class="muted"><?= date('Y') ?> Kasetsart University. Some rights reserved.</p>
</footer>
</body>
</html>