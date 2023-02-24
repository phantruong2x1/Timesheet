<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo mr-5" href="{{route('client-dashboard')}}"><img src="https://digitran.asia/wp-content/themes/digitran_wp/assets/images/image-digitran-logo.png" class="ml-4" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
      <ul class="navbar-nav mr-lg-2">
        <li class="nav-item nav-search d-none d-lg-block">

        </li>
      </ul>
      <ul class="navbar-nav navbar-nav-right">
        
        <li class="nav-item nav-profile dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
            <img src="{{asset('assets/images/faces/digitran-team1.jpg')}}" alt="profile"/>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
            <h6 class="dropdown-header">Settings</h6>
            <a href="{{route('client.settings.getStaff')}}" class="dropdown-item" >
              <i class="ti-user text-primary"></i>
              Staff Information
            </a>
            <a href="{{route('client.settings.change-password')}}" class="dropdown-item" >
              <i class="ti-key text-primary"></i>
              Change Password 
            </a>
            <button data-toggle="modal" data-target="#createFeekback" id="btnFeedback" class="dropdown-item">
              <i class="ti-info-alt text-primary"></i>
              Feedback
            </button>
            {{-- <a href="#"  id="btnFeedback" class="dropdown-item" >
              <i class="ti-info-alt text-primary"></i>
              Feedback
            </a> --}}
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item">
              <i class="ti-power-off text-primary"></i>
              Logout
            </a>
          </div>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="icon-menu"></span>
      </button>
    </div>
</nav>

<div class="modal" id="createFeekback">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Feedback</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          @csrf
          <div class="form-group">
            <label for="exampleInputTitle" ><b>Title</b></label>
            <input type="text" class="form-control"  id="exampleInputTitle" 
            name="title"  value="{{old('title')}}" placeholder="Bạn muốn góp ý về vấn đề gì?">
            {{-- Thông báo lỗi --}}
            @error('title')
            <span style="color: red">{{$message}}</span>
            @enderror
          </div>
  
          <div class="form-group">
            <label for="exampleInputContent" ><b>Content</b></label>
            <textarea name="content" class="form-control" id="exampleInputContent"
            placeholder="Nội dung góp ý là gì?" rows="5" value="{{old('content')}}"></textarea>
            {{-- Thông báo lỗi --}}
            @error('title')
            <span style="color: red">{{$message}}</span>
            @enderror
          </div>
          <p style="color: rgb(147, 149, 150)">Cảm ơn vì sự đóng góp!</p>
        </div>
        <div class="modal-footer">
          <button id="addFeedback" class="btn btn-success">Save</button>
        </form>
        {{-- <a href="{{route('client.settings.post-feekback')}}" class="btn btn-success">Save</a> --}}
      </div>
    </div>
  </div>
</div>
<script>
  let createFeekback = document.getElementById('addFeedback');

  createFeekback.addEventListener('click', function(){
    event.preventDefault();

    let title = document.getElementById('exampleInputTitle').value;
    let content = document.getElementById('exampleInputContent').value;

    $.ajax({
      type: 'POST',
      url: "{{route('client.settings.post-feekback')}}",
      data: {
        title: title,
        content: content
      },
      success: function(data){
        console.log(data);
        window.alert('Thêm thành công!');
      }
      
    });
    
  });

</script>
