@foreach ($order->actions['buttons'] as $next_status)
    <?php
      $btnType="btn-primary";
      if(str_contains($next_status,'accept')){
        $btnType="btn-success";
      }else if(str_contains($next_status,'reject')){
        $btnType="btn-danger";
      }
    ?>
    <a href="{{ url('updatestatus/'.$next_status.'/'.$order->id) }}" class="btn btn-sm {{$btnType   }}">{{ __($next_status) }}</a> 
@endforeach
@if (strlen($order->actions['message'])>0)
    <p><small class="text-muted">{{ $order->actions['message'] }}</small><p>
@endif