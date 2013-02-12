<div id="loginForm" class="span5 offset3">
  <form name="loginForm" method="POST" action="/login" class="form-horizontal" accept-charset="utf-8">
    <fieldset>
      <legend class="text-center">Please Login to continue</legend>
      <div class="control-group">
        <label class="control-label" for="username">Username *</label>

        <div class="controls">
          <input type="text" name="username" id="username" maxlength="16" required="required"/>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="password">Password *</label>

        <div class="controls">
          <input type="password" id="password" name="password" maxlength="64" required="required"/>
        </div>
      </div>
      <hr />
      <div class="controls">
        <input class="btn btn-primary" type="submit" name="submit" value="Login"/>
        <input class="btn" type="reset" value="Reset"/>
      </div>
    </fieldset>
  </form>
</div>