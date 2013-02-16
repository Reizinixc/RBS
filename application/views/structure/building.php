<div class="span8">
  <h2 class="page-header">Building List</h2>

  <form class="form-inline input-append" action="<?= site_url('structure/buildings') ?>" method="POST" accept-charset="utf-8">
    <input type="text" name="buildingname" required="required" placeholder="Building Name" maxlength="128" />
    <button type="submit" class="btn btn-success"><i class="icon-plus"></i> Create Building</button>
  </form>

  <table class="table table-striped">
    <thead>
    <tr>
      <th>Building Name</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($buildings)) { ?>
    <tr>
      <td colspan="2">Cannot find any building. Would you like to create one?</td>
    </tr>
      <?php } else { ?>
      <?php foreach ($buildings as $building) { ?>
      <tr>
        <td><?= $building->name ?></td>
        <td class="span2">
          <a class="btn btn-small" href="<?= site_url("structure/buildings/edit/$building->id") ?>"><i class="icon-pencil"></i> Edit</a>
          <a class="btn btn-small btn-danger" href="<?= site_url("structure/buildings/delete/$building->id") ?>" onclick="return confirm('Are you sure ?')">Delete</a>
        </td>
      </tr>
        <?php } ?>
      <?php } ?>
    </tbody>
  </table>
</div>