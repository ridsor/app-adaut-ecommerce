@props(['contact'])

<div class="item card card-icon lift lift-sm mb-3 position-static overflow-hidden">
    <div class="row g-2 flex-column flex-md-row">
        <div class="col w-100 d-flex align-items-center">
            <div class="row p-2 flex-column flex-md-row flex-grow-1">
                <div class="col">
                    <div class="row mb-2 flex-wrap">
                        <div class="col">
                            <div class="text-dark fw-medium">
                                <span>
                                    {{ $contact->name }}
                                </span>
                            </div>
                            <div class="text-muted">
                                <span>
                                    {{ $contact->email }}
                                </span>
                            </div>
                            <div class="text-muted">
                                <span>
                                    {{ $contact->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            @if ($contact->status == 'pending')
                                <button @click.stop="handleContactStatus('{{ $contact->id }}', 'completed')"
                                    class="btn btn-warning btn-icon" type="button">
                                    <i class="fa-regular fa-clock"></i>
                                </button>
                            @else
                                <button @click.stop="handleContactStatus('{{ $contact->id }}', 'pending')"
                                    class="btn btn-success btn-icon" type="button">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="text-dark">
                        <div class="text-dark fw-semibold">Subjek</div>
                        <span>
                            {{ $contact->subject }}
                        </span>
                    </div>
                    <div class="text-dark">
                        <div class="text-dark fw-semibold">Deskripsi</div>
                        <span>
                            {!! Str::markdown($contact->description) !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
