<div class="span8">
  <h2 class="page-header">Room List</h2>

  <div class="nav">
    <a href="<?= site_url('structure/rooms/create') ?>" class="btn btn-success"><i class="icon-plus"></i> Create
      Room</a>
  </div>

  <?php if (!empty($rooms)) { ?>
  <table class="table table-striped">
    <thead>
    <tr>
      <th>Name</th>
      <th>Type</th>
      <th>Building</th>
      <th>Image</th>
      <th></th>
    </tr>
    </thead>
    <tbody>
      <?php foreach ($rooms as $room) { ?>
    <tr>
      <td><?= $room->name ?></td>
      <td><?= $room->roomtype ?></td>
      <td><?= $room->building ?></td>
      <td>
        <?php if ($room->imageURI) { ?>
        <button class="btn btn-small imgPreview"
                data-content="<img src='<?= $this->room->getImageURI($room->id) ?>' />">Preview
        </button>
        <?php } ?>
      </td>
      <td class="span2">
        <a class="btn btn-small" href="<?= site_url("structure/rooms/edit/$room->id") ?>"><i class="icon-pencil"></i>
          Edit</a>
        <a class="btn btn-small btn-danger" href="<?= site_url("structure/rooms/delete/$room->id") ?>"
           onclick="return confirm('Are you sure ?')">Delete</a>
      </td>
    </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php } ?>

  <?php if ($roomsPage) { ?>
  <div class="pagination">
    <ul>
      <?php if ($page != 0) { ?>
      <li><a href="<?= site_url('structure/rooms/').$page-1 ?>">&laquo;</a></li>
      <?php } ?>
      <?php for ($i = 0; $i < $roomsPage; ++$i) { ?>
      <li><a href="<?= site_url("structure/rooms/$i") ?>">$i+1</a></li>
      <?php } ?>
      <?php if ($page + 1 < $roomsPage) { ?>
      <li><a href="<?= site_url('structure/rooms/').$page+1 ?>">&raquo;</a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
</div>

<script type="text/javascript">
  $(".imgPreview").popover({
    placement:'top',
    title:'Image Preview',
    trigger:'hover',
    html:true
  });
</script>