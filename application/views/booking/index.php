<div class="span8">
  <h2 class="page-header">My Booking List</h2>

  <nav class="nav">
    <a class="btn btn-success" href="<?= site_url('bookings/create') ?>"><i class="icon-plus"></i> Create Booking</a>
  </nav>

  <?= $this->load->view('booking/bookingList', true) ?>
</div>