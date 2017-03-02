  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">{$loginHeader}</h4>
  </div>
  <div class="modal-body">
    <form id="login_form">
      <div class="form-group">
        <label for="username">{$username}</label>
        <input type="text" class="form-control" id="username" placeholder="{$username}">
      </div>
      <div class="form-group">
        <label for="password">{$password}</label>
        <input type="password" class="form-control" id="password" placeholder="{$password}">
      </div>
      
    </form>
    <center><p class="bg-danger"><b>{$warning}</b></p></center>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{$cancel}</button>
    <button type="button" class="btn btn-primary" id="login">{$login}</button>
  </div>
