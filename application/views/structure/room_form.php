<form class="form-horizontal" accept-charset="utf-8" method="POST">
  <fieldset>
    <div class="control-group <?= form_error('name') ? 'error' : '' ?>">
      <label for="name" class="control-label">Name *</label>

      <div class="controls">
        <input class="span3" type="text" name="name" id="name" value="<?= set_value('name', isset($data->name) ? $data->name : '') ?>" required="required" maxlength="64" />
        <span class="help-inline"><?= form_error('name') ?></span>
      </div>
    </div>

    <div class="control-group <?= form_error('building') ? 'error' : '' ?> ?>">
      <label for="building" class="control-label">Building *</label>

      <div class="controls">
        <?php if (!empty($buildings)) { ?>
        <select class="span3" name="building" id="building" required="required">
          <?php foreach ($buildings as $building) { ?>
          <option value="<?= $building->id ?>" <?= (set_value('semesterPeriod') == $building->id or (isset($data->building_id) and $data->building_id == $building->id)) ? 'selected="selected"' : '' ?>><?= $building->name ?></option>
          <?php } ?>
        </select>
        <?php } ?>
        <span class="help-inline"><?= form_error('building') ?></span>
      </div>
    </div>

    <div class="control-group <?= form_error('building') ? 'error' : '' ?> ?>">
      <label for="roomType" class="control-label">Type *</label>

      <div class="controls">
        <?php if (!empty($roomtypes)) { ?>
        <select class="span3" name="roomType" id="roomType" required="required">
          <?php foreach ($roomtypes as $roomtype) { ?>
          <option value="<?= $roomtype->id ?>" <?= (set_value('roomType') == $roomtype->id or (isset($data->roomType_id) and $data->roomType_id == $roomtype->id)) ? 'selected="selected"' : '' ?>><?= $roomtype->name ?></option>
          <?php } ?>
        </select>
        <?php } ?>
        <span class="help-inline"><?= form_error('roomType') ?></span>
      </div>
    </div>

    <div class="control-group <?= form_error('capacity') ? 'error' : '' ?>">
      <label for="capacity" class="control-label">Capacity *</label>

      <div class="controls">
        <input class="span3" type="number" name="capacity" id="capacity" value="<?= set_value('capacity', isset($data->capacity) ? $data->capacity : '') ?>" required="required" min="1" />
        <span class="help-inline"><?= form_error('capacity') ?></span>
      </div>
    </div>
  </fieldset>

  <div class="controls">
    <input type="submit" class="btn btn-primary" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?= site_url('structure/rooms') ?>" class="btn btn-danger">Discard</a>
  </div>
</form>