<?php
$menus = <<<EOF
[
  [
    {
      "n": "pending",
      "d": "Pending List"
    },
    {
      "n": "history",
      "d": "Booking History"
    }
  ]
]
EOF;

$uri = uri_string();
?>

<aside class="span3">
  <h3>Structure Config</h3>

  <?php foreach (json_decode($menus) as $menu) { ?>
  <ul class="nav nav-tabs nav-stacked">
    <li <?= 'bookings' == uri_string() ? 'class="active"' : '' ?>>
      <a href="<?= site_url('bookings') ?>">My Booking List <?= 'bookings' == uri_string() ? '<i class="icon-chevron-right pull-right"></i>' : '' ?></a>
    </li>
    <?php foreach ($menu as $m) {
    $matched = preg_match('/^(bookings\/'.$m->n.')/', uri_string()) ? 'active' : ''
    ?>

    <li class="<?= $matched ? 'active' : '' ?>">
      <a href="<?= site_url('bookings/'.$m->n) ?>"><?= $m->d ?><?= $matched ? '<i class="icon-chevron-right pull-right"></i>' : '' ?></a>
    </li>
    <?php } ?>
  </ul>
  <?php } ?>
</aside>