<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

      {{-- Dashboard --}}
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
          <i class="icon-grid menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Dashboard')}}</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="icons">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{route('admin-dashboard')}}">TimeSheet</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{route('timesheets.show')}}">TimeSheet Detail</a></li>
          </ul>
        </div>
      </li>

      {{-- Request --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('requests.index')}}">
          <i class="icon-contract menu-icon"></i>
          <span class="menu-title">Request</span>
        </a>
      </li>

      {{-- Statistical --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('statisticals.index')}}">
          <i class="icon-bar-graph menu-icon"></i>
          <span class="menu-title">Statistical</span>
        </a>
      </li>

      {{-- History --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('historis.index')}}">
          <i class="icon-columns menu-icon"></i>
          <span class="menu-title">History</span>
        </a>
      </li>
      
      {{-- <li class="nav-item">
        <a class="nav-link"  href="#charts" >
          <i class="icon-bar-graph menu-icon"></i>
          <span class="menu-title">Charts</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="charts">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
          <i class="icon-contract menu-icon"></i>
          <span class="menu-title">Icons</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="icons">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
          <i class="icon-ban menu-icon"></i>
          <span class="menu-title">Error pages</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="error">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
          </ul>
        </div>
      </li> --}}
      {{-- User --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('users.index')}}">
          <i class="icon-head menu-icon"></i>
          <span class="menu-title">User</span>
        </a>
      </li>

      {{-- Position --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('positions.index')}}">
          <i class="icon-layout menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Position')}}</span>
        </a>
      </li>

      {{-- Department --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('departments.index')}}">
          <i class="icon-grid-2 menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Department')}}</span>
        </a>
      </li>

      {{-- Staff --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('staff.index')}}">
          <i class="icon-paper menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Staff')}}</span>
        </a>
      </li>

      {{-- Get Time --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('timesheets.get-time')}}">
          <i class=" menu-icon"></i>
          <span class="menu-title">-----</span>
        </a>
      </li>

    </ul>
  </nav>