<form name="semesterForm" class="form-horizontal" accept-charset="utf-8" method="POST">

  <fieldset>
    <div class="control-group <?= form_error('year') ? 'error' : '' ?>">
      <label for="year" class="control-label">B.C. Year *</label>

      <div class="controls">
        <input type="text" name="year" id="year" <?= $method === 'edit' ? 'disabled="disabled"' : '' ?>
               value="<?= set_value('year', isset($data->year) ? $data->year : '') ?>" pattern="[1-9][0-9]{3}"/>
        <span class="help-inline"><?= form_error('year') ?></span>
      </div>
    </div>

    <div class="control-group <?= form_error('semesterPeriod') ? 'error' : '' ?>">
      <label for="semesterPeriod" class="control-label">Semester *</label>

      <div class="controls">
        <?php if (!empty($semesterPeriods)) { ?>
        <select name="semesterPeriod" id="semesterPeriod" <?= $method === 'edit' ? 'disabled="disabled"' : '' ?>>
          <?php foreach ($semesterPeriods as $semesterPeriod) { ?>
          <option value="<?= $semesterPeriod->id ?>" <?= (set_value('semesterPeriod') == $semesterPeriod->id or (isset($data->semesterPeriod_id) and $data->semesterPeriod_id == $semesterPeriod->id)) ? 'selected="selected"' : '' ?>><?= $semesterPeriod->name ?></option>
          <?php } ?>
        </select>
        <?php } ?>
        <span class="help-inline"><?= form_error('semesterPeriod') ?></span>
      </div>
    </div>

    <div class="control-group <?= form_error('startDate') ? 'error' : '' ?>">
      <label for="startDate" class="control-label">Start Date *</label>

      <div class="controls">
        <input type="text" name="startDate" id="startDate" class="datepicker" value="<?= set_value('startDate', isset($data->startDateTime) ? $data->startDateTime : '') ?>"/>
        <span class="help-inline"><?= form_error('startDate') ?></span>
      </div>
    </div>

    <div class="control-group <?= form_error('endDate') ? 'error' : '' ?>">
      <label for="endDate" class="control-label">End Date *</label>

      <div class="controls">
        <input type="text" name="endDate" id="endDate" class="datepicker" value="<?= set_value('endDate', isset($data->endDateTime) ? $data->endDateTime : '') ?>"/>
        <span class="help-inline"><?= form_error('endDate') ?></span>
      </div>
    </div>
  </fieldset>

  <div class="controls">
    <input type="submit" class="btn btn-primary" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?= site_url('structure/semesters') ?>" class="btn btn-danger">Discard</a>
  </div>
</form>

<script type="text/javascript">
  var startDate = $("#startDate").datepicker({
    format:'yyyy-mm-dd'
  }).on('changeDate', function(e) {
        var date = new Date(e.date);
        if (e.date.valueOf() > endDate.date.valueOf()) {
          endDate.setDate(date + 1);
        }
        startDate.hide();
        $("#endDate")[0].focus();
      }).data('datepicker');

  var endDate = $("#endDate").datepicker({
    format:'yyyy-mm-dd',
    onRender: function (date) {
      return date.valueOf() <= startDate.date.valueOf() ? 'disabled' : '';
    }
  }).on('changeDate',function () {
        endDate.hide();
      }).data('datepicker');
</script>