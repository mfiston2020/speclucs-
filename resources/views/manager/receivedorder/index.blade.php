@extends('manager.includes.app')

@section('title',__('navigation.dashboard') .' - '. __('manager/received-order.received_orders'))

@push('css')
    <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link rel="stylesheet" href=""{{ asset('dashboard/assets/dist/css/custom-style.min.css') }}>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current',__('manager/received-order.income'))
@section('page_name',__('manager/received-order.income_list'))
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid note-has-grid">
    <ul class="nav nav-pills p-3 bg-white mb-3 align-items-center">
      <li class="nav-item">
        <a href="javascript:void(0)" class="
            nav-link
            rounded-pill
            note-link
            d-flex
            align-items-center
            justify-content-center
            active
            px-3 px-md-3
            me-0 me-md-2
          " id="all-category">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list feather-sm fill-white me-0 me-md-1"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3" y2="6"></line><line x1="3" y1="12" x2="3" y2="12"></line><line x1="3" y1="18" x2="3" y2="18"></line></svg><span class="d-none d-md-block font-weight-medium">All Notes</span></a>
      </li>
      <li class="nav-item">
        <a href="javascript:void(0)" class="
            nav-link
            rounded-pill
            note-link
            d-flex
            align-items-center
            justify-content-center
            px-3 px-md-3
            me-0 me-md-2
          " id="note-business">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase feather-sm fill-white me-0 me-md-1"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg><span class="d-none d-md-block font-weight-medium">Business</span></a>
      </li>
      <li class="nav-item">
        <a href="javascript:void(0)" class="
            nav-link
            rounded-pill
            note-link
            d-flex
            align-items-center
            justify-content-center
            px-3 px-md-3
            me-0 me-md-2
          " id="note-social">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2 feather-sm fill-white me-0 me-md-1"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg><span class="d-none d-md-block font-weight-medium">Social</span></a>
      </li>
      <li class="nav-item">
        <a href="javascript:void(0)" class="
            nav-link
            rounded-pill
            note-link
            d-flex
            align-items-center
            justify-content-center
            px-3 px-md-3
            me-0 me-md-2
          " id="note-important">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star feather-sm fill-white me-0 me-md-1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="d-none d-md-block font-weight-medium">Important</span></a>
      </li>
      <li class="nav-item ms-auto">
        <a href="javascript:void(0)" class="btn btn-primary rounded-pill d-flex align-items-center px-3" id="add-notes">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file feather-sm fill-white me-0 me-md-1"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg><span class="d-none d-md-block font-weight-medium fs-3">Add Notes</span></a>
      </li>
    </ul>
    <div class="tab-content">
      <div id="note-full-container" class="note-has-grid row">
        <div class="col-md-4 single-note-item all-category">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Book a Ticket for Movie">
              Book a Ticket for Movie
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">11 March 2009</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-important">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Go for lunch">
              Go for lunch
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">01 April 2002</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-social">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Meeting with Mr.Jojo">
              Meeting with Mr.Jojo
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">19 October 2023</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-business">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Give Review for design">
              Give Review for design
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">02 January 2000</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-social">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Nightout with friends">
              Nightout with friends
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">01 August 1999</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-important">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Launch new template">
              Launch new template
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">21 January 2015</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-social">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Change a Design">
              Change a Design
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">25 December 2016</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-business">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Give review for foods">
              Give review for foods
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">18 December 2023</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 single-note-item all-category note-important">
          <div class="card card-body">
            <span class="side-stick"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Give salary to employee">
              Give salary to employee
              <i class="point ri-checkbox-blank-circle-fill ms-1 fs-1"></i>
            </h5>
            <p class="note-date fs-2 text-muted">15 Fabruary 2023</p>
            <div class="note-content">
              <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">
                Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.
              </p>
            </div>
            <div class="d-flex align-items-center">
              <a href="javascript:void(0)" class="link me-1"><i class="ri-star-line fs-5 favourite-note"></i></a>
              <a href="javascript:void(0)" class="link text-danger ms-2"><i class="ri-delete-bin-line fs-5 remove-note"></i></a>
              <div class="ms-auto">
                <div class="category-selector btn-group">
                  <a class="nav-link dropdown-toggle category-dropdown label-group p-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                    <div class="category">
                      <div class="category-business"></div>
                      <div class="category-social"></div>
                      <div class="category-important"></div>
                      <span class="more-options text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right category-menu">
                    <a class="
                        note-business
                        badge-group-item badge-business
                        dropdown-item
                        position-relative
                        category-business
                        text-success
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i>Business</a>
                    <a class="
                        note-social
                        badge-group-item badge-social
                        dropdown-item
                        position-relative
                        category-social
                        text-info
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Social</a>
                    <a class="
                        note-important
                        badge-group-item badge-important
                        dropdown-item
                        position-relative
                        category-important
                        text-danger
                        d-flex
                        align-items-center
                      " href="javascript:void(0);"><i class="ri-checkbox-blank-circle-line me-1"></i> Important</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Add notes -->
    <div class="modal fade" id="addnotesmodal" tabindex="-1" role="dialog" aria-labelledby="addnotesmodalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title text-white">Add Notes</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="notes-box">
              <div class="notes-content">
                <form action="javascript:void(0);" id="addnotesmodalTitle">
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <div class="note-title">
                        <label>Note Title</label>
                        <input type="text" id="note-has-title" class="form-control" minlength="25" placeholder="Title">
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="note-description">
                        <label>Note Description</label>
                        <textarea id="note-has-description" class="form-control" minlength="60" placeholder="Description" rows="3"></textarea>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" data-bs-dismiss="modal">Discard</button>
            <button id="btn-n-add" class="btn btn-info" disabled="disabled">Add</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
@endpush
