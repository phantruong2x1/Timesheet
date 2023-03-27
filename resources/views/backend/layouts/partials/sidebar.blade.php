<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

      {{-- Dashboard --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('admin-dashboard')}}">
          <i class="ti-dashboard menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      {{-- Timesheet Detail --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('timesheets.show')}}">
          <i class="ti-calendar menu-icon"></i>
          <span class="menu-title">Timesheet Detail</span>
        </a>
      </li>
      {{-- Request --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('requests.index')}}">
          <i class="ti-comment-alt menu-icon"></i>
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

      {{-- Payroll Cost --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('payroll-costs.index')}}">
          <i class="ti-money menu-icon"></i>
          <span class="menu-title">Payroll Cost</span>
        </a>
      </li>

      {{-- History --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('historis.index')}}">
          <i class="ti-time menu-icon"></i>
          <span class="menu-title">History</span>
        </a>
      </li>

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
          <i class="ti-briefcase menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Position')}}</span>
        </a>
      </li>

      {{-- Department --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('departments.index')}}">
          <i class="ti-archive menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Department')}}</span>
        </a>
      </li>

      {{-- Staff --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('staff.index')}}">
          <i class="ti-id-badge menu-icon"></i>
          <span class="menu-title">{{__('sunshine.Staff')}}</span>
        </a>
      </li>
      {{-- Get Time --}}
      <li class="nav-item">
        <a class="nav-link" href="{{route('feedbacks.index')}}">
          <i class="ti-info-alt menu-icon"></i>
          <span class="menu-title">Feedback</span>
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