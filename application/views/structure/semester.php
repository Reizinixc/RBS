<div class="span8" xmlns="http://www.w3.org/1999/html">
  <h2 class="page-header">Semester List</h2>

  <div class="nav">
    <a class="btn btn-success" href="<?= site_url('structure/semesters/create') ?>"><i class="icon-plus"></i> Create new
      Semester</a>
  </div>

  <?php if (!empty($semesters)) { ?>
  <table class="table table-striped">
    <thead>
    <tr>
      <th>Year</th>
      <th>Semester</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th></th>
    </tr>
    </thead>

    <tbody>
      <?php foreach ($semesters as $semester) { ?>
    <tr>
      <td><?= $semester->year ?></td>
      <td><?= $semester->name ?></td>
      <td><?= date('j F Y', strtotime($semester->startDateTime)) ?></td>
      <td><?= date('j F Y', strtotime($semester->endDateTime)) ?></td>
      <td class="span2">
        <a class="btn btn-small"
           href="<?= site_url("structure/semesters/edit/$semester->year/$semester->semesterPeriod_id") ?>"><i
            class="icon-pencil"></i> Edit</a>
        <a class="btn btn-small btn-danger"
           href="<?= site_url("structure/semesters/delete/$semester->year/$semester->semesterPeriod_id") ?>"
           onclick="return confirm('Are you sure ?')">Delete</a>
      </td>
    </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php } ?>
</div>