<div id="equipment-wrap">
  <span id="tool-referens-id" style="display: none"><?= $toolId ?></span>
  <div id="tools-tree" style="padding-bottom: 10px">
    <div style="position: relative">
      <div class="small-nidden-btns visible-xs visible-sm">
        <button class="refresh-tools-tree">Обновить</button>
      </div>
      <div class="container-fuid" style="float:left; width: 100%">
        <input class="form-control form-control-sm" autocomplete="off" name="search" data-tree="tools-reference-tree"
               placeholder="Поиск по названию...">
      </div>
      <div style="padding-top: 8px; right: 10px; position: absolute">
        <a href="" class="btnResetSearch" data-tree="tools-reference-tree">
          <i class="fa fa-times-circle" aria-hidden="true" style="font-size:20px; color:#9d9d9d"></i>
        </a>
      </div>
    </div>

    <!-- дерево оборудования -->
    <div>
      <div style="border-radius:2px;padding-top:40px">
        <div id="tools-reference-tree" class="ui-draggable-handle"></div>
      </div>
    </div>
  </div>
</div>

<script>

  $(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    // отображение и логика работа дерева
    var main_url = '/equipment/default/all-tools';

    tree = $("#tools-reference-tree").fancytree({
      source: {
        url: main_url
      },
      expandParents: true,
      noAnimation: false,
      scrollIntoView: true,
      topNode: null,
      extensions: ['filter'],
      quicksearch: true,
      minExpandLevel: 3,
      wide: {
        iconWidth: '32px',     // Adjust this if @fancy-icon-width != '16px'
        iconSpacing: '6px', // Adjust this if @fancy-icon-spacing != '3px'
        labelSpacing: '6px',   // Adjust this if padding between icon and label !=  '3px'
        levelOfs: '32px'     // Adjust this if ul padding != '16px'
      },
      filter: {
        autoApply: true,                                    // Re-apply last filter if lazy data is loaded
        autoExpand: true,                                   // Expand all branches that contain matches while filtered
        counter: true,                                      // Show a badge with number of matching child nodes near parent icons
        fuzzy: false,                                       // Match single characters in order, e.g. 'fb' will match 'FooBar'
        hideExpandedCounter: true,                          // Hide counter badge if parent is expanded
        hideExpanders: true,                                // Hide expanders if all child nodes are hidden by filter
        highlight: true,                                    // Highlight matches by wrapping inside <mark> tags
        leavesOnly: false,                                   // Match end nodes only
        nodata: true,                                       // Display a 'no data' status node if result is empty
        mode: 'dimm'                                        // Grayout unmatched nodes (pass "hide" to remove unmatched node instead)
      },
      init: function (event, data) {
        var toolId = $('#tool-referens-id').text();
        data.tree.activateKey(toolId);
      },
    });

    $('#main-teh-tab li').click(function (e) {
      e.preventDefault();
      $('#main-teh-tab li').removeClass();
      $(this).addClass('active');
      var node = $("#tools-reference-tree").fancytree("getActiveNode");
      var ref = $(this).data("tabName");
      if (node != null) {
        var toolId = node.data.id;
        loadTabsData(ref, toolId);
      }
    })
  });

  function getNodeId() {
    var node = $("#tools-reference-tree").fancytree("getActiveNode");
    if (node) {
      return node.data.id;
    } else {
      return 1;
    }
  }

  function filterTree(tree, data) {
    var cc = 0;
    tree.filterBranches(function (node) {
      if (data[node.key] === 1) {
        return true;
      }
    });
  }

</script>
