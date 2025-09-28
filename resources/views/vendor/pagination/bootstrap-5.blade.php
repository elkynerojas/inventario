@if ($paginator->hasPages())
    <nav aria-label="Navegación de páginas">
        <div class="d-flex justify-content-between align-items-center">
            {{-- Información de resultados --}}
            <div class="pagination-info">
                Mostrando 
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                a 
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                de 
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                resultados
            </div>

            {{-- Controles de paginación --}}
            <div>
                <ul class="pagination pagination-sm mb-0">
                    {{-- Botón Anterior --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-left pagination-icon"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                                <i class="bi bi-chevron-left pagination-icon"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Números de página --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="page-item disabled">
                                <span class="page-link">{{ $element }}</span>
                            </li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Botón Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                                <i class="bi bi-chevron-right pagination-icon"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-right pagination-icon"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif