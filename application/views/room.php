<div>
  <section>
    <h3 class="help-inline"><?= $rooms->name ?></h3><h4 class="help-inline">@ <?= $rooms->building ?></h4>
    <div class="clearfix">
      <p class="pull-left">Room Type: <?= $rooms->roomType ?>, Capacity: <?= $rooms->capacity ?></p>
      <?php if ($this->session->userdata('user_id') != null) { ?>
      <a href="<?= site_url("bookings/create/$rooms->id") ?>" class="btn btn-small btn-success pull-right"><i class="icon-plus"></i> Create Booking</a>
      <?php } ?>
    </div>
  </section>

  <?php if (!empty($bookingDetails)) { ?>
  <table class="table table-striped">
    <thead>
    <tr>
      <th>Start <i class="icon-chevron-up"></i></th>
      <th>End</th>
      <th>Activity Name</th>
      <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($bookingDetails as $bookingDetail) { ?>
      <tr class="<?= $bookingDetail->approveStatus == 0 ? 'warning' : ($bookingDetail->approveStatus == 1 ? 'success' : 'error') ?>">
        <td><?= date('j M H:i', strtotime($bookingDetail->startDateTime)) ?></td>
        <td><?= date('j M H:i', strtotime($bookingDetail->endDateTime)) ?></td>
        <td><?= $bookingDetail->course_code ? "$bookingDetail->course_code ($bookingDetail->additionObjective)" : $bookingDetail->additionObjective ?></td>
        <td><a class="btn btn-small" title="Booking Details" href="<?= site_url("bookings/$bookingDetail->booking_id") ?>"><i class="icon-file"></i></a></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
  <?php } else { ?>
  <hr />
    <p>No booking request in this room.</p>
  <?php } ?>
</div>