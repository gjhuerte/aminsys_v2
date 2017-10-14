<div class="modal fade" id="changeAccessLevelModal" tabindex="-1" role="dialog" aria-labelledby="changeAccessLevelModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <ul style='margin-left: 10px;'>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
          </ul>
        </div>
      @endif
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 style="color:#337ab7;">Supply Inventory</h3>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="text" class="form-control"
          <button type="button" id="search" class="btn btn-primary btn-lg btn-block">Search</button>
        </div>
      </div> <!-- end of modal-body -->
    </div> <!-- end of modal-content -->
  </div>
</div>
<script>
  $(document).ready(function(){

  });
</script>
