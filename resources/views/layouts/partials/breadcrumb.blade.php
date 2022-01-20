@if(request()->route()->getName() != 'home.index')
@auth
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    @yield('breadcrumb')
  </ol>
</nav>
@endauth
@endif