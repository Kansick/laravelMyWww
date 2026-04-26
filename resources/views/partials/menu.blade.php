@php
    $currentPath = request()->path();
@endphp

@if(!empty($menuItems))
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom fixed-top">
        <div class="container-fluid mx-auto" style="width: 1200px">
        @foreach($menuItems as $item)
            @if(isset($item['type']) && $item['type'] == "logo")
                <a class="navbar-brand text-uppercase me-5 main-color" href="/">{{ $item['title'] ?? "NOT FOUND TEXT" }}</a>
            @endif
        @endforeach
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
@endif

@foreach($menuItems as $index => $item)
    @php
        $isActive = false;
        $hasDropdown = false;
        if ( (isset($item['active']) && !empty($item['active'])) && (isset($item['type']) && $item['type'] == "menu") ) {
            if($item['active']){
                $isActive = true;
            } 
        }
        if( (isset($item['childs']) && is_array($item['childs']) && count($item['childs']) > 0) ){
            foreach($item['childs'] as $child){
                if(isset($child['active']) && !empty($child['active'])){
                    if($child['active']){
                        $hasDropdown = true;
                    }
                }
            }
        }
    @endphp
    @if($isActive)
        @if(!$hasDropdown)
            <li class="nav-item">
                <a class="nav-link main-color" aria-current="page" href="{{ $item['url'] ?? '#' }}">{{ $item['title'] ?? "NOT FOUND" }}</a>
            </li>
        @endif
        @if($hasDropdown)
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-uppercase main-color" href="{{ $item['url'] ?? '#' }}" id="navbarDropdownMenuLink_{{$index}}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $item['title'] ?? "NOT FOUND"}}
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 3L5 7L9 3" stroke="white" stroke-width="1.5"></path>
                    </svg>
                </a>
                <ul class="dropdown-menu bg-dark-custom" aria-labelledby="navbarDropdownMenuLink_{{$index}}">
                    @foreach($item['childs'] as $child)
                        @if(isset($child['active']) && !empty($child['active']))
                            @if($child['active'])
                                <li><a class="dropdown-item text-uppercase main-color" href="{{ $child['url'] ?? '#' }}">{{ $child['title'] ?? "NOT FOUND"}}</a></li>
                            @endif
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif
    @endif
@endforeach

@if(!empty($menuItems))
                </ul>
            </div>
        </div>
    </nav>
@endif