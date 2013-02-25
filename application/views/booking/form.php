<?= validation_errors('<div class="alert alert-error">', '</div>') ?>

<form class="form-horizontal" method="POST" accept-charset="utf-8">
  <div class="control-group">
    <label class="control-label" for="startDate">Start</label>
    <div class="controls">
      <div class="input-append">
        <input class="span2 datepicker" type="text" name="startDate" id="startDate" autocomplete="off"
        pattern="[1-9][0-9]{3}-[0-9]{1,2}-[0-9]{1,2}" required="required" value="<?= set_value('startDate', $data->startDate) ?>"/>
        <span class="add-on"><i class="icon-calendar"></i></span>
      </div>
      <div class="input-append bootstrap-timepicker">
        <input class="span1" type="text" name="startTime" id="startTime" autocomplete="off"
        pattern="[0-9]{1,2}:[0-9]{1,2}(:[0-9]{1,2})?" required="required" value="" />
        <span class="add-on"><i class="icon-time"></i></span>
      </div>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="endDate">End</label>
    <div class="controls">
      <div class="input-append">
        <input class="span2 datepicker" type="text" name="endDate" id="endDate" autocomplete="off"
        pattern="[1-9][0-9]{3}-[0-9]{1,2}-[0-9]{1,2}" required="required" value="<?= set_value('endDate', $data->endDate) ?>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
      </div>
      <div class="input-append bootstrap-timepicker">
        <input class="span1" type="text" name="endTime" id="endTime" autocomplete="off"
        pattern="[0-9]{1,2}:[0-9]{1,2}(:[0-9]{1,2})?" required="required" value="<?= set_value('endTime', $data->endTime) ?>" />
        <span class="add-on"><i class="icon-time"></i></span>
      </div>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Every</label>
    <div class="controls">
      <?php foreach (array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat') as $day) { ?>
      <?php $dayAttr = "isEvery".$day ?>
      <label class="checkbox inline">
        <input type="checkbox" name="<?= $dayAttr ?>" value="1" <?= set_checkbox($dayAttr, 1, $data->$dayAttr) ?> /> <?= $day ?>
      </label>
      <?php } ?>
      <br />
      <br />
      <button id="everyDay" type="button" class="btn btn-small">Day</button>
      <button id="everyMWF" type="button" class="btn btn-small">Mon Wed Fri</button>
      <button id="everyTT" type="button" class="btn btn-small">Tue Thu</button>&nbsp;&nbsp;&nbsp;&nbsp;
      <button id="everyNone" type="button" class="btn btn-small btn-warning"><i class="icon-remove"></i> Clear</button>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Rooms</label>

<!--    --><?php //$bookingRoom = set_value('rooms', empty($bookingRooms) ? array(0) : $bookingRooms) ?>
    <?php $bookingRoom = set_value('rooms', $bookingRooms[0]); ?>
    <div class="controls">
      <div id="roomList">
<!--        --><?php //foreach ($bookingRooms as $bookingRoom) { ?>
        <select name="rooms">
          <?php foreach (json_decode($jsonRooms) as $room) { ?>
          <option value="<?= $room->id ?>" <?= $room->id == $bookingRoom ? 'selected="selected"' : '' ?>><?= $room->name ?></option>
          <?php } ?>
        </select><br />
<!--        --><?php //} ?>
      </div>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Objective</label>

    <div class="controls">
      <select name="bookingObjective" required="required">
      <?php $bookingObjectiveOri = set_value('bookingObjective', $data->bookingObjective_id) ?>;
      <?php foreach ($bookingObjectives as $bookingObjective) { ?>
        <option value="<?= $bookingObjective->id ?>" <?= $bookingObjective->id == $bookingObjectiveOri ? 'selected="selected"' : '' ?>><?= $bookingObjective->name ?></option>
      <?php } ?>
      </select>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="course_code">Course Code</label>

    <div class="controls">
      <input type="text" id="course_code" pattern="[0-9]{6,8}" maxlength="8" value="<?= form_error('course_code') ? form_error('course_code') : $data->course_code ?>" />
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="additionObjective">Activity Name</label>

    <div class="controls">
      <input type="text" id="additionObjective" name="additionObjective" <?= false ? 'required="required"' : '' ?> maxlength="64" value="<?= set_value('additionObjective', $data->additionObjective) ?>" />
    </div>
  </div>

  <div class="control-group">
    <div class="controls">
      <input type="submit" class="btn btn-primary" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="<?= site_url('bookings') ?>" class="btn btn-danger">Discard</a>
    </div>
  </div>
</form>

<script type="text/javascript">
  var nowTemp = new Date();
  var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

  var startDate = $("input#startDate").datepicker({
    format: 'yyyy-mm-dd',
    onRender: function(date) {
      return date.valueOf() < now.valueOf() ? 'disabled' : '';
    }
  }).on('changeDate', function(e) {
        if (e.date.valueOf() > endDate.date.valueOf()) {
          endDate.setValue(new Date(e.date));
        }
      }).data('datepicker');

  var endDate = $("input#endDate").datepicker({
    format: 'yyyy-mm-dd',
    onRender: function(date) {
      return date.valueOf() < startDate.date.valueOf() ? 'disabled' : '';
    }
  }).on('changeDate', function(e) {
        endDate.hide();
      }).data('datepicker');

  $("input#startTime").timepicker({
    template: 'dropdown',
    minuteStep: 5,
    showMeridian: false,
    defaultTime: '<?= set_value('startTime', $data->startTime) ?>'
  });

  $("input#endTime").timepicker({
    template: 'dropdown',
    minuteStep: 5,
    showMeridian: false,
    defaultTime: '<?= set_value('endTime', $data->endTime) ?>'
  });

  var rooms = <?= $jsonRooms ?>;

  $(document).ready(function() {

    $("button#everyDay").on('click', function(e) {
      $("input[name^='isEvery']").prop('checked', 'checked');
    });

    $("button#everyMWF").on('click', function(e) {
      $("input[name='isEveryMon']").prop('checked', 'checked');
      $("input[name='isEveryWed']").prop('checked', 'checked');
      $("input[name='isEveryFri']").prop('checked', 'checked');
    });

    $("button#everyTT").on('click', function(e) {
      $("input[name='isEveryTue']").prop('checked', 'checked');
      $("input[name='isEveryThu']").prop('checked', 'checked');
    });

    $("button#everyNone").on('click', function(e) {
      $("input[name^='isEvery']").prop('checked', false);
    });
  });

  var abbrCodes = <?= $jsonCourses ?>;
  var inputCourseCode = $("input#course_code");

  $(document).ready(function() {
    inputCourseCode.on('keyup', function(e) {
      $("label[for='additionObjective']").text($(this).val().length > 0 ? 'Abbr. Course Name' : 'Activity Name');
    });

    inputCourseCode.on('blur', function(e) {
      var code = $("input#course_code").val();
      if (code.length != 0) {
        for (var i in abbrCodes) {
          if (code == abbrCodes[i].code) {
            $("input#additionObjective").val(abbrCodes[i].abbrName);
            break;
          }
        }
      }
    });
  });
</script>