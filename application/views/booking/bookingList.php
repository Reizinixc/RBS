<?php if (!empty($bookings)) { ?>
<table class="table table-striped">
  <thead>
  <tr>
    <th>Range</th>
    <th>Time</th>
    <th>Repeat</th>
    <th>Room</th>
    <th>For</th>
    <th>Course / Activity</th>
    <th></th>
  </tr>
  </thead>
  <tbody>
    <?php foreach ($bookings as $booking) { ?>
  <tr class="<?= !$booking->approveStatus ? 'warning' : ($booking->approveStatus == 1 ? 'success' : 'error') ?>">
    <td><?= $booking->startDate == $booking->endDate ? date('j M y', strtotime($booking->startDate)) : date('j M', strtotime($booking->startDate)).' ~ '.date('j M y', strtotime($booking->endDate)) ?></td>
    <td><?= substr($booking->startTime, 0, 5).' ~ '.substr($booking->endTime, 0, 5) ?></td>
    <td>
      <?php foreach (array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat') as $day) { ?>
      <?php $dayAttr = 'isEvery'.$day ?>
      <?= $booking->$dayAttr ? $day.' ' : '' ?>
      <?php } ?>
    </td>
    <td>
      <?php foreach ($booking->roomList ? json_decode($booking->roomList) : array() as $room) { ?>
      <?= $room.' ' ?>
      <?php } ?>
    </td>
    <td><?= $booking->bookingObjective ?></td>
    <td><?= $booking->course_code ? $booking->course_code.' ('.$booking->additionObjective.')' : $booking->additionObjective ?></td>
    <td>
      <a class="btn btn-small" href="<?= site_url("bookings/$booking->id") ?>" title="Booking Details"><i class="icon-file"></i></a>
      <?php if ($booking->approveStatus != 1 and $canEdit) { ?>
      <a class="btn btn-small" href="<?= site_url("bookings/edit/$booking->id") ?>" title="Edit"><i class="icon-edit"></i></a>
      <?php } ?>
    </td>
  </tr>
    <?php } ?>
  </tbody>
</table>
<?php } else { ?>
<div><?= $emptyMsg ?></div>
<?php } ?>