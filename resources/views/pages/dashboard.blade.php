@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-title', 'Malaysia Crisis Management Tools')

@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        #map {
            height: 700px;
        }
        .filter-container {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .form-check-scrollable {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }
        .total-info {
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0,0,0,0.2);
            font-weight: bold;
            margin-left: 10px;
        }

        .select2-container .select2-selection--single {
            height: 45px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px;
            right: 10px;
        }

        .p-modal{
            text-align:justify;
        }
        .hospital-legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 5px;
        }
        .hospital-legend-item img {
            width: 30px;
            height: 30px;
        }

        p{
        margin-bottom: 8px;
            line-height: 18px;
        }

        .btn-danger{
            background-color:#395272;
            border-color: transparent;
        }

        .btn-danger:hover{
            background-color:#5686c3;
            border-color: transparent;
        }

        .btn.active {
            background-color: #5686c3 !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .p-3{
            padding: 10px !important;
            margin: 0 3px;
        }

        .btn-outline-danger{
            color: #FFFFFF;
            background-color:#395272;
            border-color: transparent;
        }

        .btn-outline-danger:hover{
            background-color:#5686c3;
            border-color: transparent;
        }

        .fa,
        .fab,
        .fad,
        .fal,
        .far,
        .fas {
            color: #346abb;
        }

        .card-header{
            padding: 0.25rem 1.25rem;
            color: #3c66b5;
            font-weight: bold;
        }

        .mb-4{
            margin-bottom: 0.5rem !important;
        }

    /* Classification section */
    .classification {
      display: flex;
      width: 100%;
    }

    .class-column {
      flex: 1;
      text-align: center;

    }
    .class-column:last-child {
      border-right: none;
    }

    .class-header {
      font-weight: 600;
      padding: 0.1rem 0;
    }

    /* Color bars */
    .class-medical-classification {border: none; text-align: left;}
    .class-airport-category {border: none;}
    .class-advanced { border-bottom: 3px solid #0070c0; }
    .class-intermediate { border-bottom: 3px solid #00b050; }
    .class-basic { border-bottom: 3px solid #ffc000; }

    /* Airfield layout */
    .airport-list {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Hospital layout */
    .hospital-list {
      display: flex;
      flex-direction: column;
      align-items: center;

    }

    /* For side-by-side classes */
    .hospital-row {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0;
    }

    .hospital-item {
      display: flex;
      align-items: center;
      gap: 0;
      font-size: 0.9rem;
      white-space: nowrap;
    }

    .hospital-icon {
      width: 18px;
      height: 18px;
      border-radius: 3px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    /* Image inside icon box */
    .hospital-icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    /* Airfield icons */
    .category-item img {
      width: 16px;
      height: 16px;
      object-fit: contain;
    }

    /* Police classification legend */
    .legend-grid {
        display: grid;
        gap: 0;
        width: 100%;
        align-items: start;
    }

    .legend-grid-2 {
        grid-template-columns: repeat(2, max-content);
        column-gap: 10px;
        width: auto;
    }

    /* Kolom selebar isinya supaya legend rapat & rata kiri */
    .legend-grid-3 {
        grid-template-columns: repeat(3, max-content);
        column-gap: 10px;
        width: auto;
    }

    .legend-grid-4 {
        grid-template-columns: repeat(4, max-content);
        column-gap: 10px;
        width: auto;
    }

    .legend-grid-item {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        width: 100%;
        text-align: left;
        white-space: nowrap;
    }

    .legend-grid-item img {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
    }

    .legend-grid-item small {
        text-align: left;
    }

    /* Tab modal legend mengikuti tampilan dashboard-ref */
    .info-modal-tabs {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        flex: 0 0 auto;
        flex-wrap: nowrap;
        gap: 2px;
        overflow-x: auto;
    }

    .info-modal-tabs .nav-link {
        border: 1px solid transparent;
        border-bottom: none;
        border-radius: 6px 6px 0 0;
        color: #55606e;
        font-size: 13px !important;
        font-weight: 600;
        padding: 8px 14px !important;
        white-space: nowrap;
    }

    .info-modal-tabs .nav-link:hover {
        background: #eef2f7;
        color: #395272;
    }

    .info-modal-tabs .nav-link.active {
        background: #fff;
        color: #395272;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .police-modal-dialog {
        width: calc(100vw - 2rem);
        max-width: 1280px;
    }

    @media (max-width: 1199.98px) {
        .info-modal-tabs {
            flex-wrap: wrap;
            overflow-x: hidden;
        }
    }

    /* Facilities checkbox list (filter panel) */
    .facility-title {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #555;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .facility-list {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    /* Label membungkus checkbox supaya seluruh baris bisa diklik */
    .facility-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 4px 6px;
        border-radius: 5px;
        line-height: 1.2;
        cursor: pointer;
    }

    .facility-item:hover {
        background: #f2f6fc;
    }

    .facility-item input[type="checkbox"] {
        width: 14px;
        height: 14px;
        margin: 0;
        flex-shrink: 0;
        accent-color: #1a73e8;
        cursor: pointer;
    }

    .facility-name {
        flex: 1;
        font-size: 13px;
        color: #333;
    }

    .facility-count {
        flex-shrink: 0;
        min-width: 30px;
        text-align: center;
        font-size: 11px;
        font-weight: 600;
        color: #395272;
        background: #e8eef7;
        border-radius: 10px;
        padding: 1px 6px;
    }

    .facility-item-all .facility-name {
        font-weight: 700;
        color: #222;
    }

    /* Province dropdown (filter panel) */
    .select-input {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 8px 10px;
        background: #fff;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .select-input input {
        border: none;
        width: 100%;
        cursor: pointer;
        background: transparent;
        outline: none;
    }

    .select-dropdown {
        display: none;
        position: absolute;
        width: 100%;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-top: 3px;
        z-index: 9999;
        max-height: 250px;
        overflow: hidden;
    }

    .select-dropdown.show {
        display: block;
    }

    .dropdown-search {
        width: 100%;
        border: none;
        border-bottom: 1px solid #ddd;
        padding: 8px;
        outline: none;
    }

    #provinceList {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 180px;
        overflow-y: auto;
    }

    #provinceList li {
        padding: 5px 10px;
    }

    #provinceList li:hover {
        background: #f5f5f5;
    }

    #provinceList label {
        width: 100%;
        margin: 0;
        cursor: pointer;
    }

    /* ===== Google Places Autocomplete Fix ===== */
    .pac-container {
        z-index: 99999 !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2) !important;
        font-family: inherit !important;
        margin-top: 2px !important;
        border: 1px solid #ddd !important;
    }

    .pac-item {
        padding: 6px 12px !important;
        cursor: pointer !important;
        font-size: 13px !important;
        border-top: 1px solid #f0f0f0 !important;
    }

    .pac-item:hover {
        background: #f0f6ff !important;
    }

    .pac-item-query {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #333 !important;
    }

    .pac-matched {
        color: #1a73e8 !important;
        font-weight: 700 !important;
    }

    #locationSearchMap:focus {
        outline: none !important;
        border-color: #1a73e8 !important;
        box-shadow: 0 0 0 2px rgba(26,115,232,0.2) !important;
    }
    </style>

@endpush

@section('conten')

<div class="card">
    <div class="row" style="background-color: #dfeaf1;">
        <div class="col-md-9">
            <div class="d-flex p-3" style="justify-content: center;">
                <div class="d-flex gap-2" style="flex-wrap: wrap; width: 100%; justify-content: space-between; column-gap: 45px; row-gap: 12px;">

                      <!-- Airport -->
                      <div class="class-column" style="flex: 0 0 auto;">

                        <div class="airport-list" style="align-items:start;">

                          <div class="class-header class-airport-category" style="text-align:left;">Airfield Classification</div>
                          <div class="hospital-row legend-grid legend-grid-3">

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level6Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:18px; height:18px;">
                                  <small>International</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level5Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:18px; height:18px;">
                                  <small>Domestic</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level4Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:18px; height:18px;">
                                  <small>Regional</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                  <small>Civil-Military</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level3Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:18px; height:18px;">
                                  <small>Military</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level1Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:18px; height:18px;">
                                  <small>Private</small>
                              </button>

                          </div>

                        </div>
                      </div>

                      <!-- Medical Facility Legend -->
                      <div style="flex: 0 0 auto; flex-direction: column;">
                        <!-- Title -->
                        <div>
                            <div class="class-header class-medical-classification">Medical Facility Classification</div>
                        </div>
                        <div style="display: flex; flex-direction: row;">
                            <!-- Advanced -->
                            <div class="class-column">
                              <div class="class-header class-advanced">Advanced</div>
                              <div class="hospital-list">
                                <div class="hospital-item">
                                  <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level66Modal">
                                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:24px; height:24px;">
                                    <small>Tertiary</small>
                                  </button>
                                </div>
                              </div>
                            </div>

                            <!-- Intermediate -->
                            <div class="class-column">
                              <div class="class-header class-intermediate">Intermediate</div>
                              <div class="hospital-list">
                                <div class="hospital-row">
                                  <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level55Modal">
                                      <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:24px; height:24px;">
                                      <small>Secondary</small>
                                    </button>
                                  </div>
                                  <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level44Modal">
                                      <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:24px; height:24px;">
                                      <small>Primary</small>
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Basic -->
                            <div class="class-column">
                              <div class="class-header class-basic">Basic</div>
                              <div class="hospital-list">
                                <div class="hospital-row">
                                  <div class="hospital-item">
                                    <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level11Modal">
                                        <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:24px; height:24px;">
                                        <small>Clinic / Health Center</small>
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>

                      <!-- Police Legend -->
                      <div class="class-column" style="flex: 0 0 auto;">
                        <div class="class-header class-airport-category" style="text-align:left;">Police Classification</div>
                        <div class="hospital-row legend-grid legend-grid-2">

                            <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police4Modal">
                                <img src="{{ asset('images/Layer1.png') }}" alt="Police HQ">
                                <small>National Police HQ</small>
                            </button>

                            <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police3Modal">
                                <img src="{{ asset('images/Layer2.png') }}" alt="State police contingent headquarters">
                                <small>State police contingent headquarters (IPK)</small>
                            </button>

                            <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police2Modal">
                                <img src="{{ asset('images/Layer3.png') }}" alt="District Police Force">
                                <small>District Police Force (IPD)</small>
                            </button>

                            <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#police1Modal">
                                <img src="{{ asset('images/Layer4.png') }}" alt="Police Station">
                                <small>Police Station</small>
                            </button>

                        </div>
                      </div>
                </div>
            </div>
        </div>
         <div class="col-md-3">
            <div class="d-flex justify-content-end p-3">
                <div class="d-flex gap-2 mt-2">

                    <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                        <i class="bi bi-airplane fs-3"></i>
                        <small>Aviation</small>
                    </a>

                    <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
                    <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                        <small>Medical</small>
                    </a>

                    <a href="{{ url('police') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('police') ? 'active' : '' }}">
                        <i class="bi bi-person-badge" style="width: 24px; height: 24px;"></i>
                        <small>Police</small>
                    </a>

                    <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
                    <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                        <small>Embassies</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<div style="position:relative;">

<div id="map"></div>

<!-- Route Detail Panel -->
<div id="routePanel" style="
    display:none;
    position:absolute;
    top:10px;
    left:10px;
    width:300px;
    max-height:calc(100% - 20px);
    background:#fff;
    border-radius:10px;
    box-shadow:0 4px 20px rgba(0,0,0,0.18);
    z-index:999;
    flex-direction:column;
    overflow:hidden;
    font-family:inherit;
">
    <!-- Header -->
    <div style="background:#1a73e8;padding:12px 14px;color:#fff;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;">
        <div>
            <div style="font-size:11px;opacity:0.85;letter-spacing:0.5px;">DRIVING DIRECTIONS</div>
            <div id="routePanelTitle" style="font-size:13px;font-weight:600;margin-top:2px;">—</div>
        </div>
        <button onclick="closeRoutePanel()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:26px;height:26px;border-radius:50%;cursor:pointer;font-size:15px;line-height:1;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <!-- Summary -->
    <div id="routeSummary" style="padding:10px 14px;background:#f0f4ff;border-bottom:1px solid #dde8ff;display:flex;gap:16px;flex-shrink:0;">
        <div style="text-align:center;">
            <div style="font-size:18px;font-weight:700;color:#1a73e8;" id="routeDistance">—</div>
            <div style="font-size:10px;color:#666;text-transform:uppercase;letter-spacing:0.4px;">Distance</div>
        </div>
        <div style="text-align:center;">
            <div style="font-size:18px;font-weight:700;color:#395272;" id="routeDuration">—</div>
            <div style="font-size:10px;color:#666;text-transform:uppercase;letter-spacing:0.4px;">Est. Time</div>
        </div>
    </div>
    <!-- Steps -->
    <div id="routeSteps" style="overflow-y:auto;flex:1;padding:8px 0;"></div>
</div>

</div>

<div class="modal fade" id="level1Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Private Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level2Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Combined Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level3Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Military Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level4Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Regional Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level5Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">International Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level7Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Military Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level11Modal" tabindex="-1" aria-labelledby="level11ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" alt="Health clinic" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level11ModalLabel">Clinic / Health Clinic (Community Primary-Care Level)</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="level11Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap" id="level11-overview-tab" data-bs-toggle="tab" data-bs-target="#level11-overview" type="button" role="tab" aria-controls="level11-overview" aria-selected="true">Overview</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level11-role-tab" data-bs-toggle="tab" data-bs-target="#level11-role" type="button" role="tab" aria-controls="level11-role" aria-selected="false">Role</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level11-clinical-services-tab" data-bs-toggle="tab" data-bs-target="#level11-clinical-services" type="button" role="tab" aria-controls="level11-clinical-services" aria-selected="false">Clinical Services</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="level11TabsContent">
          <div class="tab-pane fade show active" id="level11-overview" role="tabpanel" aria-labelledby="level11-overview-tab" tabindex="0">
            <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <h6 class="alert-heading fw-bold">Disclaimer</h6>
              <p class="p-modal mb-2">Malaysia does not use “Health Center” as a separate current national facility grade equivalent to a hospital classification. In the Ministry of Health public system, the principal comprehensive primary-care facility is the Health Clinic – Klinik Kesihatan (KK). Other community primary-care facilities include Rural Clinics (Klinik Desa), Maternal and Child Health Clinics, Community Clinics (Klinik Komuniti), dental clinics, and mobile services.</p>
              <p class="p-modal mb-0">Public Health Clinics are organized through Ministry of Health administrative, workload, service, infrastructure, and catchment-planning frameworks. Private medical clinics follow a separate statutory framework under the Private Healthcare Facilities and Services Act 1998 [Act 586] and are registered as private medical clinics rather than classified as KK1–KK7.</p>
            </div>
            <p class="p-modal">A Health Clinic – Klinik Kesihatan (KK) is a Ministry of Health community-based primary healthcare facility providing first-contact outpatient care, preventive services, continuing treatment, maternal and child healthcare, health promotion, disease-control programs, and referral to hospitals or specialist services when treatment exceeds clinic capability.</p>
            <p class="p-modal">Health Clinics form the principal comprehensive clinic level of Malaysia's public primary-care network. Their service capacity varies according to patient workload, population served, available medical personnel, infrastructure, diagnostic support, and local healthcare requirements. Larger Health Clinics may provide Family Medicine Specialist services, emergency assessment, laboratory services, radiography, rehabilitation, pharmacy, dental services, and extended maternal-child healthcare, while smaller clinics provide a more limited primary-care package.</p>
            <p class="p-modal mb-0">The Ministry of Health classifies Health Clinics from KK1 to KK7 according to average daily patient attendance. Standard facility planning separately considers the services provided and estimated catchment population. These classifications determine clinic scale and planning requirements; they do not represent hospital grades or inpatient levels.</p>
          </div>

          <div class="tab-pane fade" id="level11-role" role="tabpanel" aria-labelledby="level11-role-tab" tabindex="0">
            <ul class="p-modal mb-0">
          <li>Provide first-contact medical assessment, diagnosis, treatment, monitoring, and follow-up for common illnesses and minor injuries.</li>
          <li>Provide continuing management of chronic and noncommunicable diseases, including diabetes, hypertension, cardiovascular risk conditions, and other priority conditions.</li>
          <li>Deliver maternal, antenatal, postnatal, newborn, child, adolescent, reproductive, adult, elderly, and family-health services.</li>
          <li>Provide immunization, screening, health promotion, nutrition, preventive healthcare, communicable-disease control, and public-health programs.</li>
          <li>Provide basic emergency assessment, initial resuscitation, stabilization, and coordination of ambulance or hospital referral.</li>
          <li>Conduct minor outpatient procedures and basic diagnostic investigations according to clinic capability.</li>
          <li>Coordinate referrals to district, specialist, state, university, or other referral hospitals when patients require inpatient care, surgery, specialist management, advanced diagnostics, or critical care.</li>
          <li>Support community outreach, surveillance, domiciliary services, health education, and population-health programs.</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="level11-clinical-services" role="tabpanel" aria-labelledby="level11-clinical-services-tab" tabindex="0">
        <h6>Approximate Bed Capacity</h6>
        <p class="p-modal">Health Clinics generally have no conventional inpatient hospital beds and primarily operate as outpatient and ambulatory primary-care facilities.</p>
        <p class="p-modal">Selected clinics may contain observation areas, sick bays, maternity spaces, or an Alternative Birthing Centre (ABC) according to approved facility plans and local service requirements. Limited observation or maternity beds do not convert a Health Clinic into a secondary-care hospital.</p>

        <h6 class="mt-3">Health Clinic Workload Classification</h6>
        <ul class="p-modal">
          <li><strong>KK1</strong> — more than 800 average patient attendances per day.</li>
          <li><strong>KK2</strong> — 500–800 average patient attendances per day.</li>
          <li><strong>KK3</strong> — 300–500 average patient attendances per day.</li>
          <li><strong>KK4</strong> — 150–300 average patient attendances per day.</li>
          <li><strong>KK5</strong> — 100–150 average patient attendances per day.</li>
          <li><strong>KK6</strong> — 50–100 average patient attendances per day.</li>
          <li><strong>KK7</strong> — fewer than 50 average patient attendances per day.</li>
        </ul>

        <h6 class="mt-3">Typical Services</h6>
        <ul class="p-modal">
          <li>General outpatient consultation and treatment.</li>
          <li>Maternal and child healthcare.</li>
          <li>Antenatal and postnatal care.</li>
          <li>Family planning and reproductive healthcare.</li>
          <li>Immunizations and preventive services.</li>
          <li>Chronic-disease screening, treatment, and follow-up.</li>
          <li>Communicable-disease detection, surveillance, treatment, and referral.</li>
          <li>Basic emergency assessment and stabilization.</li>
          <li>Pharmacy services.</li>
          <li>Laboratory or mini-laboratory services according to clinic type.</li>
          <li>Radiography at designated larger clinics.</li>
          <li>Dental services at designated facilities.</li>
          <li>Rehabilitation and allied-health services according to clinic capability.</li>
          <li>Community health education and outreach.</li>
        </ul>

        <h6 class="mt-3">Surgical &amp; Procedural Capacity</h6>
        <ul class="p-modal">
          <li>Wound management, dressings, injections, specimen collection, intravenous therapy, and minor outpatient procedures.</li>
          <li>Basic emergency resuscitation, oxygen therapy, stabilization, and transfer.</li>
          <li>Normal delivery at designated clinics equipped with an Alternative Birthing Centre.</li>
          <li>No routine major surgery, comprehensive operating-theatre service, general anesthesia program, or specialist intensive-care service.</li>
        </ul>

        <h6 class="mt-3">Referral Position</h6>
        <ul class="p-modal">
          <li>Health Clinics function primarily as the community entry point to the public healthcare system.</li>
          <li>Patients manageable at primary-care level remain under clinic treatment and follow-up.</li>
          <li>Patients requiring hospital admission, surgery, specialist consultation, advanced imaging, intensive care, or complex management are referred to the appropriate hospital or specialist service.</li>
          <li>Patients discharged from hospitals may return to Health Clinics for medication monitoring, wound care, rehabilitation, chronic-disease management, maternal-child follow-up, or other continuing care.</li>
        </ul>

        <h6 class="fw-bold mt-4">Other Community Clinic Types</h6>
        <ul class="p-modal">
          <li><strong>Rural Clinic – Klinik Desa (KD):</strong> Smaller community-proximate facility traditionally focused on maternal-child health, nursing, preventive care, community health, and selected basic primary-care services.</li>
          <li><strong>Maternal and Child Health Clinic:</strong> Facility focused on antenatal, postnatal, reproductive, newborn, child-health, family-planning, and preventive services.</li>
          <li><strong>Community Clinic – Klinik Komuniti:</strong> Local outpatient facility providing accessible treatment for minor illnesses, basic screening, health promotion, follow-up, and selected community healthcare.</li>
          <li><strong>Mobile Clinic:</strong> Primary-care service delivered by mobile medical teams, buses, boats, helicopters, or other arrangements to populations with geographic or access barriers.</li>
          <li><strong>Private Medical Clinic:</strong> A non-government facility used for the practice of medicine on an outpatient basis, including screening, diagnosis and treatment, preventive or promotive healthcare, and treatment using appropriate medical equipment or devices. Under Act 586, a private medical clinic must be registered with the Ministry of Health.</li>
        </ul>

        <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <strong>Note:</strong> The term Health Clinic (Klinik Kesihatan) should be used as the primary English–Malay facility designation in the Malaysia classification. “Health Centre” may appear descriptively or in older material, but it should not be presented as a separate current MOH facility class. KK1–KK7 describe Health Clinic workload and planning scale and must not be interpreted as primary, secondary, or tertiary hospital grades.
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level22Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-orange.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 2</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>Community Health Post - Health Sub Center (CHP)</b></p>
        <p class="p-modal">Primary health, ambulatory care, and short stay inpatient and maternity care at the local rural / remote community level, with a minimum of six (6) health workers to ensure safe 24-hour care and treatment.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level33Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class D — Sub-district Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Provides basic inpatient and emergency care with general practitioners and limited specialist support. Mainly located in sub-districts serving as the first referral point before higher-level hospitals.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level44Modal" tabindex="-1" aria-labelledby="level44ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" alt="Primary medical facility" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level44ModalLabel">Primary Medical Facilities (Community Level)</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="level44Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap" id="level44-overview-tab" data-bs-toggle="tab" data-bs-target="#level44-overview" type="button" role="tab" aria-controls="level44-overview" aria-selected="true">Overview</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level44-role-tab" data-bs-toggle="tab" data-bs-target="#level44-role" type="button" role="tab" aria-controls="level44-role" aria-selected="false">Role</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level44-clinical-services-tab" data-bs-toggle="tab" data-bs-target="#level44-clinical-services" type="button" role="tab" aria-controls="level44-clinical-services" aria-selected="false">Clinical Services</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="level44TabsContent">
          <div class="tab-pane fade show active" id="level44-overview" role="tabpanel" aria-labelledby="level44-overview-tab" tabindex="0">
            <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <h6 class="alert-heading fw-bold">Disclaimer</h6>
              <p class="p-modal mb-2">Malaysia’s public primary-care network is classified mainly by facility type, service scope, workload, standard facility plan, and catchment population. The KK1–KK7 classification applies to Ministry of Health Health Clinics and does not represent a hospital grade.</p>
              <p class="p-modal mb-0"><strong>Source:</strong> <a href="https://hq.moh.gov.my/bpkk/index.php/klasifikasi-klinik-kesihatan" class="text-primary" target="_blank" rel="noopener noreferrer">Malaysia Ministry of Health: Health Clinic Classification</a></p>
            </div>
            <p class="p-modal">Primary medical facilities provide the main community entry point into Malaysia’s public health system. Health Clinics deliver broad outpatient, maternal-child, emergency, preventive, chronic-disease, pharmacy, laboratory, dental, rehabilitation, and public-health services according to clinic type. Rural Clinics, Maternal and Child Health Clinics, Community Clinics, mobile services, and dental facilities extend access closer to local populations.</p>
            <p class="p-modal">As of 31 December 2024, the Ministry of Health reported 1,131 Health Clinics, 1,656 Rural Clinics, 77 Maternal and Child Health Clinics, and 205 Community Clinics. Mobile clinic services operated through bus, boat, and helicopter teams.</p>
            <p class="p-modal mb-0"><strong>Note:</strong> Health Clinic classification has two related uses. The workload classification places clinics into KK1–KK7 by average daily attendance. Standard facility planning uses service scope and estimated catchment population. The two approaches support planning and do not create inpatient hospital levels.</p>
          </div>

          <div class="tab-pane fade" id="level44-role" role="tabpanel" aria-labelledby="level44-role-tab" tabindex="0">
            <ul class="p-modal mb-0">
          <li>Provide first-contact assessment and treatment for common acute, chronic, communicable, and noncommunicable conditions.</li>
          <li>Deliver maternal, newborn, child, adolescent, reproductive, adult, elderly, disability, and family-health services.</li>
          <li>Provide immunisation, screening, disease prevention, surveillance, health promotion, nutrition, and community outreach.</li>
          <li>Manage hypertension, diabetes, cardiovascular risk, tuberculosis, HIV, mental-health conditions, and other priority programmes according to clinic capability.</li>
          <li>Provide basic emergency response, ambulance coordination, stabilisation, and referral for hospital treatment.</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="level44-clinical-services" role="tabpanel" aria-labelledby="level44-clinical-services-tab" tabindex="0">
        <h6>Approximate Bed Capacity</h6>
        <p class="p-modal">Primary-care clinics generally have no regular hospital beds. Most operate as outpatient and ambulatory facilities rather than inpatient hospitals.</p>
        <p class="p-modal">Selected standard clinic plans may include an Alternative Birthing Centre, sick bay, short observation area, or maternity space. These limited beds do not convert the clinic into a secondary hospital.</p>
        <p class="p-modal">Mobile clinics, Rural Clinics, Community Clinics, and routine dental clinics do not operate conventional inpatient wards.</p>

        <h6 class="mt-3">Health Clinic Workload Classification</h6>
        <ul class="p-modal">
          <li><strong>KK1</strong> — more than 800 average patient attendances per day.</li>
          <li><strong>KK2</strong> — 500–800 average patient attendances per day.</li>
          <li><strong>KK3</strong> — 300–500 average patient attendances per day.</li>
          <li><strong>KK4</strong> — 150–300 average patient attendances per day.</li>
          <li><strong>KK5</strong> — 100–150 average patient attendances per day.</li>
          <li><strong>KK6</strong> — 50–100 average patient attendances per day.</li>
          <li><strong>KK7</strong> — fewer than 50 average patient attendances per day.</li>
        </ul>

        <h6 class="mt-3">Standard Health Clinic Planning Categories</h6>
        <ul class="p-modal">
          <li><strong>KK2</strong> — broad outpatient, emergency, maternal-child, dental, rehabilitation, radiography, laboratory, and pharmacy services; catchment above 50,000 people.</li>
          <li><strong>KK3</strong> — similar broad service package; catchment above 30,000 to 50,000 people.</li>
          <li><strong>KK4</strong> — outpatient, emergency, maternal-child, dental, radiography, laboratory, and pharmacy services, with optional Alternative Birthing Centre and sick bay; catchment above 20,000 to 30,000 people.</li>
          <li><strong>KK5</strong> — outpatient, emergency, maternal-child, dental, mini-laboratory, and pharmacy services, with optional Alternative Birthing Centre and sick bay; catchment above 10,000 to 20,000 people.</li>
          <li><strong>KK6</strong> — outpatient, emergency, maternal-child, mini-laboratory, and pharmacy services, with optional Alternative Birthing Centre and sick bay; catchment above 5,000 to 10,000 people.</li>
          <li><strong>KK7</strong> — outpatient, emergency, maternal-child, mini-laboratory, and pharmacy services, with optional Alternative Birthing Centre and sick bay; catchment below 5,000 people.</li>
        </ul>

        <h6 class="mt-3">Primary Service Levels</h6>
        <ul class="p-modal">
          <li><strong>Universal</strong> — commonly provided by KK6 and KK7 through support personnel and medical officers on a resident or visiting basis.</li>
          <li><strong>Intermediate</strong> — commonly provided by KK4 and KK5 through support personnel and medical officers without a resident Family Medicine Specialist.</li>
          <li><strong>Advanced</strong> — commonly provided by KK1, KK2, and KK3 through medical officers, specialists, multidisciplinary personnel, and higher-level diagnostic and pharmacy support.</li>
        </ul>

        <h6 class="mt-3">Other Primary Facility Types</h6>
        <ul class="p-modal">
          <li><strong>Rural Clinic (Klinik Desa)</strong> — community-proximate maternal-child, nursing, preventive, and basic primary-care services according to local deployment.</li>
          <li><strong>Maternal and Child Health Clinic</strong> — focused antenatal, postnatal, reproductive, newborn, child-health, and family-planning services.</li>
          <li><strong>Community Clinic (Klinik Komuniti)</strong> — accessible community outpatient care for minor illness, screening, follow-up, and health promotion.</li>
          <li><strong>Private Medical Clinic and Private Dental Clinic</strong> — registered first-contact facilities operating outside the public clinic classification.</li>
          <li><strong>Mobile Clinic</strong> — scheduled services delivered by bus, boat, helicopter, or mobile team to communities with access barriers.</li>
          <li><strong>Dental Clinic</strong> — standalone or integrated public dental services located in clinics, hospitals, schools, community facilities, and mobile programmes.</li>
        </ul>

        <h6 class="mt-3">Core Services</h6>
        <ul class="p-modal">
          <li>General consultation and treatment of common illness and minor injury.</li>
          <li>Antenatal, postnatal, reproductive, family-planning, newborn, child-health, immunisation, and nutrition services.</li>
          <li>Screening and continuing management for chronic disease and selected mental-health conditions.</li>
          <li>Communicable-disease detection, treatment, contact management, surveillance, and referral according to programme.</li>
          <li>Health education, environmental health, school health, community outreach, domiciliary care, and preventive programmes.</li>
        </ul>

        <h6 class="mt-3">Surgical &amp; Procedural Capacity</h6>
        <ul class="p-modal">
          <li>Wound care, dressings, injections, specimen collection, intravenous treatment, and minor outpatient procedures.</li>
          <li>Normal delivery and essential maternity care at designated Alternative Birthing Centres.</li>
          <li>Basic emergency resuscitation, oxygen, stabilisation, and ambulance referral according to equipment and staff readiness.</li>
          <li>No routine major surgery, comprehensive anaesthesia service, or specialist intensive care.</li>
        </ul>

        <h6 class="mt-3">Diagnostic &amp; Support Infrastructure</h6>
        <ul class="p-modal">
          <li>Consultation, treatment, immunisation, maternal-child, pharmacy, and basic emergency areas.</li>
          <li>Mini-laboratory, full laboratory, radiography, rehabilitation, dental, and diagnostic support according to clinic type.</li>
          <li>Cold-chain, medicine storage, public-health reporting, infection prevention, water, sanitation, and waste-management systems.</li>
          <li>Referral communication with district health offices, hospitals, ambulance services, and specialist clinics.</li>
        </ul>

        <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <strong>Note:</strong> KK1–KK7 describes clinic workload and planning needs. It should not be translated into primary, secondary, or tertiary hospital classes. A higher-numbered clinic is not necessarily clinically inferior; its scale reflects attendance, catchment, service package, staffing, and local access requirements.
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level55Modal" tabindex="-1" aria-labelledby="level55ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" alt="Secondary medical facility" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level55ModalLabel">Secondary Medical Facilities (State/District Referral Level)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="level55Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap" id="level55-overview-tab" data-bs-toggle="tab" data-bs-target="#level55-overview" type="button" role="tab" aria-controls="level55-overview" aria-selected="true">Overview</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level55-role-tab" data-bs-toggle="tab" data-bs-target="#level55-role" type="button" role="tab" aria-controls="level55-role" aria-selected="false">Role</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level55-clinical-service-tab" data-bs-toggle="tab" data-bs-target="#level55-clinical-service" type="button" role="tab" aria-controls="level55-clinical-service" aria-selected="false">Clinical Service</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="level55TabsContent">
          <div class="tab-pane fade show active" id="level55-overview" role="tabpanel" aria-labelledby="level55-overview-tab" tabindex="0">
            <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <h6 class="alert-heading fw-bold">Disclaimer</h6>
              <p class="p-modal mb-2">Secondary facilities are described by their hospital-level referral and treatment role. The Ministry of Health administratively separates hospitals with specialists from hospitals without specialists. Hospitals with specialists are further divided into major and minor specialist hospitals for administrative purposes. These categories are not defined by a single bed threshold.</p>
              <p class="p-modal mb-0"><strong>Source:</strong> <a href="https://www.moh.gov.my/images/04-penerbitan/pelan-strategik/Pelan_Strategik_KKM_compressed.pdf" class="text-primary" target="_blank" rel="noopener noreferrer">Malaysia Ministry of Health: Strategic Framework of the Medical Programme 2021–2025</a></p>
            </div>
            <p class="p-modal">Secondary medical facilities provide hospital-level emergency, outpatient, inpatient, medical, surgical, maternity, paediatric, diagnostic, rehabilitative, and stabilisation services for state, divisional, district, or multi-district catchments. They receive referrals from health clinics, rural clinics, community clinics, private clinics, pre-hospital services, and smaller hospitals. Complex cases move to a state hospital, major referral centre, special medical institution, or university hospital.</p>
            <p class="p-modal mb-0"><strong>Note:</strong> The boundary between secondary and tertiary care is not rigid. Major specialist hospitals may deliver both levels, while minor specialist hospitals concentrate on core resident specialties. Hospitals without resident specialists provide general hospital care and rely on visiting specialists, hospital clusters, teleconsultation, or upward referral.</p>
          </div>

          <div class="tab-pane fade" id="level55-role" role="tabpanel" aria-labelledby="level55-role-tab" tabindex="0">
            <ul class="p-modal mb-0">
          <li>Provide hospital-level referral care for a defined state, district, or multi-district population.</li>
          <li>Manage common and moderately complex medical, surgical, obstetric, paediatric, and emergency conditions.</li>
          <li>Provide inpatient admission, observation, diagnostics, essential surgery, maternity care, rehabilitation, and specialist consultation according to local capability.</li>
          <li>Stabilise critically ill, injured, high-risk obstetric, neonatal, or surgical patients before transfer.</li>
          <li>Support clinical supervision, outreach, referral communication, and service integration with primary-care facilities.</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="level55-clinical-service" role="tabpanel" aria-labelledby="level55-clinical-service-tab" tabindex="0">
        <h6>Approximate Bed Capacity</h6>
        <p class="p-modal">There is no fixed administrative threshold. Capacity ranges from small district hospitals with several dozen beds to major specialist hospitals with several hundred beds. Some major specialist hospitals exceed the capacity of smaller state hospitals.</p>
        <p class="p-modal">The Ministry of Health states that major or minor specialist status considers location, physical capacity, population coverage, demographics, and access to specialised care. Bed count alone does not determine the category.</p>
        <p class="p-modal">No current public national register was located that assigns an updated bed total to every hospital together with its major specialist, minor specialist, or non-specialist classification. National totals should not be converted into unsupported class averages.</p>

        <h6 class="mt-3">Public Secondary Hospital Types</h6>
        <ul class="p-modal">
          <li><strong>Major Specialist Hospital</strong> — broad resident-specialist platform, major referral workload, and selected subspecialty or regional-centre functions.</li>
          <li><strong>Minor Specialist Hospital</strong> — core resident-specialist services, historically planned around general medicine, general surgery, obstetrics and gynaecology, paediatrics, orthopaedics, anaesthesiology, radiology, pathology, psychiatry, and emergency medicine.</li>
          <li><strong>Hospital Without Specialist / Non-Specialist Hospital</strong> — general inpatient, emergency, maternity, basic diagnostic, and stabilisation services without a full resident-specialist platform.</li>
          <li><strong>Cluster or Network Hospital</strong> — a hospital that shares specialists, diagnostics, beds, operating capacity, and support services with other hospitals under an integrated cluster arrangement.</li>
        </ul>

        <h6 class="mt-3">Core Specialties</h6>
        <ul class="p-modal">
          <li>General and acute internal medicine.</li>
          <li>General surgery and emergency surgical care according to staff, theatre, anaesthesia, and blood availability.</li>
          <li>Paediatrics, obstetrics, gynaecology, maternity, and neonatal stabilisation.</li>
          <li>Orthopaedics, emergency medicine, anaesthesiology, radiology, pathology, psychiatry, and other resident specialties according to hospital category.</li>
          <li>Selected dental, rehabilitation, geriatric, communicable-disease, and noncommunicable-disease services.</li>
        </ul>

        <h6 class="mt-3">Intermediate Services</h6>
        <ul class="p-modal">
          <li>Emergency assessment, inpatient care, observation, stabilisation, and referral.</li>
          <li>Specialist outpatient consultation, chronic-disease management, antenatal and postnatal care, rehabilitation, pharmacy, and follow-up.</li>
          <li>Day-care treatment and ambulatory procedures according to approved services.</li>
          <li>Referral coordination with state hospitals, major specialist centres, special institutions, primary-care services, ambulance teams, and private providers.</li>
        </ul>

        <h6 class="mt-3">Surgical &amp; Procedural Capacity</h6>
        <ul class="p-modal">
          <li>Common elective and emergency operations according to local specialty, anaesthesia, theatre, and blood-transfusion readiness.</li>
          <li>Caesarean delivery and essential obstetric procedures at equipped hospitals.</li>
          <li>Trauma, orthopaedic, abdominal, endoscopic, and other general procedures according to category and resident expertise.</li>
          <li>Transfer for complex surgery, advanced critical care, specialised imaging, transplantation, or services unavailable locally.</li>
        </ul>

        <h6 class="mt-3">Diagnostic &amp; Support Infrastructure</h6>
        <ul class="p-modal">
          <li>Hospital laboratory, pathology, radiography, ultrasound, and other diagnostics according to facility capability.</li>
          <li>Operating theatre, recovery, maternity, emergency, oxygen, pharmacy, sterile supply, and infection-prevention infrastructure.</li>
          <li>Blood storage or transfusion services according to approved facility arrangements.</li>
          <li>Shared specialist, diagnostic, allied-health, and support services through hospital clusters and referral networks.</li>
        </ul>

        <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <strong>Note:</strong> Major specialist and minor specialist are Ministry of Health administrative categories. They should not be presented as universal national grades for university, military, or private hospitals. Current service availability must be verified at facility level because clustering, new specialist appointments, renovations, and equipment readiness can change the operational scope.
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level66Modal" tabindex="-1" aria-labelledby="level66ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" alt="Tertiary medical facility" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level66ModalLabel">Tertiary Medical Facilities (National/State/Regional Referral Level)</h5>
        </div>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="level66Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap" id="level66-overview-tab" data-bs-toggle="tab" data-bs-target="#level66-overview" type="button" role="tab" aria-controls="level66-overview" aria-selected="true">Overview</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level66-role-tab" data-bs-toggle="tab" data-bs-target="#level66-role" type="button" role="tab" aria-controls="level66-role" aria-selected="false">Role</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="level66-clinical-services-tab" data-bs-toggle="tab" data-bs-target="#level66-clinical-services" type="button" role="tab" aria-controls="level66-clinical-services" aria-selected="false">Clinical Services</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="level66TabsContent">
          <div class="tab-pane fade show active" id="level66-overview" role="tabpanel" aria-labelledby="level66-overview-tab" tabindex="0">
            <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <h6 class="alert-heading fw-bold">Disclaimer</h6>
              <p class="p-modal mb-2">The care-level classifications in this document organise Malaysia’s medical facilities according to clinical capability, referral responsibility, and position in the patient-care pathway. They must be read together with the Ministry of Health administrative hospital categories. State hospital, major specialist hospital, minor specialist hospital, hospital without specialist, and special medical institution are administrative or functional categories; they are not statutory bed grades.</p>
              <p class="p-modal mb-0"><strong>Source:</strong> <a href="https://www.moh.gov.my/images/04-penerbitan/pelan-strategik/Pelan_Strategik_Bahagian_Perkembangan_Perubatan.pdf" class="text-primary" target="_blank" rel="noopener noreferrer">Malaysia Ministry of Health: Specialty &amp; Subspecialty Framework of Ministry of Health Hospitals</a></p>
            </div>

            <p class="p-modal">Tertiary medical care forms Malaysia’s highest specialist and subspecialist referral platform. It is concentrated in state hospitals, selected major specialist hospitals, national or regional referral centres, special medical institutions, and university teaching hospitals. These facilities manage complex disease, advanced surgery, critical care, multidisciplinary treatment, and referrals that exceed the capability of district hospitals and primary-care services.</p>
            <p class="p-modal mb-0"><strong>Note:</strong> Tertiary status is determined by actual service capability and referral role rather than the hospital’s administrative label alone. A state hospital normally provides broad tertiary services, but selected major specialist hospitals and special medical institutions may act as national or regional centres for defined disciplines. Hospital clustering allows specialised services to be distributed across nearby hospitals regardless of category.</p>
          </div>

          <div class="tab-pane fade" id="level66-role" role="tabpanel" aria-labelledby="level66-role-tab" tabindex="0">
            <ul class="p-modal mb-0">
          <li>Provide national, state, or regional referral care for complex, severe, high-risk, and uncommon conditions.</li>
          <li>Receive referrals from specialist hospitals, non-specialist hospitals, health clinics, private providers, ambulance services, and direct emergency presentation.</li>
          <li>Deliver advanced medical, surgical, obstetric, paediatric, neonatal, diagnostic, intensive-care, rehabilitative, and palliative services.</li>
          <li>Coordinate multidisciplinary treatment, subspecialist consultation, advanced diagnostics, and long-term follow-up.</li>
          <li>Support teaching, specialist training, clinical research, national protocols, and service-development programmes.</li>
          <li>Refer selected patients abroad or to another national centre when a required procedure or subspecialty is unavailable locally.</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="level66-clinical-services" role="tabpanel" aria-labelledby="level66-clinical-services-tab" tabindex="0">
        <h6>Approximate Bed Capacity</h6>
        <p class="p-modal">There is no fixed tertiary threshold. Large tertiary referral hospitals commonly operate several hundred beds. Hospital Kuala Lumpur reports more than 2,300 patient beds and functions as a national referral centre for multiple specialties and subspecialties.</p>
        <p class="p-modal">Special medical institutions vary substantially. Some operate large inpatient services, while others focus on a defined national function and may have limited beds or no conventional inpatient bed base.</p>
        <p class="p-modal">As of 31 December 2024, the Ministry of Health reported 41,209 official beds across 139 hospitals and 5,646 beds across 11 special medical institutions. The published national total is not broken down by state, major specialist, minor specialist, and non-specialist administrative class.</p>

        <h6 class="mt-3">Main Public Tertiary Facility Types</h6>
        <ul class="p-modal">
          <li><strong>State Hospital</strong> — the principal Ministry of Health hospital for a state, normally carrying the broadest state referral responsibility and a wide range of specialty and subspecialty services.</li>
          <li><strong>Major Specialist Hospital</strong> — a hospital with resident specialists, broad specialty coverage, and regional or multi-district referral responsibilities; selected facilities provide substantial tertiary services.</li>
          <li><strong>Special Medical Institution</strong> — a national or regional institution focused on a defined specialty, disease group, or patient population, including cancer, respiratory medicine, rehabilitation, psychiatry, cardiac care, and women-and-children services.</li>
          <li><strong>University or Teaching Hospital</strong> — a public hospital under a university or higher-education institution that combines advanced clinical care, teaching, training, and research.</li>
          <li><strong>Armed Forces Referral Hospital</strong> — a Ministry of Defence hospital providing military health services and selected referral care outside the direct Ministry of Health hospital hierarchy.</li>
        </ul>

        <h6 class="mt-3">Core Specialties</h6>
        <ul class="p-modal">
          <li>Advanced internal medicine and medical subspecialties.</li>
          <li>General surgery and complex surgical specialties.</li>
          <li>Obstetrics, gynaecology, paediatrics, neonatology, and high-risk maternal-child care.</li>
          <li>Emergency medicine, anaesthesiology, intensive care, high-dependency care, and major trauma management.</li>
          <li>Cardiology, oncology, nephrology, neurology, neurosurgery, orthopaedics, urology, ophthalmology, otorhinolaryngology, psychiatry, rehabilitation, and other specialised disciplines according to institution.</li>
          <li>National and regional consultation for complex communicable and noncommunicable diseases.</li>
        </ul>

        <h6 class="mt-3">Intermediate Services</h6>
        <ul class="p-modal">
          <li>Twenty-four-hour emergency assessment, inpatient admission, stabilisation, and critical care.</li>
          <li>Specialist and subspecialist outpatient clinics, multidisciplinary review, day care, and follow-up.</li>
          <li>Blood transfusion, pharmacy, rehabilitation, physiotherapy, occupational therapy, dietetics, medical social work, and specialist nursing.</li>
          <li>Referral coordination with state and district hospitals, health clinics, private providers, pre-hospital teams, and other national centres.</li>
          <li>Teaching, clinical supervision, research, protocol development, and technical support for lower-level facilities.</li>
        </ul>

        <h6 class="mt-3">Surgical &amp; Procedural Capacity</h6>
        <ul class="p-modal">
          <li>Major elective and emergency surgery across multiple surgical disciplines.</li>
          <li>Advanced anaesthesia, operating-theatre, peri-operative, intensive-care, and post-operative support.</li>
          <li>Interventional radiology, endoscopy, dialysis, cardiac procedures, oncology treatment, and other specialised procedures according to institutional capability.</li>
          <li>Complex obstetric, neonatal, paediatric, trauma, transplant, and organ-support services at designated centres.</li>
          <li>Referral to another national centre or overseas provider when treatment exceeds available domestic capacity.</li>
        </ul>

        <h6 class="mt-3">Diagnostic &amp; Support Infrastructure</h6>
        <ul class="p-modal">
          <li>Advanced laboratory, pathology, microbiology, blood-bank, transfusion, and molecular diagnostic services.</li>
          <li>Radiography, ultrasound, CT, MRI, nuclear medicine, and specialised imaging according to institutional role.</li>
          <li>Intensive-care monitoring, ventilation, oxygen, isolation, emergency, theatre, sterile-supply, and medical-gas systems.</li>
          <li>Pharmacy, infection prevention, equipment maintenance, waste management, health information, teaching, and research infrastructure.</li>
        </ul>

        <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <strong>Note:</strong> Malaysia’s tertiary network is distributed. Hospital Kuala Lumpur is the largest Ministry of Health hospital and a major national referral centre, but it is not the single gateway for all tertiary care. State hospitals, major specialist hospitals, special institutions, university hospitals, and regional centres share national and regional referral responsibilities.
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== Police Classification Modals ===== -->

<div class="modal fade" id="police1Modal" tabindex="-1" aria-labelledby="police1ModalLabel" aria-hidden="true">
  <div class="modal-dialog police-modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer4.png') }}" alt="Police Station" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police1ModalLabel">Police Station</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="police1Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap px-2" id="police1-definition-tab" data-bs-toggle="tab" data-bs-target="#police1-definition" type="button" role="tab" aria-controls="police1-definition" aria-selected="true" style="font-size: 12px;">Definition &amp; Purpose</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police1-commander-tab" data-bs-toggle="tab" data-bs-target="#police1-commander" type="button" role="tab" aria-controls="police1-commander" aria-selected="false" style="font-size: 12px;">Commander</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police1-classification-tab" data-bs-toggle="tab" data-bs-target="#police1-classification" type="button" role="tab" aria-controls="police1-classification" aria-selected="false" style="font-size: 12px;">Police Station Classification</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police1-responsibilities-tab" data-bs-toggle="tab" data-bs-target="#police1-responsibilities" type="button" role="tab" aria-controls="police1-responsibilities" aria-selected="false" style="font-size: 12px;">Responsibilities/Roles/Function</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police1-distribution-tab" data-bs-toggle="tab" data-bs-target="#police1-distribution" type="button" role="tab" aria-controls="police1-distribution" aria-selected="false" style="font-size: 12px;">Geographic Distribution</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police1-equivalent-tab" data-bs-toggle="tab" data-bs-target="#police1-equivalent" type="button" role="tab" aria-controls="police1-equivalent" aria-selected="false" style="font-size: 12px;">Police – Civil – Military Equivalent</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="police1TabsContent">
          <div class="tab-pane fade show active" id="police1-definition" role="tabpanel" aria-labelledby="police1-definition-tab" tabindex="0">
            <p><strong>Definition:</strong> A <em>Balai Polis</em> (Police Station) is the principal local-level territorial police unit of the Royal Malaysia Police (<em>Polis Diraja Malaysia</em> – PDRM). It operates under the administration and command of the relevant District Police Headquarters (<em>Ibu Pejabat Polis Daerah</em> – IPD) and provides frontline policing services in the assigned police station jurisdiction.</p>
            <p>PDRM formally organizes its territorial structure through four organizational layers: <strong>Federal Headquarters at Bukit Aman, Contingent Police Headquarters (IPK), District Police Headquarters (IPD), and Police Stations (<em>Balai Polis</em>).</strong> Therefore, <em>Balai Polis</em> represents the fourth organizational layer overall and the third subnational territorial police layer, below the Contingent and District levels.</p>
            <p><em>Balai Polis</em> are the primary local interface between PDRM and the public. Police reports may be lodged at any police station, including reports concerning incidents that occurred outside the station's immediate jurisdiction. Under Section 107 of the Criminal Procedure Code, reports received by the <em>Ketua Polis Balai</em> (KPB) or another police officer acting under the KPB's direction are formally recorded as police reports.</p>
            <p><strong>Purpose:</strong> <em>Balai Polis</em> maintain a permanent local police presence, receive public reports and complaints, respond to incidents, conduct patrol and crime-prevention activities, provide public assistance, support criminal investigations, and implement district-level policing operations within their assigned area.</p>
            <p><strong>Command Level:</strong> Local police command — third subnational territorial police layer; subordinate to the District Police Headquarters (IPD).</p>
            <p class="mb-0"><strong>Administrative Equivalent:</strong> Sub-district / Municipality / Urban Area</p>
          </div>

          <div class="tab-pane fade" id="police1-commander" role="tabpanel" aria-labelledby="police1-commander-tab" tabindex="0">
            <p><em>Balai Polis</em> is led by a <em>Ketua Polis Balai</em> (KPB) – Chief of Police Station / Police Station Chief, who is responsible for station personnel, daily police operations, public services, local security conditions, and implementation of instructions issued by the relevant <em>Ketua Polis Daerah</em> (KPD – District Police Chief) through the IPD.</p>
            <p>The rank of the KPB varies according to the station's manpower establishment, operational requirements, jurisdiction, workload, and importance.</p>
            <p>PDRM's published organizational structure states that a KPB may range from Sarjan (Sergeant) to Inspektor (Inspector). However, other official PDRM records demonstrate a broader practical range:</p>
            <ul>
              <li><strong>Sarjan Mejar (Sergeant Major):</strong> Recorded as KPB at Balai Polis Batu 18, Hulu Langat.</li>
              <li><strong>Sub-Inspektor (Sub-Inspector):</strong> Recorded at Balai Polis Putra Heights, Kuala Berang, Masjid Tanah, Gadek and other stations.</li>
              <li><strong>Inspektor (Inspector):</strong> Recorded at Balai Polis Bukit Puchong and Balai Polis Ulu Tiram.</li>
              <li><strong>Asisten Superintenden Polis (ASP – Assistant Superintendent of Police):</strong> Recorded at larger or strategically significant stations including Balai Polis Bandar Sunway and, most recently, Balai Polis Karakit in Sabah in May 2026.</li>
            </ul>
            <p><strong>Typical Head Rank:</strong> Sarjan Mejar / Sub-Inspektor / Inspektor, with ASP at selected larger or operationally significant stations.</p>
            <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <strong>Note:</strong> KPB rank should therefore not be presented as a single fixed rank. The general PDRM structure page describes a Sergeant–Inspector range, while official PDRM station records confirm that ASP appointments occur in practice.
            </div>
          </div>

          <div class="tab-pane fade" id="police1-classification" role="tabpanel" aria-labelledby="police1-classification-tab" tabindex="0">
            <p>PDRM classifies police stations based on their approved manpower (<em>perjawatan</em>), which reflects how large the station is and how much policing work it handles. Parliamentary records identify five current categories, A to E, from the largest stations to the smallest. The approved number of personnel is the authorized strength, which may differ from actual staffing levels due to vacancies.</p>

            <div class="table-responsive my-4">
              <table class="table table-bordered align-middle mb-0">
                <thead class="text-white text-center">
                  <tr>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Category</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Personnel</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Definition</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row" class="text-white text-center" style="background-color: #4778c3;">A</th>
                    <td class="fw-bold text-center" style="background-color: #afc2e2;">150–287 personnel</td>
                    <td style="background-color: #afc2e2;">Very large police stations that serve high-demand or highly populated areas. They have many officers and handle a wide range of policing duties.</td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-white text-center" style="background-color: #4778c3;">B</th>
                    <td class="fw-bold text-center" style="background-color: #d2dcef;">99–149 personnel</td>
                    <td style="background-color: #d2dcef;">Large stations that cover busy areas and handle regular crime prevention, patrols, and public service duties.</td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-white text-center" style="background-color: #4778c3;">C</th>
                    <td class="fw-bold text-center" style="background-color: #afc2e2;">67–98 personnel</td>
                    <td style="background-color: #afc2e2;">Medium-sized stations that provide standard policing services such as patrols, reporting, and local crime response.</td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-white text-center" style="background-color: #4778c3;">D</th>
                    <td class="fw-bold text-center" style="background-color: #d2dcef;">38–66 personnel</td>
                    <td style="background-color: #d2dcef;">Small stations that focus on basic policing duties and serve smaller or less busy areas.</td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-white text-center" style="background-color: #4778c3;">E</th>
                    <td class="fw-bold text-center" style="background-color: #afc2e2;">7–37 personnel</td>
                    <td style="background-color: #afc2e2;">Very small stations with limited staff, mainly handling basic services, patrol presence, and local incident response.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <p class="mb-0"><strong>Category F (Historical):</strong> Was used in the Balai League Table (BLT) introduced in 2014, which expanded the competition categories from A–E to A–F. It included certain stations such as Balai Polis Tubau, Lingga, and Kayu Ara Pasong. However, current official PDRM classification systems only recognize Categories A to E, so Category F is considered a historical or competition-based category, not part of the current staffing structure.</p>
          </div>

          <div class="tab-pane fade" id="police1-responsibilities" role="tabpanel" aria-labelledby="police1-responsibilities-tab" tabindex="0">
            <p>A <em>Balai Polis</em> is the primary frontline territorial police unit under the District Police Headquarters. It implements PDRM duties at community level and maintains direct contact with residents, businesses, local institutions, and other authorities.</p>

            <h6 class="fw-bold mt-4">Responsibilities</h6>
            <ul>
              <li><strong>Public Security and Order:</strong> Maintain law and public order within the assigned station jurisdiction and respond to local security disturbances.</li>
              <li><strong>Frontline Police Response:</strong> Respond to crimes, disturbances, emergencies, public-assistance requests, and other incidents requiring immediate police presence.</li>
              <li><strong>Police Reports and Complaints:</strong> Receive and formally record police reports, complaints, and information from members of the public. Police reports may be lodged at any <em>Balai Polis</em> regardless of where the incident occurred.</li>
              <li><strong>Crime Prevention:</strong> Conduct visible policing, patrols, local surveillance, preventive activities, and community-based crime-prevention measures.</li>
              <li><strong>Local Law Enforcement:</strong> Enforce applicable criminal and public-order laws, detect offences, identify suspects, make lawful arrests when required, and support prosecution processes. These duties reflect PDRM's statutory responsibilities to maintain law and order, preserve national security, prevent and detect crime, arrest offenders, and gather security intelligence.</li>
              <li><strong>Incident and Crime-Scene Response:</strong> Provide the initial police response to reported incidents, secure scenes, protect persons and property, identify witnesses, preserve evidence, and coordinate further investigation with the appropriate IPD investigation branch when required.</li>
              <li><strong>Patrol Operations:</strong> Conduct foot patrols, mobile patrols, beat duties, and other local policing activities to maintain police visibility and deter criminal activity.</li>
              <li><strong>Community Policing:</strong> Maintain engagement with residents, community leaders, businesses, schools, and local organizations to identify security concerns, improve police-community cooperation, and support crime prevention.</li>
              <li><strong>Public Assistance:</strong> Provide information, receive requests for police assistance, direct members of the public to appropriate police services, and provide immediate assistance during emergencies or security incidents.</li>
              <li><strong>Local Security Monitoring:</strong> Maintain awareness of crime patterns, public-order conditions, suspicious activities, and emerging security issues within the station jurisdiction and report significant developments through the district command structure.</li>
              <li><strong>Support to District Police Operations:</strong> Provide personnel, local information, patrol support, checkpoints, searches, area security, arrests, and other operational assistance to the IPD during district-level operations.</li>
              <li><strong>Command and Administration:</strong> Manage station personnel, duty rosters, station security, reporting, police records, equipment, local patrol deployment, and implementation of directives issued by the District Police Headquarters.</li>
            </ul>

            <h6 class="fw-bold mt-4">Roles &amp; Functions</h6>
            <ul class="mb-0">
              <li><strong>Frontline Police Presence:</strong> Serve as the permanent local operational presence of PDRM and the main point of physical access to police services.</li>
              <li><strong>First Police Contact:</strong> Act as the initial police contact for crimes, disturbances, emergencies, complaints, and requests for assistance.</li>
              <li><strong>Local Operational Unit:</strong> Conduct routine patrol, response, prevention, enforcement, and community-security activities within the assigned police jurisdiction.</li>
              <li><strong>Information and Reporting Channel:</strong> Collect local crime and security information and transmit relevant developments to the IPD and appropriate specialist branches.</li>
              <li><strong>Investigation Support:</strong> Conduct initial case handling and support investigations while more complex or specialized investigations are handled or coordinated by the appropriate investigation divisions at district or higher levels.</li>
              <li><strong>Community Security Platform:</strong> Maintain regular police engagement with communities and support local crime-prevention and public-safety programmes.</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="police1-distribution" role="tabpanel" aria-labelledby="police1-distribution-tab" tabindex="0">
            <p><em>Balai Polis</em> are distributed throughout Malaysia under the respective District Police Headquarters (IPD). The PDRM organizational structure currently published on its official website lists 837 police stations nationwide.</p>
            <p>Each <em>Balai Polis</em> is assigned its own <em>kawasan pentadbiran</em>, or police administrative area. The size and character of these jurisdictions vary considerably according to population, geography, development, crime patterns, transportation networks, and operational requirements.</p>
            <p class="mb-2"><strong>Official PDRM examples demonstrate this variation:</strong></p>
            <ul>
              <li><strong>Balai Polis Bandar Sunway</strong> covers a predominantly residential and commercial urban jurisdiction.</li>
              <li><strong>Balai Polis Batu 18</strong> covers residential, industrial, and commercial areas.</li>
              <li><strong>Balai Polis Kuala Berang</strong> covers villages, business areas, and agricultural areas.</li>
              <li><strong>Balai Polis Tambunan</strong> covers a much larger rural jurisdiction and receives support from subordinate police posts.</li>
            </ul>
            <p class="mb-0">Police station boundaries therefore do not necessarily correspond directly to a civil municipality, <em>mukim</em>, town, or sub-district boundary. A station's jurisdiction is determined according to PDRM police-administrative and operational requirements.</p>
          </div>

          <div class="tab-pane fade" id="police1-equivalent" role="tabpanel" aria-labelledby="police1-equivalent-tab" tabindex="0">
            <h6 class="fw-bold">Police – Civil – Military Equivalent</h6>
            <ul>
              <li><strong>Police:</strong> <em>Balai Polis</em> — local territorial police station and frontline police command.</li>
              <li><strong>Civil:</strong> Approximately sub-district / town / municipality / local community level, depending on the station's assigned jurisdiction.</li>
              <li><strong>Military:</strong> No direct standardized equivalent. The nearest comparison may be a Malaysian Army battalion/regiment or local military element with responsibility for the same geographic area, but Army units are organized according to operational formations and Areas of Responsibility rather than <em>Balai Polis</em> or civil administrative boundaries. Official Malaysian Army material demonstrates that battalion and regiment Areas of Responsibility can cover individual districts, groups of districts, or other designated areas.</li>
            </ul>
            <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <strong>Administrative Relationship:</strong> There is no fixed one-to-one alignment between <em>Balai Polis</em>, civilian administrative units, and Malaysian Army units. <em>Balai Polis</em> jurisdictions are established according to PDRM policing requirements, while Malaysian Army formations are organized according to military operational requirements.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police2Modal" tabindex="-1" aria-labelledby="police2ModalLabel" aria-hidden="true">
  <div class="modal-dialog police-modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer3.png') }}" alt="District Police Headquarters" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police2ModalLabel">District Police Headquarters (IPD)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="police2Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap px-2" id="police2-definition-tab" data-bs-toggle="tab" data-bs-target="#police2-definition" type="button" role="tab" aria-controls="police2-definition" aria-selected="true" style="font-size: 12px;">Definition &amp; Purpose</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police2-commander-tab" data-bs-toggle="tab" data-bs-target="#police2-commander" type="button" role="tab" aria-controls="police2-commander" aria-selected="false" style="font-size: 12px;">Commander</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police2-classification-tab" data-bs-toggle="tab" data-bs-target="#police2-classification" type="button" role="tab" aria-controls="police2-classification" aria-selected="false" style="font-size: 12px;">IPD Rank Differentiation</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police2-responsibilities-tab" data-bs-toggle="tab" data-bs-target="#police2-responsibilities" type="button" role="tab" aria-controls="police2-responsibilities" aria-selected="false" style="font-size: 12px;">Responsibilities/Functions</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police2-distribution-tab" data-bs-toggle="tab" data-bs-target="#police2-distribution" type="button" role="tab" aria-controls="police2-distribution" aria-selected="false" style="font-size: 12px;">Geographic Distribution</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police2-equivalent-tab" data-bs-toggle="tab" data-bs-target="#police2-equivalent" type="button" role="tab" aria-controls="police2-equivalent" aria-selected="false" style="font-size: 12px;">Police-Civil-Military Equivalent</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="police2TabsContent">
          <div class="tab-pane fade show active" id="police2-definition" role="tabpanel" aria-labelledby="police2-definition-tab" tabindex="0">
            <p><strong>Definition:</strong> The District Police Headquarters (<em>Ibu Pejabat Polis Daerah</em>—IPD) is the principal district-level territorial command of the Royal Malaysia Police (<em>Polis Diraja Malaysia</em>—PDRM). It commands and coordinates policing activities throughout an assigned police district and operates under the relevant State/Contingent Police Headquarters (<em>Ibu Pejabat Polis Kontinjen</em>—IPK).</p>
            <p>In PDRM's national organizational structure, the IPD is positioned below the Federal Police Headquarters at Bukit Aman and the Contingent Police Headquarters, and above the police-station level. PDRM officially identifies four organizational layers: <strong>Federal Headquarters &rarr; Contingent Headquarters &rarr; District Police Headquarters &rarr; Police Station.</strong> The official PDRM structure states that Malaysia has 148 police administrative districts, each led by a <em>Ketua Polis Daerah</em> (KPD).</p>
            <p>The IPD is the command, operational, investigative and administrative centre for policing in its district. Depending on the district, it coordinates police stations (<em>Balai Polis</em>), police posts (<em>Pondok Polis</em>), patrol resources, investigation branches, traffic functions, crime-prevention activities and other district police elements. PDRM directories confirm that police posts and community police posts operate under numerous district jurisdictions, although PDRM's formal four-layer organizational structure identifies the police station as the lowest principal organizational level.</p>
            <p><strong>Purpose:</strong> The IPD maintains public security and order, enforces the law, prevents and investigates crime, manages district-level police operations, supervises subordinate police facilities and provides police services to communities throughout its assigned jurisdiction.</p>
            <p><strong>Command Level:</strong> District police command—third organizational level of PDRM overall and the principal territorial command below the State/Contingent Police Headquarters.</p>
            <p class="mb-0"><strong>Administrative Equivalent:</strong> District (<em>Daerah</em>), as a general administrative comparison. However, a PDRM police district is an operational police jurisdiction and does not necessarily correspond exactly to a civilian administrative district.</p>
          </div>

          <div class="tab-pane fade" id="police2-commander" role="tabpanel" aria-labelledby="police2-commander-tab" tabindex="0">
            <p>The IPD is led by the District Police Chief (<em>Ketua Polis Daerah</em>—KPD). The English title Officer-in-Charge of Police District (OCPD) is also used in Malaysian police terminology and English-language PDRM material.</p>
            <p>PDRM states that the rank attached to a KPD position depends on the size of the police district. There is therefore no single rank applicable to all District Police Chiefs.</p>

            <p class="mb-2"><strong>Typical KPD ranks include:</strong></p>
            <ul>
              <li><strong>Senior Assistant Commissioner of Police (SAC):</strong> Used for selected major or particularly significant police districts. For example, the KPD of Wangsa Maju was recorded by PDRM in March 2026 at SAC rank.</li>
              <li><strong>Assistant Commissioner of Police (ACP):</strong> Commonly used for major urban, metropolitan or strategically important police districts. Current 2026 examples include Putrajaya, Kulai, Kajang, Tawau and several Johor Bahru-area districts.</li>
              <li><strong>Superintendent of Police (Supt.):</strong> Used for many other police districts. Current examples include Kudat, Segamat, Sipitang, Pontian, Brickfields and Alor Gajah.</li>
            </ul>

            <p class="mb-0">The KPD exercises command over district police operations and reports through the relevant state or territorial PDRM command structure.</p>
          </div>

          <div class="tab-pane fade" id="police2-classification" role="tabpanel" aria-labelledby="police2-classification-tab" tabindex="0">
            <h6 class="fw-bold">IPD Rank Differentiation</h6>
            <p>PDRM does not publicly identify a nationwide Type A/Type B/Type C classification system for IPD comparable to the Indonesian Polda classification. Instead, the official PDRM structure states that the rank of the KPD depends on the size of the police district. Operational importance, population, urbanisation, policing workload, strategic facilities and security requirements may therefore be reflected in the seniority of the command appointment, but these should not be presented as formal IPD classes unless specifically designated by PDRM.</p>

            <div class="table-responsive mt-4">
              <table class="table table-bordered align-middle mb-0">
                <thead class="text-white text-center">
                  <tr>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">District Command</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Typical Head Position &amp; Rank</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">General Application</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Selected major police districts</th>
                    <td style="background-color: #afc2e2;">KPD — <strong>SAC</strong></td>
                    <td style="background-color: #afc2e2;">Selected high-demand or strategically significant district commands</td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Major police districts</th>
                    <td style="background-color: #d2dcef;">KPD — <strong>ACP</strong></td>
                    <td style="background-color: #d2dcef;">Major urban, metropolitan or strategically important districts</td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Other police districts</th>
                    <td style="background-color: #afc2e2;">KPD — <strong>Supt.</strong></td>
                    <td style="background-color: #afc2e2;">Standard district police commands throughout Malaysia</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="alert border-0 border-start border-3 rounded-1 mt-4 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <strong>Note:</strong> These categories describe observed command-rank patterns and are not formal PDRM IPD classifications.
            </div>
          </div>

          <div class="tab-pane fade" id="police2-responsibilities" role="tabpanel" aria-labelledby="police2-responsibilities-tab" tabindex="0">
            <p>An IPD is responsible for overall policing and security management in its district, including enforcement, prevention, investigation, and coordination of police resources.</p>

            <h6 class="fw-bold mt-4">Core Responsibilities</h6>
            <ul class="mb-0">
              <li>Maintain public order and safety in the district.</li>
              <li>Enforce laws through patrols, arrests, and police operations.</li>
              <li>Investigate crimes and coordinate criminal investigations.</li>
              <li>Prevent crime through patrols, visibility policing, and community engagement.</li>
              <li>Manage traffic enforcement and road safety.</li>
              <li>Handle public order situations including gatherings, protests, and emergencies.</li>
              <li>Respond to emergencies and disasters in coordination with other agencies.</li>
              <li>Provide police services including reports, assistance, and public support.</li>
              <li>Coordinate with other agencies (courts, local government, emergency services, etc.).</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="police2-distribution" role="tabpanel" aria-labelledby="police2-distribution-tab" tabindex="0">
            <p>PDRM's official structure identifies 148 police administrative districts, each led by a KPD, under 14 state contingents.</p>
            <p>Police districts may align with civil districts but are primarily defined for operational policing needs, not administrative boundaries.</p>
            <p>Major urban areas may contain multiple IPDs, while some strategic locations (e.g. airports) may have dedicated district commands.</p>
            <p class="mb-0">Below the IPD are 837 police stations, supported by police posts and community police posts.</p>
          </div>

          <div class="tab-pane fade" id="police2-equivalent" role="tabpanel" aria-labelledby="police2-equivalent-tab" tabindex="0">
            <ul>
              <li><strong>District (<em>Daerah</em>):</strong> Civil administrative level responsible for district-level government administration where the district system applies.</li>
              <li><strong>IPD:</strong> PDRM territorial police command responsible for policing and law enforcement across an assigned police district.</li>
              <li><strong>Malaysian Armed Forces:</strong> No direct standardized district-level military equivalent to an IPD. The Malaysian Army organizes territorial and operational forces through field commands, divisions, brigades and subordinate military formations rather than maintaining a police-style military headquarters for every civil district. Current Malaysian Army material identifies Field Commands, Divisions and Brigades as major command formations.</li>
            </ul>
            <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <strong>Note:</strong> The civilian district and PDRM police district may be geographically similar, but they operate under different legal authorities and command structures. Unlike Indonesia's relatively useful Province–Kodam–Polda territorial comparison, Malaysia does not have a standardized District–Military District–IPD three-way equivalent. An IPD should therefore be treated primarily as a PDRM police operational jurisdiction, not as a direct counterpart to a Malaysian Armed Forces territorial command.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police3Modal" tabindex="-1" aria-labelledby="police3ModalLabel" aria-hidden="true">
  <div class="modal-dialog police-modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer2.png') }}" alt="State police contingent headquarters" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police3ModalLabel">State Police Contingent Headquarters (IPK)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="police3Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap" id="police3-definition-tab" data-bs-toggle="tab" data-bs-target="#police3-definition" type="button" role="tab" aria-controls="police3-definition" aria-selected="true">Definition &amp; Purpose</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="police3-commander-tab" data-bs-toggle="tab" data-bs-target="#police3-commander" type="button" role="tab" aria-controls="police3-commander" aria-selected="false">Commander</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="police3-classification-tab" data-bs-toggle="tab" data-bs-target="#police3-classification" type="button" role="tab" aria-controls="police3-classification" aria-selected="false">Contingent Command Classification</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="police3-responsibilities-tab" data-bs-toggle="tab" data-bs-target="#police3-responsibilities" type="button" role="tab" aria-controls="police3-responsibilities" aria-selected="false">Responsibilities / Functions</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap" id="police3-distribution-tab" data-bs-toggle="tab" data-bs-target="#police3-distribution" type="button" role="tab" aria-controls="police3-distribution" aria-selected="false">Geographic Distribution</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="police3TabsContent">
          <div class="tab-pane fade show active" id="police3-definition" role="tabpanel" aria-labelledby="police3-definition-tab" tabindex="0">
            <p><strong>Definition:</strong> Ibu Pejabat Polis Kontinjen (IPK), or Police Contingent Headquarters, is the highest territorial command of the Royal Malaysia Police (PDRM) below Bukit Aman. It oversees policing, investigations, intelligence, public order, traffic enforcement, and administration in its assigned contingent.</p>
            <p>PDRM is structured into four levels: <strong>Federal Headquarters &rarr; Contingent &rarr; District &rarr; Police Station.</strong> There are 14 Police Contingents in total, covering all 13 states and the Federal Territory of Kuala Lumpur.</p>
            <p>Most contingents align with state boundaries, but not all Federal Territories have their own IPK. Putrajaya is under IPD Putrajaya (Kuala Lumpur Contingent), while Labuan is under IPD Labuan (Sabah Contingent).</p>
            <p>Unlike state governments, IPKs are part of a national police system under Bukit Aman. They implement national policies and operational directives.</p>
            <p><strong>Purpose:</strong> IPKs maintain law and order, prevent and investigate crime, ensure public safety, manage traffic enforcement, and coordinate police operations in their territory, in line with the Police Act 1967.</p>
            <p><strong>Command Level:</strong> State-level territorial police command (highest territorial police command)</p>
            <p class="mb-0"><strong>Administrative Equivalent:</strong> State and Federal Territory</p>
          </div>

          <div class="tab-pane fade" id="police3-commander" role="tabpanel" aria-labelledby="police3-commander-tab" tabindex="0">
            <p>PDRM uses different titles depending on the contingent:</p>
            <ul>
              <li><strong>Sabah Police Contingent:</strong> Led by the Commissioner of Police Sabah (<em>Pesuruhjaya Polis Sabah</em>), rank CP.</li>
              <li><strong>Sarawak Police Contingent:</strong> Led by the Commissioner of Police Sarawak (<em>Pesuruhjaya Polis Sarawak</em>), rank CP.</li>
              <li><strong>Other State Contingents:</strong> Led by the State Chief of Police (<em>Ketua Polis Negeri</em>), usually CP or DCP depending on appointment.</li>
              <li><strong>Kuala Lumpur Police Contingent:</strong> Led by the Chief of Police Kuala Lumpur (<em>Ketua Polis Kuala Lumpur</em>), rank CP, also overseeing IPD Putrajaya.</li>
            </ul>
            <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <strong>Note:</strong> <em>Ketua Polis Negeri</em> should not be abbreviated as KPN, as this refers to the Inspector-General of Police (<em>Ketua Polis Negara</em>).
            </div>
          </div>

          <div class="tab-pane fade" id="police3-classification" role="tabpanel" aria-labelledby="police3-classification-tab" tabindex="0">
            <p>PDRM does not use formal “Type A/B” classifications for contingents. All are simply part of the Contingent Level structure.</p>
            <p>Command ranks vary by appointment. Some contingents are led by CPs (e.g., Kuala Lumpur, Selangor, Johor, Sabah, and Sarawak), while others are led by DCPs. This reflects staffing arrangements, not official categories.</p>

            <div class="table-responsive mt-4">
              <table class="table table-bordered align-middle mb-0">
                <thead class="text-white text-center" style="background-color: #4778c3;">
                  <tr>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Unit</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Head</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Rank</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Role</th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="background-color: #afc2e2;">
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Sabah Contingent</th>
                    <td style="background-color: #afc2e2;">CP Sabah</td>
                    <td style="background-color: #afc2e2;">CP</td>
                    <td style="background-color: #afc2e2;">State-level command; includes Labuan IPD</td>
                  </tr>
                  <tr style="background-color: #d2dcef;">
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Sarawak Contingent</th>
                    <td style="background-color: #d2dcef;">CP Sarawak</td>
                    <td style="background-color: #d2dcef;">CP</td>
                    <td style="background-color: #d2dcef;">State-level command</td>
                  </tr>
                  <tr style="background-color: #afc2e2;">
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Other States</th>
                    <td style="background-color: #afc2e2;">State Chief of Police</td>
                    <td style="background-color: #afc2e2;">CP/DCP</td>
                    <td style="background-color: #afc2e2;">State-level command</td>
                  </tr>
                  <tr style="background-color: #d2dcef;">
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Kuala Lumpur</th>
                    <td style="background-color: #d2dcef;">CP Kuala Lumpur</td>
                    <td style="background-color: #d2dcef;">CP</td>
                    <td style="background-color: #d2dcef;">Federal Territory command; includes Putrajaya IPD</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="police3-responsibilities" role="tabpanel" aria-labelledby="police3-responsibilities-tab" tabindex="0">
            <p>An IPK is the main command between Bukit Aman and District Police Headquarters (IPD). It supervises all policing activities in its area.</p>

            <h6 class="fw-bold mt-4">Core Responsibilities</h6>
            <ul class="mb-0">
              <li><strong>Law &amp; Order:</strong> Maintain public safety, prevent disturbances, and protect life and property.</li>
              <li><strong>Crime Enforcement:</strong> Coordinate investigations, arrests, and enforcement operations.</li>
              <li><strong>Public Order:</strong> Manage protests, major events, and security incidents.</li>
              <li><strong>Intelligence:</strong> Collect and analyze security intelligence through Special Branch.</li>
              <li><strong>Criminal Investigation:</strong> Supervise serious and multi-district cases.</li>
              <li><strong>Commercial &amp; Cybercrime:</strong> Handle fraud, cybercrime, and commercial offences.</li>
              <li><strong>Narcotics:</strong> Enforce drug-related laws and operations.</li>
              <li><strong>Traffic Control:</strong> Manage road safety, accidents, and enforcement.</li>
              <li><strong>Crime Prevention:</strong> Conduct community policing and prevention programs.</li>
              <li><strong>Emergency Response:</strong> Support disaster and crisis operations.</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="police3-distribution" role="tabpanel" aria-labelledby="police3-distribution-tab" tabindex="0">
            <p>PDRM has 14 Police Contingents:</p>
            <div class="row g-0">
              <div class="col-12 col-md-4">
                <ol class="mb-md-0">
                  <li>Perlis</li>
                  <li>Kedah</li>
                  <li>Pulau Pinang</li>
                  <li>Perak</li>
                  <li>Selangor</li>
                </ol>
              </div>
              <div class="col-12 col-md-4">
                <ol start="6" class="mb-md-0">
                  <li>Kuala Lumpur</li>
                  <li>Negeri Sembilan</li>
                  <li>Melaka</li>
                  <li>Johor</li>
                  <li>Pahang</li>
                </ol>
              </div>
              <div class="col-12 col-md-4">
                <ol start="11" class="mb-0">
                  <li>Terengganu</li>
                  <li>Kelantan</li>
                  <li>Sabah</li>
                  <li>Sarawak</li>
                </ol>
              </div>
            </div>

            <h6 class="fw-bold mt-4">Federal Territory Notes</h6>
            <ul class="mb-0">
              <li><strong>Kuala Lumpur:</strong> Has its own IPK.</li>
              <li><strong>Putrajaya:</strong> Under IPD Putrajaya (Kuala Lumpur Contingent).</li>
              <li><strong>Labuan:</strong> Under IPD Labuan (Sabah Contingent).</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police4Modal" tabindex="-1" aria-labelledby="police4ModalLabel" aria-hidden="true">
  <div class="modal-dialog police-modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer1.png') }}" alt="National Police HQ" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police4ModalLabel">Royal Malaysia Police / Polis Diraja Malaysia (PDRM)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs info-modal-tabs px-3 pt-2 w-100" id="police4Tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-nowrap px-2" id="police4-definition-tab" data-bs-toggle="tab" data-bs-target="#police4-definition" type="button" role="tab" aria-controls="police4-definition" aria-selected="true">Definition &amp; Purpose</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police4-commander-tab" data-bs-toggle="tab" data-bs-target="#police4-commander" type="button" role="tab" aria-controls="police4-commander" aria-selected="false">Commander</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police4-classification-tab" data-bs-toggle="tab" data-bs-target="#police4-classification" type="button" role="tab" aria-controls="police4-classification" aria-selected="false">National Police Classification</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police4-responsibilities-tab" data-bs-toggle="tab" data-bs-target="#police4-responsibilities" type="button" role="tab" aria-controls="police4-responsibilities" aria-selected="false">Responsibilities/Roles/Functions</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police4-distribution-tab" data-bs-toggle="tab" data-bs-target="#police4-distribution" type="button" role="tab" aria-controls="police4-distribution" aria-selected="false">Geographic Distribution</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-nowrap px-2" id="police4-equivalent-tab" data-bs-toggle="tab" data-bs-target="#police4-equivalent" type="button" role="tab" aria-controls="police4-equivalent" aria-selected="false">Police – Civil – Military Equivalent</button>
          </li>
        </ul>

        <div class="tab-content pt-4" id="police4TabsContent">
          <div class="tab-pane fade show active" id="police4-definition" role="tabpanel" aria-labelledby="police4-definition-tab" tabindex="0">
            <p><strong>Definition:</strong> The Royal Malaysia Police (<em>Polis Diraja Malaysia</em> – PDRM) is Malaysia’s national police force and represents the highest institutional level in the country’s police hierarchy. It exercises nationwide authority over PDRM headquarters elements and the territorial police structure, including State Police Contingents, Police Districts, Police Stations, and supporting local police facilities.</p>
            <p>PDRM is responsible for law enforcement, public order, internal security, crime prevention and investigation, security intelligence, traffic enforcement, and the protection of people and property throughout Malaysia. It operates nationally under the Ministry of Home Affairs (<em>Kementerian Dalam Negeri</em> – KDN), with its federal headquarters at Bukit Aman, Kuala Lumpur.</p>
            <p>The principal statutory framework governing PDRM is the Police Act 1967 [Act 344]. PDRM operates through four formal organizational levels: Federal, Contingent/State, District, and Police Station. The federal level is represented by the Royal Malaysia Police Headquarters at Bukit Aman, which serves as the national command and administrative centre and houses the Inspector-General of Police and other senior PDRM leadership.</p>
            <p><strong>Purpose:</strong> PDRM maintains law and order, preserves national peace and security, prevents and detects crime, apprehends offenders, conducts criminal investigations, collects security intelligence, and provides policing services throughout Malaysia.</p>
            <p class="mb-0"><strong>Command Level:</strong> National / Federal police command — highest level of the police hierarchy in Malaysia.</p>
          </div>

          <div class="tab-pane fade" id="police4-commander" role="tabpanel" aria-labelledby="police4-commander-tab" tabindex="0">
            <p>PDRM is headed by the Inspector-General of Police – <em>Ketua Polis Negara</em> (KPN), who holds the rank of Inspector General (IG), the highest professional rank in the Royal Malaysia Police.</p>
            <p class="mb-0">The Inspector-General of Police exercises national leadership over PDRM and is responsible for the overall administration, operational direction, organizational readiness, and coordination of the police force. The IGP is supported by the Deputy Inspector-General of Police (DIG) and the senior leadership of PDRM Headquarters.</p>
          </div>

          <div class="tab-pane fade" id="police4-classification" role="tabpanel" aria-labelledby="police4-classification-tab" tabindex="0">
            <h6 class="fw-bold">National Police Classification</h6>
            <p>PDRM constitutes a single centralized national police organization. Unlike territorial commands, there is no Type A, Type B, or comparable classification at the national PDRM level.</p>
            <p>For organizational purposes, the national force can be divided into two principal components:</p>

            <div class="table-responsive my-4">
              <table class="table table-bordered align-middle mb-0">
                <thead class="text-white text-center">
                  <tr>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Organizational Component</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Level</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Command Relationship</th>
                    <th scope="col" style="background-color: #4778c3; color: #fff;">Role</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row" class="text-white" style="background-color: #4778c3;">PDRM Headquarters / HQ Elements</th>
                    <td style="background-color: #afc2e2;">Federal / National</td>
                    <td style="background-color: #afc2e2;">Under the Inspector-General of Police</td>
                    <td style="background-color: #afc2e2;">National leadership, administration, intelligence, investigation, operational coordination, specialist policing, logistics, integrity, traffic, and policy.</td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-white" style="background-color: #4778c3;">Territorial Units</th>
                    <td style="background-color: #d2dcef;">Nationwide territorial structure</td>
                    <td style="background-color: #d2dcef;">Under national PDRM authority through the territorial chain of command</td>
                    <td style="background-color: #d2dcef;">Conduct territorial policing through State Police Contingents, Police Districts, Police Stations, and supporting local police facilities.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <p class="mb-0">This arrangement means that HQ Elements and Territorial Units are two major organizational components under national PDRM authority. Territorial units should not be shown as subordinate to a single headquarters department; they operate through their own territorial command chain under the overall authority of the national PDRM leadership.</p>
          </div>

          <div class="tab-pane fade" id="police4-responsibilities" role="tabpanel" aria-labelledby="police4-responsibilities-tab" tabindex="0">
            <p>PDRM is the principal national policing institution responsible for enforcing Malaysian law and maintaining national public security. Its primary responsibilities include:</p>

            <h6 class="fw-bold mt-4">Responsibilities</h6>
            <ul>
              <li><strong>Law Enforcement and Public Order:</strong> Enforce criminal and other applicable laws, maintain public order, and protect public safety throughout Malaysia. PDRM's statutory core duties include maintaining law and order and preserving national peace and security.</li>
              <li><strong>Crime Prevention and Detection:</strong> Prevent, detect, investigate, and suppress criminal activity through territorial police units and national investigative departments.</li>
              <li><strong>Criminal Investigation:</strong> Conduct and coordinate investigations into general crime, organized crime, serious offences, commercial crime, narcotics offences, and other criminal matters requiring specialist or national-level capability.</li>
              <li><strong>Arrest and Prosecution Support:</strong> Arrest persons lawfully subject to arrest, conduct investigations, and perform prosecution-related responsibilities authorized under Malaysian law.</li>
              <li><strong>Security Intelligence:</strong> Collect, evaluate, and process security intelligence to identify threats to public order, national security, and internal stability. The Special Branch provides PDRM's principal security-intelligence capability.</li>
              <li><strong>Internal Security and Public Order:</strong> Maintain national capability for public-order operations, major security incidents, high-risk operations, and other internal-security requirements through the Internal Security and Public Order Department and associated operational formations.</li>
              <li><strong>Traffic Enforcement and Road Safety:</strong> Enforce traffic laws, investigate road incidents, regulate traffic operations, and support road safety through the Traffic Investigation and Enforcement Department.</li>
              <li><strong>Crime Prevention and Community Policing:</strong> Conduct preventive policing, strengthen community participation, improve police accessibility, and support PDRM's concept of “total policing” involving communities and other stakeholders.</li>
            </ul>

            <h6 class="fw-bold mt-4">National Command and Control</h6>
            <ul>
              <li><strong>National Police Leadership:</strong> Establish national policing priorities, operational policies, security directives, and force-wide standards.</li>
              <li><strong>Territorial Police Supervision:</strong> Exercise national authority over the 14 State Police Contingents and the subordinate District Police Headquarters and Police Stations.</li>
              <li><strong>National Operational Coordination:</strong> Coordinate policing activities involving multiple states or police districts and deploy national resources where operational requirements exceed local capabilities.</li>
              <li><strong>Specialist Capability Coordination:</strong> Maintain national investigative, intelligence, public-order, narcotics, commercial-crime, logistics, integrity, community-security, and traffic capabilities through PDRM Headquarters departments.</li>
            </ul>

            <h6 class="fw-bold mt-4">National Security and Major Operations</h6>
            <ul>
              <li><strong>Security Threat Response:</strong> Detect, prevent, and respond to threats affecting national security, internal stability, or public order.</li>
              <li><strong>Major Incident Management:</strong> Coordinate police resources during major incidents, security emergencies, large public events, and operations requiring multi-contingent support.</li>
              <li><strong>Critical Infrastructure and Strategic Security:</strong> Support the protection of strategic locations, important government facilities, transportation infrastructure, and other locations requiring enhanced police security.</li>
            </ul>

            <h6 class="fw-bold mt-4">Public Services and Community Protection</h6>
            <ul class="mb-0">
              <li><strong>Public Assistance:</strong> Provide nationwide police services through territorial commands, Police Stations, and local policing facilities.</li>
              <li><strong>Emergency Police Response:</strong> Receive and respond to crime, public-safety incidents, emergencies, and requests for police assistance.</li>
              <li><strong>Community Safety:</strong> Build cooperation between police and communities to improve crime prevention, public confidence, information sharing, and local security awareness.</li>
            </ul>
          </div>

          <div class="tab-pane fade" id="police4-distribution" role="tabpanel" aria-labelledby="police4-distribution-tab" tabindex="0">
            <p>PDRM exercises policing responsibilities throughout Malaysia through a centralized national organization supported by 14 State Police Contingents (IPK). These Contingents supervise Police Districts and Police Stations responsible for territorial law enforcement and public-security operations.</p>
            <p class="mb-0">The territorial command system allows national PDRM policies and operational directions to be implemented through progressively localized police commands while maintaining centralized national authority.</p>
          </div>

          <div class="tab-pane fade" id="police4-equivalent" role="tabpanel" aria-labelledby="police4-equivalent-tab" tabindex="0">
            <ul>
              <li><strong>Federal Government:</strong> National civil authority responsible for governance and public administration.</li>
              <li><strong>Royal Malaysia Police Headquarters – Bukit Aman:</strong> National police command responsible for law enforcement, internal security, public order, crime prevention, and policing.</li>
              <li><strong>Malaysian Armed Forces Headquarters:</strong> National military command responsible for national defence and military operations.</li>
            </ul>
            <div class="alert border-0 border-start border-3 rounded-1 mb-0" role="note" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
              <strong>Note:</strong> These institutions operate under separate constitutional and statutory mandates. The comparison represents approximate national command levels rather than a direct equivalence of authority, rank, or function.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('service')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry,drawing"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// === Province dropdown (open/close, search, selected label) ===
document.addEventListener('click', (e) => {
    const provinceSelectInput = e.target.closest('#provinceSelect .select-input');
    const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');

    if (provinceSelectInput) {
        if (provinceDropdown) provinceDropdown.classList.toggle('show');
    } else {
        const provinceSelect = document.getElementById('provinceSelect');
        if (provinceSelect && !provinceSelect.contains(e.target) && provinceDropdown) {
            provinceDropdown.classList.remove('show');
        }
    }
}, true);

document.addEventListener('keyup', (e) => {
    if (e.target.id === 'provinceSearchInput') {
        const keyword = e.target.value.toLowerCase();
        document.querySelectorAll('#provinceList li').forEach(li => {
            const text = li.textContent.toLowerCase();
            li.style.display = text.includes(keyword) ? '' : 'none';
        });
    }
});

document.addEventListener('change', function (e) {
    if (e.target.classList && e.target.classList.contains('province-checkbox')) {
        const selected = [...document.querySelectorAll('.province-checkbox:checked')]
            .map(cb => cb.parentElement.textContent.trim());
        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            if (selected.length === 0) {
                provinceSearch.value = '';
                provinceSearch.placeholder = 'Select State';
            } else if (selected.length <= 2) {
                provinceSearch.value = selected.join(', ');
            } else {
                provinceSearch.value = selected.length + ' State Selected';
            }
        }
    }
});
</script>

<script>
    // --- Map Initialization (Google Maps) ---
    const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 4.079218905204628, lng: 108.47519471043994 },
        zoom: 6,
        mapTypeId: 'roadmap',
        mapTypeControl: true,
        fullscreenControl: true,
        streetViewControl: false
    });

    // --- Global States ---
    let airportMarkers = [];
    let hospitalMarkers = [];
    let policeMarkers = [];
    let embassyMarkers = [];
    const infoWindow = new google.maps.InfoWindow();
    let drawnPolygonGeoJSON = null;
    let radiusCircle = null;
    let radiusPinMarker = null;
    let lastClickedLocation = null;
    let totalHospitals = 0;
    let totalAirports = 0;
    let totalPolice = 0;
    let totalEmbassies = 0;

    const POLICE_DEFAULT_ICON = 'https://png.pngtree.com/png-vector/20221211/ourmid/pngtree-minimal-location-map-icon-logo-symbol-vector-design-transparent-background-png-image_6520892.png';

    // --- Directions (in-map routing) ---
    const directionsService  = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: false,
        polylineOptions: { strokeColor: '#1a73e8', strokeWeight: 5, strokeOpacity: 0.85 }
    });
    directionsRenderer.setMap(map);

    // "Clear Route" button
    const clearRouteBtn = document.createElement('div');
    clearRouteBtn.id = 'clearRouteBtn';
    clearRouteBtn.innerHTML = '✕ Clear Route';
    Object.assign(clearRouteBtn.style, {
        display: 'none',
        background: '#fff',
        border: '2px solid rgba(0,0,0,0.2)',
        borderRadius: '6px',
        padding: '6px 12px',
        fontSize: '13px',
        fontWeight: '600',
        cursor: 'pointer',
        margin: '10px',
        color: '#d32f2f',
        boxShadow: '0 2px 6px rgba(0,0,0,0.15)'
    });
    clearRouteBtn.title = 'Clear the current route';
    clearRouteBtn.addEventListener('click', () => {
        directionsRenderer.setDirections({ routes: [] });
        clearRouteBtn.style.display = 'none';
    });
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(clearRouteBtn);

    // --- Nearby Category Bar (Google Maps style) ---
    let categoryMarkers   = [];
    let activeCategoryBtn = null;

    const categoryBar = document.createElement('div');
    categoryBar.id = 'nearbyCategBar';
    Object.assign(categoryBar.style, {
        display:       'none',
        background:    'transparent',
        padding:       '8px 10px 0',
        gap:           '8px',
        flexWrap:      'nowrap',
        overflowX:     'auto',
        maxWidth:      '90vw',
        scrollbarWidth:'none'
    });

    const nearbyCategories = [
        { label: 'Hotels', icon: '🏨', type: 'lodging' }
    ];

    nearbyCategories.forEach(cat => {
        const btn = document.createElement('button');
        btn.textContent = cat.icon + ' ' + cat.label;
        Object.assign(btn.style, {
            display:      'inline-flex',
            alignItems:   'center',
            gap:          '4px',
            padding:      '6px 14px',
            borderRadius: '20px',
            border:       '1px solid rgba(0,0,0,0.12)',
            background:   '#fff',
            color:        '#222',
            fontSize:     '13px',
            fontWeight:   '500',
            cursor:       'pointer',
            whiteSpace:   'nowrap',
            boxShadow:    '0 1px 4px rgba(0,0,0,0.15)',
            transition:   'all 0.15s'
        });

        btn.addEventListener('click', () => {
            if (activeCategoryBtn === btn) {
                // toggle off
                clearCategoryMarkers();
                resetCategoryBtn(btn);
                activeCategoryBtn = null;
                return;
            }
            if (activeCategoryBtn) resetCategoryBtn(activeCategoryBtn);
            activeCategoryBtn = btn;
            btn.style.background  = '#1a73e8';
            btn.style.color       = '#fff';
            btn.style.borderColor = '#1a73e8';
            showNearbyCategory(cat.type, cat.label);
        });

        categoryBar.appendChild(btn);
    });

    map.controls[google.maps.ControlPosition.TOP_CENTER].push(categoryBar);

    function resetCategoryBtn(btn) {
        btn.style.background  = '#fff';
        btn.style.color       = '#222';
        btn.style.borderColor = 'rgba(0,0,0,0.12)';
    }

    function clearCategoryMarkers() {
        categoryMarkers.forEach(m => m.setMap(null));
        categoryMarkers = [];
    }

    function showNearbyCategory(type, label) {
        if (!lastClickedLocation) return;
        clearCategoryMarkers();

        const center  = new google.maps.LatLng(lastClickedLocation.lat, lastClickedLocation.lng);
        const service = new google.maps.places.PlacesService(map);

        // Color map per category
        const iconColors = {
            lodging:    '#1a73e8',
            restaurant: '#e53935',
            pharmacy:   '#2e7d32',
            atm:        '#f57c00',
            parking:    '#1565c0',
            cafe:       '#6d4c41',
            hospital:   '#c62828',
        };
        const color = iconColors[type] || '#555';

        function makeSvgIcon(col) {
            const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='32' height='40' viewBox='0 0 32 40'>`
                      + `<path d='M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24S32 28 32 16C32 7.16 24.84 0 16 0z' fill='${col}'/>`
                      + `<circle cx='16' cy='16' r='7' fill='#fff'/>`
                      + `</svg>`;
            return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
        }

        const searchRadiusM  = 20000; // 20 km
        const searchRadiusKm = searchRadiusM / 1000;

        service.nearbySearch({ location: center, radius: searchRadiusM, type }, (results, status) => {
            if (status !== google.maps.places.PlacesServiceStatus.OK) {
                if (status === 'ZERO_RESULTS') {
                    alert(`No ${label.toLowerCase()} found within ${searchRadiusKm} km.`);
                } else {
                    alert(`Failed to load ${label.toLowerCase()}. Error status: ${status}. Please ensure "Places API" is enabled and billing is active.`);
                    console.error('PlacesService nearbySearch failed with status:', status);
                }
                return;
            }
            if (!results.length) return;

            results.forEach(place => {
                if (!place.geometry?.location) return;

                const marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map,
                    title: place.name,
                    icon: { url: makeSvgIcon(color), scaledSize: new google.maps.Size(32, 40) },
                    animation: google.maps.Animation.DROP
                });

                const dist     = google.maps.geometry.spherical.computeDistanceBetween(center, place.geometry.location);
                const distText = dist >= 1000 ? (dist / 1000).toFixed(1) + ' km' : Math.round(dist) + ' m';
                const rating   = place.rating ? `⭐ ${place.rating.toFixed(1)}` : '';
                const destLat  = place.geometry.location.lat();
                const destLng  = place.geometry.location.lng();
                const safeName = (place.name || '').replace(/'/g, "\\'");

                marker.addListener('click', () => {
                    infoWindow.setContent(`
                        <div style="font-size:13px;min-width:190px;">
                            <h5 style="border-bottom:1px solid #ccc;margin:0 0 6px;font-size:14px;">${place.name}</h5>
                            <div style="color:#666;font-size:12px;margin-bottom:3px;">${label}</div>
                            ${rating  ? `<div style="font-size:12px;">${rating}</div>` : ''}
                            <div style="margin-top:4px;font-size:12px;color:#555;"> ${distText} from search location</div>
                            <div style="margin-top:8px;">
                                <button onclick="showRouteOnMap(${center.lat()},${center.lng()},${destLat},${destLng},'${safeName}')"
                                        style="display:inline-flex;align-items:center;gap:5px;
                                               background:#1a73e8;color:#fff;border:none;
                                               padding:5px 12px;border-radius:6px;font-size:12px;
                                               font-weight:500;cursor:pointer;">
                                    <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                        <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                                    </svg>
                                    Get Directions
                                </button>
                            </div>
                        </div>`);
                    infoWindow.open(map, marker);
                });

                categoryMarkers.push(marker);
            });
        });
    }

    // Helper: close route panel
    function closeRoutePanel() {
        const panel = document.getElementById('routePanel');
        if (panel) panel.style.display = 'none';
        directionsRenderer.setDirections({ routes: [] });
        clearRouteBtn.style.display = 'none';
    }

    // Helper: draw route on map + show panel
    function showRouteOnMap(originLat, originLng, destLat, destLng, destName) {
        directionsService.route({
            origin: new google.maps.LatLng(originLat, originLng),
            destination: new google.maps.LatLng(destLat, destLng),
            travelMode: google.maps.TravelMode.DRIVING
        }, (result, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
                clearRouteBtn.style.display = 'inline-block';
                infoWindow.close();

                // --- Populate Route Panel ---
                const leg = result.routes[0].legs[0];
                const panel = document.getElementById('routePanel');
                document.getElementById('routePanelTitle').textContent = destName || 'Destination';
                document.getElementById('routeDistance').textContent  = leg.distance.text;
                document.getElementById('routeDuration').textContent  = leg.duration.text;

                const stepsEl = document.getElementById('routeSteps');
                stepsEl.innerHTML = leg.steps.map((step, i) => {
                    const raw = (step.html_instructions || step.instructions || '');
                    const instruction = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                    if (!instruction) return ''; // skip steps with no text
                    const icons = {
                        'Turn left':        '↰',
                        'Turn right':       '↱',
                        'Keep left':        '↖',
                        'Keep right':       '↗',
                        'Continue':         '↑',
                        'Head':             '↑',
                        'Roundabout':       '↻',
                        'U-turn':           '⟳',
                        'Merge':            '↑',
                        'Ramp':             '↗',
                        'Destination':      '📍',
                    };
                    let icon = '•';
                    for (const [key, val] of Object.entries(icons)) {
                        if (instruction.startsWith(key)) { icon = val; break; }
                    }
                    const isLast = i === leg.steps.length - 1;
                    return `
                        <div style="display:flex;gap:10px;padding:8px 14px;
                                    border-bottom:${isLast ? 'none' : '1px solid #f0f0f0'};
                                    align-items:flex-start;">
                            <div style="min-width:22px;height:22px;background:${isLast ? '#395272' : '#e8f0fe'};
                                        border-radius:50%;display:flex;align-items:center;
                                        justify-content:center;font-size:12px;
                                        color:${isLast ? '#fff' : '#1a73e8'};flex-shrink:0;margin-top:1px;">
                                ${icon}
                            </div>
                            <div style="flex:1;">
                                <div style="font-size:12px;color:#222;line-height:1.4;">${instruction}</div>
                                <div style="font-size:11px;color:#888;margin-top:2px;">${step.distance.text}</div>
                            </div>
                        </div>`;
                }).join('');

                panel.style.display = 'flex';
            } else {
                if (status === 'ZERO_RESULTS') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Route Not Found',
                        text: 'No driving route could be found between your location and the destination. The two locations may not be connected by road.',
                        confirmButtonColor: '#1a73e8',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Directions Error',
                        text: 'Could not get directions: ' + status,
                        confirmButtonColor: '#1a73e8',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }

    // --- Polygon Draw (Custom Point-by-Point) ---
    let isDrawingPolygon = false;
    let polygonLatLngs = [];
    let activePolygon = null;
    let activePolyline = null;
    let cursorPolyline = null;
    let startMarker = null;

    const drawButton = document.createElement('div');
    drawButton.innerHTML = '⬟';
    drawButton.style.backgroundColor = 'white';
    drawButton.style.border = '2px solid rgba(0,0,0,0.2)';
    drawButton.style.borderRadius = '4px';
    drawButton.style.width = '34px';
    drawButton.style.height = '34px';
    drawButton.style.textAlign = 'center';
    drawButton.style.lineHeight = '30px';
    drawButton.style.fontSize = '18px';
    drawButton.style.cursor = 'pointer';
    drawButton.style.margin = '10px';
    drawButton.title = 'Draw Polygon (Click point by point, click starting point to finish)';

    map.controls[google.maps.ControlPosition.LEFT_TOP].push(drawButton);

    const clearButton = document.createElement('div');
    clearButton.innerHTML = '🗑️';
    clearButton.style.backgroundColor = 'white';
    clearButton.style.border = '2px solid rgba(0,0,0,0.2)';
    clearButton.style.borderRadius = '4px';
    clearButton.style.width = '34px';
    clearButton.style.height = '34px';
    clearButton.style.textAlign = 'center';
    clearButton.style.lineHeight = '30px';
    clearButton.style.fontSize = '16px';
    clearButton.style.cursor = 'pointer';
    clearButton.style.margin = '10px 0';
    clearButton.title = 'Clear Polygon';

    map.controls[google.maps.ControlPosition.LEFT_TOP].push(clearButton);

    drawButton.addEventListener('click', () => {
        isDrawingPolygon = !isDrawingPolygon;
        if (isDrawingPolygon) {
            map.setOptions({ draggable: false });
            drawButton.style.backgroundColor = '#ccc';
            map.getDiv().style.cursor = 'crosshair';
            polygonLatLngs = [];
            if (activePolygon) activePolygon.setMap(null);
            if (activePolyline) activePolyline.setMap(null);
            if (cursorPolyline) cursorPolyline.setMap(null);
            if (startMarker) startMarker.setMap(null);
            activePolygon = null;
            activePolyline = new google.maps.Polyline({
                path: polygonLatLngs,
                strokeColor: '#0000FF',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                clickable: false,
                map: map
            });
            cursorPolyline = new google.maps.Polyline({
                path: [],
                strokeColor: '#0000FF',
                strokeOpacity: 0.5,
                strokeWeight: 3,
                clickable: false,
                map: map
            });
            startMarker = null;
            drawnPolygonGeoJSON = null;
        } else {
            finishPolygon();
        }
    });

    map.addListener('click', (e) => {
        if (!isDrawingPolygon) return;
        polygonLatLngs.push(e.latLng);
        activePolyline.setPath(polygonLatLngs);

        if (polygonLatLngs.length === 1) {
            startMarker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 6,
                    fillColor: '#FFFFFF',
                    fillOpacity: 1,
                    strokeColor: '#0000FF',
                    strokeWeight: 2,
                },
                zIndex: 999
            });
            startMarker.addListener('click', () => {
                if (isDrawingPolygon) finishPolygon();
            });
        }
    });

    map.addListener('mousemove', (e) => {
        if (!isDrawingPolygon || polygonLatLngs.length === 0) return;
        const lastPoint = polygonLatLngs[polygonLatLngs.length - 1];
        cursorPolyline.setPath([lastPoint, e.latLng]);
    });

    map.addListener('rightclick', () => {
        if (isDrawingPolygon) finishPolygon();
    });

    async function finishPolygon() {
        if (!isDrawingPolygon) return;
        isDrawingPolygon = false;
        map.setOptions({ draggable: true });
        drawButton.style.backgroundColor = 'white';
        map.getDiv().style.cursor = '';
        if (cursorPolyline) cursorPolyline.setMap(null);
        if (startMarker) startMarker.setMap(null);

        if (polygonLatLngs.length > 2) {
            if (activePolyline) activePolyline.setMap(null);
            activePolygon = new google.maps.Polygon({
                paths: polygonLatLngs,
                strokeColor: '#0000FF',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillColor: '#0000FF',
                fillOpacity: 0.2,
                editable: true,
                map: map
            });

            const coordinates = polygonLatLngs.map(p => [p.lng(), p.lat()]);
            coordinates.push([polygonLatLngs[0].lng(), polygonLatLngs[0].lat()]); // Close polygon

            drawnPolygonGeoJSON = {
                type: "Feature",
                geometry: { type: "Polygon", coordinates: [coordinates] },
                properties: {}
            };

            const updatePolygonFilter = async () => {
                if (!activePolygon) return;
                const path = activePolygon.getPath();
                if (path.getLength() > 2) {
                    const newCoords = [];
                    for (let i = 0; i < path.getLength(); i++) {
                        const xy = path.getAt(i);
                        newCoords.push([xy.lng(), xy.lat()]);
                    }
                    newCoords.push([path.getAt(0).lng(), path.getAt(0).lat()]);
                    drawnPolygonGeoJSON.geometry.coordinates = [newCoords];
                    await refreshCurrentFilters();
                }
            };

            google.maps.event.addListener(activePolygon.getPath(), 'set_at', updatePolygonFilter);
            google.maps.event.addListener(activePolygon.getPath(), 'insert_at', updatePolygonFilter);
            google.maps.event.addListener(activePolygon.getPath(), 'remove_at', updatePolygonFilter);

            await refreshCurrentFilters();
        } else {
            if (activePolyline) activePolyline.setMap(null);
            activePolyline = null;
            activePolygon = null;
            drawnPolygonGeoJSON = null;
        }
    }

    clearButton.addEventListener('click', async () => {
        if (activePolygon) activePolygon.setMap(null);
        if (activePolyline) activePolyline.setMap(null);
        if (cursorPolyline) cursorPolyline.setMap(null);
        if (startMarker) startMarker.setMap(null);
        activePolygon = null;
        activePolyline = null;
        cursorPolyline = null;
        startMarker = null;
        polygonLatLngs = [];
        drawnPolygonGeoJSON = null;
        isDrawingPolygon = false;
        map.setOptions({ draggable: true });
        drawButton.style.backgroundColor = 'white';
        map.getDiv().style.cursor = '';
        await refreshCurrentFilters();
    });

    // --- Update Radius ---
    function updateRadiusCircleAndPin(radius = 0) {
        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }

        if (radius > 0 && lastClickedLocation) {
            radiusCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.2,
                map: map,
                center: lastClickedLocation,
                radius: radius * 1000
            });
        }
    }

    // Red pin marker for searched location (separate from radius circle)
    function placeLocationPin(location, label) {
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        radiusPinMarker = new google.maps.Marker({
            position: location,
            map: map,
            title: label || 'Selected Location',
            icon: {
                url: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                scaledSize: new google.maps.Size(25, 41)
            },
            zIndex: 9999,
            animation: google.maps.Animation.DROP
        });
    }

    // Enable/disable radius section based on whether location is set
    function setRadiusSectionEnabled(enabled) {
        const section = document.getElementById('radiusSection');
        if (!section) return;
        section.style.opacity = enabled ? '1' : '0.4';
        section.style.pointerEvents = enabled ? 'auto' : 'none';
    }

    // --- Init Location Search — Google Places Autocomplete ---
    // .pac-container is repositioned to position:fixed via MutationObserver
    // to bypass Google Maps container overflow:hidden clipping.
    function initLocationSearch() {
        const input = document.getElementById('locationSearchMap');
        if (!input) {
            setTimeout(initLocationSearch, 300);
            return;
        }

        const clearBtn = document.getElementById('locationSearchClear');

        // ── 1. Create Google Places Autocomplete ──────────────────────────────
        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode', 'establishment'],
            fields: ['geometry', 'name', 'formatted_address']
        });

        // ── 2. Fix .pac-container position to avoid map overflow:hidden ───────
        // Google appends .pac-container to <body> but uses position:absolute,
        // calculated from the element's document offset. Because the map container
        // applies its own offset context, the top/left values are wrong.
        // We override with position:fixed + getBoundingClientRect().
        let pacContainer = null;

        function fixPacPosition() {
            if (!pacContainer) return;
            const rect = input.getBoundingClientRect();
            pacContainer.style.position     = 'fixed';
            pacContainer.style.zIndex       = '2147483647';
            pacContainer.style.top          = (rect.bottom + 2) + 'px';
            pacContainer.style.left         = rect.left + 'px';
            pacContainer.style.width        = rect.width + 'px';
            pacContainer.style.borderRadius = '0 0 8px 8px';
            pacContainer.style.boxShadow    = '0 8px 24px rgba(0,0,0,0.2)';
            pacContainer.style.fontFamily   = 'inherit';
        }

        // Watch for Google to inject .pac-container into <body>
        const observer = new MutationObserver(() => {
            if (!pacContainer) {
                pacContainer = document.querySelector('.pac-container');
                if (pacContainer) {
                    fixPacPosition();
                    // Re-fix on every style mutation (Google repositions it on scroll etc.)
                    new MutationObserver(fixPacPosition).observe(
                        pacContainer, { attributes: true, attributeFilter: ['style'] }
                    );
                }
            }
        });
        observer.observe(document.body, { childList: true, subtree: false });

        // Keep in sync with input position on scroll / resize
        window.addEventListener('scroll', fixPacPosition, true);
        window.addEventListener('resize', fixPacPosition);
        input.addEventListener('focus',  fixPacPosition);
        input.addEventListener('input',  fixPacPosition);

        // ── 3. Prevent map from capturing keyboard input ───────────────────────
        google.maps.event.addDomListener(input, 'keydown',   e => e.stopPropagation());
        google.maps.event.addDomListener(input, 'mousedown', e => e.stopPropagation());

        // ── 4. Focus styling ───────────────────────────────────────────────────
        input.addEventListener('focus', () => {
            input.style.borderColor = '#1a73e8';
            input.style.boxShadow   = '0 0 0 3px rgba(26,115,232,0.15)';
        });
        input.addEventListener('blur', () => {
            input.style.borderColor = '#ddd';
            input.style.boxShadow   = 'none';
        });

        // Show/hide × button
        input.addEventListener('input', () => {
            if (clearBtn) clearBtn.style.display = input.value.length ? 'inline' : 'none';
        });

        // ── 5. Handle place selection ─────────────────────────────────────────
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            const loc = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            };
            lastClickedLocation = loc;

            map.panTo(loc);
            map.setZoom(12);

            const label = place.name || place.formatted_address || 'Location';
            placeLocationPin(loc, label);

            if (clearBtn) clearBtn.style.display = 'inline';

            const badge     = document.getElementById('locationFoundBadge');
            const badgeName = document.getElementById('locationFoundName');
            if (badge)     badge.style.display = 'block';
            if (badgeName) badgeName.textContent = label;

            setRadiusSectionEnabled(true);
            const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
            updateRadiusCircleAndPin(radius);
            refreshCurrentFilters();

            // Show category bar (Nearby Hotels)
            categoryBar.style.display = 'flex';
        });

        // ── 6. Clear button ───────────────────────────────────────────────────
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                input.value = '';
                clearBtn.style.display = 'none';
                if (pacContainer) pacContainer.style.display = 'none';

                const badge = document.getElementById('locationFoundBadge');
                if (badge) badge.style.display = 'none';

                if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
                if (radiusCircle)    { radiusCircle.setMap(null);    radiusCircle    = null; }
                lastClickedLocation = null;

                // Hide category bar & clear category markers
                categoryBar.style.display = 'none';
                clearCategoryMarkers();
                if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

                setRadiusSectionEnabled(false);
                const rEl    = document.getElementById('radiusRangeMap');
                const rValEl = document.getElementById('radiusValueMap');
                if (rEl)    rEl.value          = 0;
                if (rValEl) rValEl.textContent = '0';

                refreshCurrentFilters();
                input.focus();
            });
        }
    }

    // --- Fetch Data ---
    async function fetchData(url, filters = {}) {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([k, v]) => {
            if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
            else if (v !== '' && v != null) params.append(k, v);
        });
        if (drawnPolygonGeoJSON) params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));

        try {
            const res = await fetch(`${url}?${params.toString()}`);
            return res.ok ? await res.json() : [];
        } catch (e) {
            console.error(`Error fetching ${url}:`, e);
            return [];
        }
    }

    // --- Add Markers ---
    function clearMarkers(markersArray) {
        if (!markersArray) return;
        markersArray.forEach(m => m.setMap(null));
        markersArray.length = 0;
    }

    function addMarkers(data, markersArray, defaultIconUrl) {
        clearMarkers(markersArray);
        (data || []).forEach(item => {
            if (!item || !item.latitude || !item.longitude) return;

            // Police icon is rendered smaller than airfield / medical pins
            const iconSize = item.name_police
                ? new google.maps.Size(12, 12)
                : new google.maps.Size(24, 24);

            const iconUrl = item.icon || defaultIconUrl || 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png';

            const marker = new google.maps.Marker({
                position: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) },
                map: map,
                icon: {
                    url: iconUrl,
                    scaledSize: iconSize
                }
            });

            let itemName = '', detailUrl = '', popupContent = '';

            if (item.airport_name) {
                itemName = item.airport_name;
                detailUrl = `/airports/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Classification:</strong> ${item.category || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city_name ? ', ' + item.city_name : ''}
                        ${item.province_name ? ', ' + item.province_name : ''}, Malaysia <br>
                    <strong>Website:</strong> ${item.website || 'N/A'} <br>
                `;
            } else if (item.name) {
                itemName = item.name;
                detailUrl = `/hospitals/${item.id}`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Global Classification:</strong> ${item.facility_category || 'N/A'}<br>
                    <strong>Country Classification:</strong> ${item.facility_level || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}, Malaysia <br>
                `;
            } else if (item.name_police) {
                itemName = item.name_police;
                detailUrl = `/police/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Classification:</strong> ${item.category || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}, Malaysia <br>
                    <strong>Phone:</strong> ${item.telephone || 'N/A'}<br>
                    <strong>Fax:</strong> ${item.fax || 'N/A'}<br>
                    <strong>Email:</strong> ${item.email || 'N/A'}<br>
                    <strong>Website:</strong> ${item.website || 'N/A'}<br>
                `;
            } else if (item.name_embassiees) {
                itemName = item.name_embassiees;
                detailUrl = `/embassiees/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;">${itemName}</h5>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}, Malaysia <br>
                    <strong>Phone:</strong> ${item.telephone || 'N/A'}<br>
                    <strong>Fax:</strong> ${item.fax || 'N/A'}<br>
                    <strong>Email:</strong> ${item.email || 'N/A'}<br>
                    <strong>Website:</strong> ${item.website || 'N/A'}<br>
                `;
            }

            marker.addListener('click', () => {
                const destLat = parseFloat(item.latitude);
                const destLng = parseFloat(item.longitude);

                let actionButtons = '';
                if (lastClickedLocation && !isNaN(destLat) && !isNaN(destLng)) {
                    const oLat = lastClickedLocation.lat;
                    const oLng = lastClickedLocation.lng;
                    actionButtons = `
                        <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;display:flex;gap:6px;flex-wrap:wrap;">
                            <button onclick="showRouteOnMap(${oLat},${oLng},${destLat},${destLng},'${(itemName || '').replace(/'/g, "\\'")}')"
                               style="display:inline-flex;align-items:center;gap:5px;
                                      background:#1a73e8;color:#fff;border:none;
                                      padding:5px 12px;border-radius:6px;font-size:12px;
                                      font-weight:500;cursor:pointer;">
                                <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                    <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                                </svg>
                                Get Directions
                            </button>
                            ${detailUrl ? `<a href="${detailUrl}"
                               style="display:inline-flex;align-items:center;gap:5px;
                                      background:#395272;color:#fff;text-decoration:none;
                                      padding:5px 12px;border-radius:6px;font-size:12px;
                                      font-weight:500;"
                               onmouseover="this.style.background='#5686c3'"
                               onmouseout="this.style.background='#395272'">
                                <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                    <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                                </svg>
                                Read More
                            </a>` : ''}
                        </div>`;
                } else if (detailUrl) {
                    actionButtons = `
                        <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;">
                            <a href="${detailUrl}"
                               style="display:inline-flex;align-items:center;gap:5px;
                                      background:#395272;color:#fff;text-decoration:none;
                                      padding:5px 12px;border-radius:6px;font-size:12px;
                                      font-weight:500;"
                               onmouseover="this.style.background='#5686c3'"
                               onmouseout="this.style.background='#395272'">
                                <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                    <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                                </svg>
                                Read More
                            </a>
                        </div>`;
                }

                infoWindow.setContent(`<div style="font-size:13px;min-width:200px;">${popupContent}${actionButtons}</div>`);
                infoWindow.open(map, marker);
            });

            markersArray.push(marker);
        });
    }

    // --- Apply Filters ---
    async function applyFiltersWithMapControl(
        facilities = [],
        hospitalLevels = [],
        airportClasses = [],
        provinces = [],
        radius = 0,
        airportName = '',
        hospitalName = ''
    ) {
        let common = { provinces };
        if (radius > 0 && lastClickedLocation) {
            common.radius = radius;
            common.center_lat = lastClickedLocation.lat;
            common.center_lng = lastClickedLocation.lng;
        }

        totalHospitals = 0;
        totalAirports = 0;
        totalPolice = 0;
        totalEmbassies = 0;

        // hanya facility yang dicentang yang ditampilkan
        // (checkbox "All" mencentang semuanya sekaligus)
        const showHospital = facilities.includes('hospital');
        const showAirport  = facilities.includes('airport');
        const showPolice   = facilities.includes('police');
        const showEmbassy  = facilities.includes('embassy');

        // === HOSPITALS ===
        if (showHospital) {
            const hospitals = await fetchData('/api/hospital', {
                ...common,
                name: hospitalName,
                category: hospitalLevels
            });
            addMarkers(hospitals, hospitalMarkers, null);
            totalHospitals = hospitals.length;
        } else {
            clearMarkers(hospitalMarkers);
        }

        // === AIRPORTS ===
        if (showAirport) {
            const airports = await fetchData('/api/airports', {
                ...common,
                name: airportName
            });

            const filteredAirports = airports.filter(a => {
                if (airportClasses.length === 0) return true;
                if (!a.category) return false;
                const dbCategories = a.category.split(',').map(c => c.trim().toLowerCase());
                return airportClasses.some(sel => dbCategories.includes(sel.toLowerCase()));
            });

            addMarkers(
                filteredAirports,
                airportMarkers,
                'https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png'
            );
            totalAirports = filteredAirports.length;
        } else {
            clearMarkers(airportMarkers);
        }

        // === POLICE ===
        if (showPolice) {
            const result = await fetchData('/api/polices', { ...common });

            const police = result.polices || [];
            const categoryCounts = result.categoryCounts || {};

            addMarkers(police, policeMarkers, POLICE_DEFAULT_ICON);
            totalPolice = police.length;

            Object.keys(categoryCounts).forEach(cat => {
                const id = cat.replace(/[^a-zA-Z0-9]/g, '-');
                const el = document.getElementById(`count-${id}`);
                if (el) el.textContent = categoryCounts[cat];
            });
        } else {
            clearMarkers(policeMarkers);
        }

        // === EMBASSY ===
        if (showEmbassy) {
            const embassies = await fetchData('/api/embassy', { ...common });

            addMarkers(embassies, embassyMarkers, '/images/embassy-icon-new.png');
            totalEmbassies = embassies.length;
        } else {
            clearMarkers(embassyMarkers);
        }

        updateRadiusCircleAndPin(radius);
        updateTotalCountDisplay();
    }

    function updateTotalCountDisplay() {
        // Panel filter di-attach oleh Google Maps secara async,
        // jadi elemen counter bisa belum ada saat load pertama.
        const setCount = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        };

        setCount('airportCount', totalAirports);
        setCount('hospitalCount', totalHospitals);
        setCount('policeCount', totalPolice);
        setCount('embassyCount', totalEmbassies);
    }

    // === COMBINED PANEL ===
    const combinedPanelDiv = document.createElement('div');
    combinedPanelDiv.id = 'combinedPanelDiv';
    Object.assign(combinedPanelDiv.style, {
        background: 'white',
        borderRadius: '8px',
        boxShadow: '0 2px 6px rgba(0,0,0,0.2)',
        minWidth: '260px',
        maxWidth: '290px',
        overflow: 'visible',
        margin: '10px'
    });

    combinedPanelDiv.innerHTML = `
        <button style="background:#007bff;color:white;border:none;width:100%;padding:8px;border-radius:8px 8px 0 0;font-weight:600;letter-spacing:0.3px;">Filter &amp; Radius</button>

        <!-- Search Location - NOT inside scrollable div so dropdown is never clipped -->
        <div id="searchSection" style="padding:10px 10px 6px 10px;background:white;position:relative;">
            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;"> Search Location</strong>
            <div style="position:relative;margin-top:5px;">
                <input
                    type="text"
                    id="locationSearchMap"
                    placeholder="Search Location..."
                    autocomplete="off"
                    style="width:100%;padding:7px 30px 7px 9px;border:1.5px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;"
                >
                <span id="locationSearchClear" title="Clear"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:15px;color:#aaa;display:none;">&times;</span>
            </div>
            <div id="locationFoundBadge" style="display:none;margin-top:6px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:5px;padding:4px 8px;font-size:12px;color:#2e7d32;">
                &#128204; <span id="locationFoundName"></span>
            </div>
        </div>

        <!-- Radius - also outside scrollable, enabled after location selected -->
        <div id="radiusSection" style="padding:0 10px 0 10px;opacity:0.4;pointer-events:none;transition:opacity 0.3s;">
            <hr style="margin:8px 0;">
            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">&#11096; Radius: <span id="radiusValueMap">0</span> km</strong>
            <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin:4px 0;">
            <div style="display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:5px;">
                <span>0</span><span>250 km</span><span>500 km</span>
            </div>
            <div style="display:flex;gap:5px;margin-bottom:6px;">
                <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
                <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
            </div>
        </div>

        <!-- Scrollable filters below -->
        <div id="filterPanel" style="padding:0 10px 10px 10px;max-height:52vh;overflow-y:auto;border-top:1px solid #eee;">
            <div style="padding-top:8px;">
            <span class="facility-title">Facilities</span>

                    <div class="facility-list">
                        <label class="facility-item">
                            <input class="facility-checkbox" type="checkbox" value="airport" id="facilityAirport" checked>
                            <span class="facility-name">Aviation</span>
                            <span class="facility-count" id="airportCount">0</span>
                        </label>

                        <label class="facility-item">
                            <input class="facility-checkbox" type="checkbox" value="hospital" id="facilityHospital">
                            <span class="facility-name">Medical</span>
                            <span class="facility-count" id="hospitalCount">0</span>
                        </label>

                        <label class="facility-item">
                            <input class="facility-checkbox" type="checkbox" value="police" id="facilityPolice">
                            <span class="facility-name">Police</span>
                            <span class="facility-count" id="policeCount">0</span>
                        </label>

                        <label class="facility-item">
                            <input class="facility-checkbox" type="checkbox" value="embassy" id="facilityEmbassy">
                            <span class="facility-name">Embassies</span>
                            <span class="facility-count" id="embassyCount">0</span>
                        </label>

                        <label class="facility-item facility-item-all">
                            <input type="checkbox" value="all" id="facilityAll">
                            <span class="facility-name">All</span>
                        </label>

                    </div>

                    <hr>
                    <div class="filter-box" id="provinceSelect">
                        <label class="filter-label">
                            State
                        </label>

                        <div class="select-input">
                            <input
                                type="text"
                                id="provinceSearch"
                                placeholder="Select State"
                                readonly
                            >
                            <i class="bi bi-chevron-down"></i>
                        </div>

                        <div class="select-dropdown">
                            <input
                                type="text"
                                class="dropdown-search"
                                id="provinceSearchInput"
                                placeholder="Search State..."
                            >

                            <ul id="provinceList">
                                @foreach($provinces as $province)
                                <li>
                                    <label>
                                        <input
                                            type="checkbox"
                                            class="province-checkbox"
                                            value="{{ $province->id }}"
                                        >
                                        {{ $province->provinces_region }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <hr>
                    <button id="resetMapFilter"
                            class="btn btn-sm btn-secondary w-100"
                            style="margin-top:auto;">
                        Reset All
                    </button>
                    <div id="totalCountDisplay" style="margin-top:8px;text-align:center;font-size:13px;"></div>
                </div>
            </div>`;

    google.maps.event.addDomListener(combinedPanelDiv, 'click', e => e.stopPropagation());
    google.maps.event.addDomListener(combinedPanelDiv, 'dblclick', e => e.stopPropagation());
    google.maps.event.addDomListener(combinedPanelDiv, 'mousedown', e => e.stopPropagation());
    google.maps.event.addDomListener(combinedPanelDiv, 'touchstart', e => e.stopPropagation());
    google.maps.event.addDomListener(combinedPanelDiv, 'wheel', e => e.stopPropagation());
    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(combinedPanelDiv);

    // === FACILITIES "ALL" CHECKBOX SYNC ===
    function syncFacilityAllCheckbox() {
        const all = document.getElementById('facilityAll');
        if (!all) return;
        const boxes = [...document.querySelectorAll('.facility-checkbox')];
        all.checked = boxes.length > 0 && boxes.every(cb => cb.checked);
    }

    function getCurrentFiltersFromUI() {
        const facilities = [...document.querySelectorAll('.facility-checkbox:checked')].map(el => el.value);
        const hLevels = [...document.querySelectorAll('input[name="hospitalLevel"]:checked')].map(e => e.value);
        const aClasses = [...document.querySelectorAll('input[name="airportClass"]:checked')].map(e => e.value);
        const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
        const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
        return { facilities, hLevels, aClasses, provs, radius, airportName: '', hospitalName: '' };
    }

    async function refreshCurrentFilters() {
        const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
        await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
    }

    // === Event Logic ===
    // Capture phase supaya tidak diblok oleh stopPropagation pada map control.
    document.addEventListener('change', async e => {
        if (!e.target) return;

        // "All" mencentang / melepas semua facility
        if (e.target.id === 'facilityAll') {
            document.querySelectorAll('.facility-checkbox').forEach(cb => {
                cb.checked = e.target.checked;
            });
            await refreshCurrentFilters();
            return;
        }

        if (e.target.classList && e.target.classList.contains('facility-checkbox')) {
            syncFacilityAllCheckbox();
        }

        if (!combinedPanelDiv.contains(e.target)) return;

        await refreshCurrentFilters();
    }, true);

    // === INPUT: update tampilan radius saat slider digeser (live) ===
    document.addEventListener('input', (e) => {
        if (e.target && e.target.id === 'radiusRangeMap') {
            const r = parseInt(e.target.value || 0);
            const el = document.getElementById('radiusValueMap');
            if (el) el.textContent = r;
            // hanya update tampilan lingkaran saja (belum apply ke filter)
            updateRadiusCircleAndPin(r);
        }
    }, true);

    // === CLICK: apply / reset radius dan reset all ===
    document.addEventListener('click', async (e) => {
        if (!e.target) return;

        // APPLY RADIUS => ambil filter sekarang lalu panggil applyFiltersWithMapControl dengan radius
        if (e.target.id === 'applyRadiusMap') {
            const { radius } = getCurrentFiltersFromUI();
            if (radius > 0 && !lastClickedLocation) {
                alert('Cari lokasi terlebih dahulu menggunakan kolom "Search Location" sebelum menggunakan filter radius.');
                return;
            }
            await refreshCurrentFilters();
            return;
        }

        // RESET RADIUS (hanya reset radius visual & reapply tanpa radius)
        if (e.target.id === 'resetRadiusMap') {
            const rEl = document.getElementById('radiusRangeMap');
            const rValEl = document.getElementById('radiusValueMap');
            if (rEl) rEl.value = 0;
            if (rValEl) rValEl.textContent = '0';

            if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }

            await refreshCurrentFilters();
            return;
        }

        // RESET ALL FILTERS (tombol Reset All)
        if (e.target.id === 'resetMapFilter') {
            // 1) UI reset (default: hanya Aviation yang aktif)
            document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => { cb.checked = false; });
            const defaultFacility = document.getElementById('facilityAirport');
            if (defaultFacility) defaultFacility.checked = true;
            syncFacilityAllCheckbox();

            // 2) Reset dropdown District
            const provinceSearch = document.getElementById('provinceSearch');
            if (provinceSearch) provinceSearch.value = '';
            const provinceSearchInput = document.getElementById('provinceSearchInput');
            if (provinceSearchInput) provinceSearchInput.value = '';
            document.querySelectorAll('#provinceList li').forEach(li => { li.style.display = ''; });

            // 3) Reset radius visual & location search
            const radiusRange = document.getElementById('radiusRangeMap');
            const radiusValue = document.getElementById('radiusValueMap');
            if (radiusRange) radiusRange.value = 0;
            if (radiusValue) radiusValue.textContent = '0';
            if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }
            if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
            lastClickedLocation = null;

            const locInput = document.getElementById('locationSearchMap');
            const locClear = document.getElementById('locationSearchClear');
            const locBadge = document.getElementById('locationFoundBadge');
            if (locInput) locInput.value = '';
            if (locClear) locClear.style.display = 'none';
            if (locBadge) locBadge.style.display = 'none';
            setRadiusSectionEnabled(false);

            // 4) Reset nearby category & route
            categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }
            closeRoutePanel();

            // 5) Remove drawn polygon and layers
            if (activePolygon) activePolygon.setMap(null);
            if (activePolyline) activePolyline.setMap(null);
            if (cursorPolyline) cursorPolyline.setMap(null);
            if (startMarker) startMarker.setMap(null);
            activePolygon = null;
            activePolyline = null;
            cursorPolyline = null;
            startMarker = null;
            polygonLatLngs = [];
            drawnPolygonGeoJSON = null;

            // 6) Clear markers and counters
            clearMarkers(airportMarkers);
            clearMarkers(hospitalMarkers);
            clearMarkers(policeMarkers);
            clearMarkers(embassyMarkers);
            totalAirports = 0;
            totalHospitals = 0;
            totalPolice = 0;
            totalEmbassies = 0;
            updateTotalCountDisplay();

            // 7) Re-fetch data sesuai default (Aviation)
            await applyFiltersWithMapControl(['airport'], [], [], [], 0, '', '');

            e.stopPropagation();
            e.preventDefault();
            return;
        }
    }, true);

    setTimeout(initLocationSearch, 350);

    // --- Initial Load ---
    // Tunggu sampai panel filter benar-benar ter-attach ke DOM oleh Google Maps,
    // supaya default checkbox terbaca oleh getCurrentFiltersFromUI().
    (function initialLoad() {
        if (!document.getElementById('facilityAirport')) {
            setTimeout(initialLoad, 100);
            return;
        }
        refreshCurrentFilters();
    })();
</script>

@endpush
