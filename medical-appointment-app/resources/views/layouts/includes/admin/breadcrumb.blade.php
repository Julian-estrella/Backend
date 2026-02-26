@if (count($breadcrumbs))
  {{--Display:block--}}
  <nav class="mb-2 block">
    <ol class="flex flex-wrap text-slate-700 text-sm">
      @foreach($breadcrumbs as $item)
      <li class="flex items-center">
        {{-- Si NO es el primer elemento, pinta el seperador con el espacio--}}
        @unless($loop->first)
          {{-- El span crea el separador con margen lateral--}}
          <span class="px-2 text-gray-400">
              /
          </span>
        @endunless
          {{--Revisa si existe una llave llamada 'href'--}}
          @isset($item['href'])
          <a href="{{$item['href']}}" class="opacity-60 hover:opacity-100 transition">
            {{$item['name']}}
          </a>
        @else
            {{$item['name']}}
        @endisset
      </li>
      @endforeach
    </ol>
    {{--El ultimo elemento aparezca resaltado--}}
    @if(count($breadcrumbs)>1)
    <h6 class="font-bold mt-2">
      {{end($breadcrumbs)['name']}}
    </h6>
    @endif
  </nav>
@endif