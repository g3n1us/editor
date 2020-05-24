<style >
  .cke_top{
    position: sticky;
    top: 58px;
  }
  .w-auto{
    width: auto;
  }
  
  #admin-navbar button.nav-item{
	  background: none;
	  border: none;
  }
</style>
@auth

<nav id="admin-navbar" class="admin-navbar navbar navbar-expand bg-light navbar-light sticky-top">
  <div class="navbar-nav flex-grow flex-wrap" style="flex-grow: 1">

    <a class="nav-item nav-link" href="/dashboard/pages/{{$page->id}}"><i class="fa fa-edit"></i> Edit</a>
    

    <form action="/dashboard/pages" method="post">
	    @csrf
	    @method('put')
	    <button type="submit" class="nav-item nav-link mr-auto"><i class="fa fa-plus"></i> New Page</button>
    </form>
    
	<form id="logout_form" method="post" class="ml-auto" action="/logout">
	  @csrf
	  <button type="submit" class="nav-item nav-link text-muted "><i class="fa fa-arrow-circle-left"></i> Logout</button>
	</form>
    
  </div>
</nav>



@endauth	
