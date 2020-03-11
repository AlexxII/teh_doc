<div class="complex-wiki-default">
  <div class="col-lg-12 col-md-12" id="wiki-content">

  </div>
</div>

<script>
  // загрузка контента
  $(document).ready(function () {
    var node = $("#tools-main-tree").fancytree("getActiveNode");
    var toolId = node.data.id;
    var url = '/equipment/tool/wiki/content?id=' + toolId;
    $.ajax({
      type: 'GET',
      url: url,
      success: function (response) {
        $('#wiki-content').html(response.data.data);
      },
      error: function (response) {
        console.log(response);
      }
    });
  });


</script>