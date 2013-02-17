<?php
$menus = <<<EOF
[
  [
    {
      "n": "rooms",
      "d": "Rooms"
    },
    {
      "n": "buildings",
      "d": "Buildings"
    },
    {
      "n": "semesters",
      "d": "Semesters"
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
    <?php foreach ($menu as $m) {
    $matched = preg_match('/^(structure\/'.$m->n.')/', uri_string()) ? 'active' : ''
    ?>

    <li class="<?= $matched ? 'active' : '' ?>">
      <a href="<?= site_url('structure/'.$m->n) ?>"><?= $m->d ?><?= $matched ? '<i class="icon-chevron-right pull-right"></i>' : '' ?></a>
    </li>
    <?php } ?>
  </ul>
  <?php } ?>
</aside>