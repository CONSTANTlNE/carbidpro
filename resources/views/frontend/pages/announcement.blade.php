@extends('frontend.layout.app')

@section('content')
    <!-- Start of faq  section
                                     ============================================= -->
    <section id="ft-faq-page" class="ft-faq-page-section page-padding">
        <div class="container">
            <div class="ft-faq-content mt-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="accordion" id="accordionExample">
                            @foreach (Cache::get('translatedAnnouncements'.Session::get('locale')) as $key => $announcement)
                                <div class="accordion-item headline pera-content">
                                    <h2 class="accordion-header" id="heading{{ $key }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                                            aria-controls="collapse{{ $key }}">
                                            {!! $announcement['title'] !!} - {{ $announcement['date'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {!! $announcement['content'] !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of faq section   ============================================= -->

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
        </script>
    @endpush
@endsection
