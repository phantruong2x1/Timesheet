<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      {{-- dashboard --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('client-dashboard')}}">
          <i class="ti-calendar menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Dashboard')}}</span>
        </a>
      </li>
     {{-- request --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('client.requests.index')}}">
          <i class="ti-comment-alt menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Request')}}</span>
        </a>
      </li>
      {{-- statistical --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('client.statisticals')}}">
          <i class="ti-bar-chart menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Statistical')}}</span>
        </a>
      </li>
    </ul>
  </nav>