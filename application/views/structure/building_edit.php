<div class="span8">
<h2 class="page-header">Building Editing</h2>

<form class="form-horizontal" accept-charset="utf-8" method="POST">
  <fieldset>
    <legend>Building Editing</legend>

    <div class="control-group <?= form_error('buildingname') ? 'error' : '' ?>">
      <label class="control-label" for="buildingname">Building Name *</label>

      <div class="controls">
        <input type="text" name="buildingname" id="buildingname" maxlength="128" required="required" value="<?= set_value('buildingname') ?>" />
        <span class="help-inline"><?= form_error('buildingname') ?></span>
      </div>
    </div>

    <div class="controls">
      <input class="btn btn-primary" type="submit" name="edit" value="Edit"/>
    </div>
  </fieldset>
</form>
</div>