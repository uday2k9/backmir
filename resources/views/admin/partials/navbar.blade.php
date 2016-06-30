<ul class="nav navbar-nav">
  @if (Auth::check())
    <li @if (Request::is('admin/post*')) class="active" @endif>
      <a href="/admin/post">Posts</a>
    </li>
    <li @if (Request::is('admin/tag*')) class="active" @endif>
      <a href="/admin/tag">Tags</a>
    </li>
    <li @if (Request::is('admin/upload*')) class="active" @endif>
      <a href="/admin/upload">Uploads</a>
    </li>
  @endif
</ul>

<ul class="nav navbar-nav navbar-right">
  @if (Auth::guest())
    <li><a href="<?php echo url();?>/auth/login"><i class="fa fa-lock"></i>Login</a></li>
  @else
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
         aria-expanded="false">{{ Auth::user()->name }}
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="/miramix/auth/logout">Logout</a></li>
      </ul>
    </li>
  @endif
</ul>