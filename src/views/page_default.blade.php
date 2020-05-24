@extends(view()->exists('layouts.editor_layout') ? 'layouts.editor_layout' : 'g3n1us_editor::editor_layout')

@section('content')

{!! $html ?? $page->html !!}

@endsection


