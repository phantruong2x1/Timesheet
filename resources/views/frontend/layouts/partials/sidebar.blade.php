<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{route('client-dashboard')}}">
          <i class="ti-calendar menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Dashboard')}}</span>
        </a>
      </li>
     
      <li class="nav-item">
        <a class="nav-link" href="{{route('client.requests.index')}}">
          <i class="ti-comment-alt menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Request')}}</span>
        </a>
      </li>
    </ul>
  </nav>