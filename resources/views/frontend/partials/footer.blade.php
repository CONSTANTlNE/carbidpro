  <!-- Modal Template -->
  <style>
      .carpaymentmodal .modal-dialog {
          max-width: 70%;
      }

      @media only screen and (max-width: 600px) {
          .carpaymentmodal .modal-dialog {
              max-width: 100%;
              margin-top: 100px;
          }
      }
  </style>
  @php
  // Cache initialized in Header component and included in layout
      $footerStatics=Cache::get('footerStatics'.session()->get('locale'));

                if($footerStatics===null){
                    $data=[
                        'All Rights Reserved'=> $tr->translate('All Rights Reserved'),
                    ];

                    Cache::forever('footerStatics'.session()->get('locale'), $data);
                }
  @endphp

  <div class="carpaymentmodal modal fade" id="recordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Credit info <span class="car_info"></span> </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="table-responsive">

                      <table class="table table-bordered" id="recordTable">
                          <!-- Table content will be dynamically loaded here -->
                      </table>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>


  <!-- Start of Footer section
                                                 ============================================= -->
  <footer @if(request()->routeIs('calculator.index')) style="margin-top: 90px!important;" @endif id="ft-footer" class="ft-footer-section">
      <div class="container">
          <div class="ft-footer-copywrite-1 text-center">
              <span>Copyright @ 2024 {{ Cache::get('footerStatics' . session()->get('locale'))['All Rights Reserved'] }} | <a href="/terms-and-conditions"
                      style="text-decoration: underline">Terms and Conditions</a> | <a href="/privacy-and-policy"
                      style="text-decoration: underline">Privacy
                      and Policy</a>
              </span>
              <br>
              <div class="ft-header-top ul-li">
                  <ul>
                      <li><i class="fal fa-envelope"></i> info@carbidpro.com</li>
                      <li><i class="fal fa-map-marker-alt"></i> N46 Alexander Kazbegi Ave .Tbilisi. Georgia

                      </li>
                  </ul>
              </div>
          </div>

      </div>
  </footer>
  <!-- End of Footer section
                                                                                             ============================================= -->
