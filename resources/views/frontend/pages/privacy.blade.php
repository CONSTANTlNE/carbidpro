@extends('frontend.layout.app')

@section('content')
    <section id="ft-breadcrumb" class="ft-breadcrumb-section position-relative" style="padding: 70px 0px 70px"
        data-background="https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg"
        style="background-image: url(&quot;https://html.themexriver.com/fastrans/assets/img/bg/bread-bg.jpg&quot;);">
        <span class="background_overlay"></span>
        <div class="container">
            <div class="ft-breadcrumb-content headline text-center position-relative">
                <h2 style="margin-top: 50px;">{!! $tr->translate('Privacy and Policy') !!}</h2>


            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-details-img-text-wrapper">

                    <div class="ft-blog-details-item">
                        <div class="blog-details-text headline">
                            <article>
                                <style>
                                    h1,
                                    h2,
                                    h3,
                                    h4 {
                                        font-size: 29px;
                                    }
                                    br{
                                        display: none;
                                    }
                                </style>
                                <?php echo html_entity_decode($settings->get('privacy')); ?>

                            </article>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
