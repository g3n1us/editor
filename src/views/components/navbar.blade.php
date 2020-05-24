
<div class="Xsticky-top" id="header_outer">

	<nav class="navbar navbar-inverse bg-inverse navbar-toggleable-md text-muted" style="box-shadow: 0px 0px 2px black;">
		<a class="navbar-brand p-0 align-self-top " href="/">
			{{ config('app.name', 'Laravel') }}
		</a>

		<button class="navbar-toggler Xnavbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<i class="fa fa-bars"></i>
		</button>
		<div class="collapse navbar-collapse flex-wrap" id="navbarContent">
			<div class="w-100 d-flex flex-column flex-lg-row align-items-center">
				<div class="navbar-nav ml-lg-auto align-items-center text-center flex-wrap">

					{!! navbar() !!}
					
					
					<form class="ml-auto" action="/search" style="max-width: 400px">
					    <div class="input-group input-group-sm">
						      <input class="form-control form-control-sm Xnavbar-search" type="search" name="search" value="{{request()->search}}" placeholder="">
						      <span class="input-group-btn">
							      <button class="btn-sm btn btn-primary bg-inverse" type="submit">Search Site</button>
						      </span>
					    </div>
					</form>
						

				</div>
			</div>


	  </div>


	</nav>
</div>
