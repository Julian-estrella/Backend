@props(['active'=> 'default'])
<div x-data="{tab:'{{ $active }}'}">
  @isset($header)
  <div class="border-b border-gray-200">
     <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-grey-500">
      {$header}
     </ul>
  </div>
  @endisset
  <div class="px-4 mt-4">
    {{ $slot }}
    @if ($error)
    <i class="fa-solid fa-circle-exclamation ms-2 animate-pulse"></i>
    @endif
  </div>
</div>