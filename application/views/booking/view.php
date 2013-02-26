<div class="span8">
  <h2 class="page-header">Booking Details</h2>

  <nav class="clearfix">
    <div class="pull-left">
      <h3><?= $data->course_code ? "$data->course_code ($data->additionObjective)" : $data->additionObjective ?></h3>
    </div>
    <div class="pull-right">
      <?php if (($data->approveStatus != 1 and $data->user_id == $this->session->userdata('user_id')) or $this->session->userdata('userrole_id') == 1) { ?>
      <a class="btn" href="<?= site_url('bookings/edit/'.$data->id) ?>"><i class="icon-pencil"></i> Edit Booking</a>
      <?php } ?>
      <?php if ($data->user_id == $this->session->userdata('user_id')) { ?>
      <a class="btn" href="<?= site_url('bookings/print/'.$data->id) ?>"><i class="icon-print"></i> Print</a>
      <?php } ?>
      <?php if ($data->approveStatus == 0 and $this->session->userdata('user_id') == 1) { ?>
      <form class="btn-toolbar inline" action="<?= site_url('bookings/pending') ?>" method="POST" accept-charset="utf-8">
        <div class="btn-group">
          <button class="btn btn-small btn-success" name="accept" value="<?= $data->id ?>" type="submit"><i class="icon-ok"></i> Accept</button>
          <button class="btn btn-small btn-danger" name="reject" value="<?= $data->id ?>" type="submit"><i class="icon-remove"></i> Reject</button>
        </div>
      </form>
      <?php } ?>
    </div>
  </nav>

  <section class="">
    <?php $isOneEvent = $data->isEverySun and $data->isEveryMon and $data->isEveryTue and $data->isEveryWed and $data->isEveryThu and $data->isEveryFri and $data->isEverySat ?>
    <p>Objective: <?= $data->bookingObjective ?></p>

    <p>
      Booking <?= $isOneEvent ? 'Date' : 'Range'.': ' ?><?= $data->startDate == $data->endDate ? date('j M Y', strtotime($data->startDate)) : date('j M Y', strtotime($data->startDate)).' ~ '.date('j M Y', strtotime($data->endDate)) ?></p>
    <?php if (!$isOneEvent) { ?>
    <p>Every:
      <?php foreach (array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat') as $day) { ?>
        <?php $dayAttr = 'isEvery'.$day ?>
        <?= $data->$dayAttr ? $day.' ' : '' ?>
        <?php } ?>
    </p>
    <?php } ?>
    <p>Time: <?= $data->startTime.' ~ '.$data->endTime ?></p>
    <p class="<?= $data->approveStatus == 0 ? 'text-warning' : ($data->approveStatus == 1 ? 'text-success' : 'text-error') ?>">Booking Status: <?= $data->approveStatus == 1 ? 'Accepted' : ($data->approveStatus == 0 ? 'Pending...' : 'Rejected') ?></p>

    <p>Booker: <?= $data->name ?></p>

    <table class="table table-striped">
      <thead>
      <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Room</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($timeslots as $timeslot) { ?>
      <tr>
        <td><?= date('j M Y', strtotime($timeslot->startDateTime)) ?></td>
        <td><?= date('H:i', strtotime($timeslot->startDateTime)).' ~ '.date('H:i', strtotime($timeslot->endDateTime)) ?></td>
        <td><?= $timeslot->name ?></td>
        <?php if (count($timeslots) > 1 and ($this->session->userdata('user_id') == $data->user_id or $this->session->userdata('userrole_id') == 1)) { ?>
        <td>
          <?php $startDateTime = strtotime($timeslot->startDateTime) ?>
          <?php $endDateTime = strtotime($timeslot->endDateTime) ?>
          <a href="<?= site_url("bookings/deallocate/$timeslot->room_id/$startDateTime/$endDateTime") ?>"
             class="btn btn-small btn-danger" onclick="return confirm('Are you sure ?')"><i class="icon-remove"></i>
            Deallocate</a>
        </td>
        <?php } ?>
      </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>
</div>