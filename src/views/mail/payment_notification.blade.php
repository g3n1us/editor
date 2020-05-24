@component('mail::message')
# Payment Received

## Name:
{{$request->input('card.name')}}

## Email:
{{$request->input('email')}}

## Amount:
${{$request->input('amount')/100}}

## Items:
@foreach($request->items as $item)
- {{$item['description']}}
@endforeach


@endcomponent
