<div class="span9 offset1 row-fluid">
  <h2 class="page-header">Room Finding</h2>

  <form class="form-horizontal">
    <div class="control-group">
      <label class="control-label" for="room">Room</label>

      <div class="controls">
        <input type="search" name="room" id="room" maxlength="256"/>
      </div>
    </div>
  </form>

  <div id="roomList" class="span4">

  </div>

  <div id="roomResult" class="span4">
    <h3 class="page-header">Coming up</h3>

  </div>
</div>

<script type="text/javascript">
  var rooms = <?= $jsonRooms ?>;
  var roomList = $("div#roomList");

  $("input#room").on('keyup', function () {
    roomList.html('');
    $.each(rooms, function(i, element) {
      var input = $("input#room").val();
      if (element.name.indexOf(input) == 0) {
        roomList.html(roomList.html() + element.name + '<br />');
      }
    });
  });
</script>