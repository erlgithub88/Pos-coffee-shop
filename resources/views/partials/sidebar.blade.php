<aside class="main-sidebar sidebar-light elevation-4" style="background-color: #F5E9DA;">
  <!-- Logo Brand -->
  <a href="{{ route('dashboard') }}" class="brand-link d-flex justify-content-center py-3">
    <img src="{{ asset('/storage/images/logo-coffee1.png') }}" alt="CoffeeShop Logo"
      style="width: 130px; height: 130px; object-fit: cover; border-radius: 50%; opacity: 0.95; transform: scale(1.25); transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;"
      onmouseover="this.style.transform='scale(1.35)'; this.style.opacity='1';"
      onmouseout="this.style.transform='scale(1.25)'; this.style.opacity='0.95';">
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center" style="font-size: 16px;">
      <div class="image">
        <img src="{{ Auth::user()->image_path ? asset('storage/' . Auth::user()->image_path) : asset('adminlte/dist/img/user2-160x160.jpg') }}"
          class="img-circle elevation-2" style="width: 40px; height: 40px; object-fit: cover;" alt="User Image">
      </div>
      <div class="info" style="margin-left: 10px;">
        <a href="#" class="d-block text-dark" style="font-weight: 600; font-size: 17px;">
          {{ Auth::user()->name }}
        </a>
        <small class="text-muted" style="font-size: 14px;">{{ Auth::user()->email }}</small>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"
        style="font-size: 18px; font-weight: 600;">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard') }}"
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt" style="font-size: 18px;"></i>
            <p style="margin-left: 5px;">Dashboard</p>
          </a>
        </li>

        <!-- Role-based Menus -->
        @if (Auth::user()->role == 'owner')
          @include('partials.sidebar.owner')
        @elseif (Auth::user()->role == 'manager')
          @include('partials.sidebar.manager')
        @elseif (Auth::user()->role == 'cashier')
          @include('partials.sidebar.cashier')
        @elseif (Auth::user()->role == 'member')
          @include('partials.sidebar.member')
        @endif
      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->
</aside>
