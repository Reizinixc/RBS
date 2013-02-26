<div class="span9 offset1 row-fluid">
  <h2 class="page-header">Room Finding</h2>

  <form class="form-horizontal">
    <div class="control-group">
      <label class="control-label" for="room">Room</label>

      <div class="controls">
        <input type="search" name="room" id="room" maxlength="256" autocomplete="off" />
      </div>
    </div>
  </form>

  <div class="span3">
    <table class="table">
      <thead>
      <tr>
        <th>Room</th>
      </tr>
      </thead>
      <tbody id="roomList">

      </tbody>
    </table>
  </div>

  <div id="roomResult" class="span6">
  </div>
</div>

<script type="text/javascript">
  var rooms = <?= $jsonRooms ?>;
  var roomList = $("#roomList");

  $("input#room").on('keyup', function () {
    roomList.html('');
    $.each(rooms, function(i, element) {
      var input = $("input#room").val().toLowerCase();
      if (element.name.toLowerCase().indexOf(input) == 0) {
        roomList.append('<tr class="nav"><td><a class="loadRoomList" href="#" onclick="return loadRoom(' + element.id + ');">' + element.name + '</a>&nbsp;&nbsp;&nbsp;<small>' + element.buildingName + '</small></td></tr>');
      }
    });
  });

  function loadRoom(room_id) {
    $.post('<?= site_url("index/loadRoom/") ?>', {"room_id": room_id} , function(data) {
      $("div#roomResult").html(data);
    });
    return false;
  }
</script>