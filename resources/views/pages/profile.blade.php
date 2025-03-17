@extends('layouts.app')

@push('styles')
    <style>
        .padding-custom {
            padding: 3rem 0;
        }
    </style>
@endpush

@section('content')
    <section id="team" class="padding-custom">
        <div class="container ">
            <h6 class="text-center"><span class="text-primary">|</span>Tim Kami</h6>
            <h3 class="display-6 text-center fw-semibold mb-5">Helpman yang siap membantu</h3>
            <div class="row align-items-center">
                @forelse($karyawan as $employee)
                    <div class="col-md-6 col-lg-4 mb-5">
                        @if ($employee['avatar_url'])
                            <img src="{{ asset('storage/' . $employee['avatar_url']) }}" alt="Avatar {{ $employee['name'] }}"
                                class="img-fluid">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee['name']) }}&background=4F46E5&color=fff&size=900"
                                alt="Avatar {{ $employee['name'] }}" class="img-fluid">
                        @endif
                        <h4 class="element-title mt-3 ">{{ $employee['name'] }}</h4>
                        <h6 class="text-secondary">Helpman</h6>
                        {{-- <p class="pe-5">Odio a faucibus cras lacus felis in enim. In tortor ligula
                            risus
                            nulla
                            blandit. Amet nisi iaculis suspendisse fermentum curabitur
                            feugiat.
                        </p> --}}
                        <div class=" social-links">
                            <span>
                                <i class="fas fa-birthday-cake"></i>

                                @if (!is_null($employee['age']))
                                    {{ $employee['age'] }} Tahun
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Add any JavaScript animations or interactions here
    </script>
@endpush
