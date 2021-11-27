<section id="product">

  @foreach ($processes as $key => $process)
    <div class="container my-5 py-5">
      <div class="row">
        <div class="row justify-content-center text-center my-sm-5">
          <div class="col-lg-6">
            <h2 class="text-dark mb-0">{{ $process->title}}</h2>
            <h2 class="text-primary text-gradient">{{ $process->subtitle}}</h2>
            <p class="lead">{{ $process->description }}</p>
          </div>
        </div>
        @if ($process->image)
          <div class="row justify-content-center text-center">
            <div class="col-lg-12 ms-auto me-auto">
              <img class="w-100" src="{{ $process->image }}" />
            </div>
          </div>
        @endif
      </div>
    </div>
  @endforeach

</section>