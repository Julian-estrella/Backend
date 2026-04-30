@props(['tab', 'error' => false])

<li class="me-2">
  <a href="#" x-on:click="tab = '{{ $tab }}'"
  :class="{
    'text-red-600 border-red-600': @json($error) && tab !== '{{ $tab }}', 
    'text-blue-600 border-blue-600 active': tab === '{{ $tab }}' && !@json($error),
    'text-red-600 border-red-600 active': tab === '{{ $tab }}' && @json($error),
    'border-transparent hover:text-blue-600 border-gray-300': tab !== '{{ $tab }}' && !@json($error)
  }"
  class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200"
  :aria-current="tab === '{{ $tab }}' ? 'page' : undefined">

   {{ $slot }}

    @if ($error)
        <i class="fa-solid fa-circle-exclamation ms-2 animate-pulse"></i>
    @endif       

  </a>
</li>