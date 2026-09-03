@extends('layouts.master')

@section('title','Emergency Support')
@section('page-title', 'Malaysia Medical Facility')

@push('styles')

<style>
    #map {
        height: 600px;
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

    .p-1{
        padding: 0 3px !important;
        margin: 0 3px;
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

    .leaflet-routing-container-hide .leaflet-routing-collapse-btn
    {
        left: 8px;
        top: 8px;
    }

    .leaflet-control-container .leaflet-routing-container-hide {
        width: 48px;
        height: 48px;
    }

    /* Classification */
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

    /* ===== Legend grid ===== */
    .legend-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        width: 100%;
        align-items: start;
    }

    /* Police classification: 2 kolom (2 di atas, 2 di bawah).
       Label dibiarkan satu baris (tidak turun ke bawah); font sedikit
       dikecilkan supaya bloknya tidak terlalu melebar. */
    .legend-grid-2 {
        grid-template-columns: repeat(2, max-content);
        column-gap: 8px;
        row-gap: 2px;
        width: auto;
    }

    .legend-grid-2 .legend-grid-item {
        white-space: nowrap;
        align-items: center;
    }

    .legend-grid-2 .legend-grid-item small {
        font-size: 11px;
    }

    /* Airfield classification: 3 kolom (3 di atas, 3 di bawah), rapat & rata kiri */
    .legend-grid-3 {
        grid-template-columns: repeat(3, max-content);
        column-gap: 2px;
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

    /* Ikon tanpa ukuran inline (police) diseragamkan 12px */
    .legend-grid-item img {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
    }

    .legend-grid-item small {
        text-align: left;
    }

    /* ====== DIRECTIONS PANEL - Modern Styling ====== */
    #directionsPanel {
        font-family: 'Segoe UI', Roboto, -apple-system, sans-serif !important;
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 transparent;
    }
    #directionsPanel::-webkit-scrollbar { width: 5px; }
    #directionsPanel::-webkit-scrollbar-thumb {
        background: #c1c1c1; border-radius: 10px;
    }
    #directionsPanel .dp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: linear-gradient(135deg, #1a73e8, #4285f4);
        border-radius: 8px 8px 0 0;
        margin: 0;
        color: #fff;
    }
    #directionsPanel .dp-header-title {
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #directionsPanel .dp-header-title i { color: #fff !important; font-size: 16px; }
    #directionsPanel .dp-close-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        width: 28px; height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.2s;
    }
    #directionsPanel .dp-close-btn:hover { background: rgba(255,255,255,0.35); }
    #directionsPanel .dp-close-btn i { color: #fff !important; }

    /* Google-generated table overrides */
    #directionsPanel table { border: none !important; width: 100%; }
    #directionsPanel td {
        border: none !important;
        padding: 6px 4px !important;
        font-size: 13px;
        vertical-align: top;
    }
    #directionsPanel .adp-directions { margin: 0 !important; }

    /* Route summary (origin → destination bar) */
    #directionsPanel .adp-placemark {
        background: #f0f4ff;
        border-radius: 8px;
        margin-bottom: 8px !important;
        overflow: hidden;
    }
    #directionsPanel .adp-placemark td {
        padding: 10px 12px !important;
        font-weight: 600;
        color: #1a3c6e;
        font-size: 13px;
    }
    #directionsPanel .adp-placemark img {
        filter: hue-rotate(200deg) saturate(1.5);
    }

    /* Summary bar (distance & time) */
    #directionsPanel .adp-summary {
        background: linear-gradient(135deg, #e8f0fe, #d2e3fc);
        border-radius: 8px;
        padding: 10px 14px !important;
        margin: 8px 0 !important;
        font-size: 13px;
        color: #1a3c6e;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Step list */
    #directionsPanel .adp-listsel,
    #directionsPanel .adp-list {
        border: none !important;
    }
    #directionsPanel .adp-listinfo {
        border: none !important;
        background: transparent !important;
    }

    /* Individual step rows */
    #directionsPanel .adp-step {
        border-bottom: 1px solid #eef1f5 !important;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        transition: background 0.15s;
        border-radius: 6px;
        margin-bottom: 2px;
    }
    #directionsPanel .adp-step:hover {
        background: #f5f8ff !important;
    }
    #directionsPanel .adp-step:last-child {
        border-bottom: none !important;
    }

    /* Step icon cell */
    #directionsPanel .adp-step .adp-stepicon {
        padding: 8px 4px 8px 8px !important;
    }
    #directionsPanel .adp-step .adp-stepicon .adp-maneuver {
        width: 20px;
        height: 20px;
    }

    /* Step text */
    #directionsPanel .adp-step .adp-substep {
        padding: 8px 12px 8px 4px !important;
        color: #333;
        line-height: 1.5;
        font-size: 12.5px;
    }
    #directionsPanel .adp-step .adp-substep b {
        color: #1a73e8;
        font-weight: 600;
    }
    /* Step distance */
    #directionsPanel .adp-step td:last-child {
        color: #5f6368;
        font-size: 12px;
        white-space: nowrap;
        padding-right: 10px !important;
    }

    /* Warning / legal */
    #directionsPanel .adp-warnbox,
    #directionsPanel .adp-legal {
        font-size: 11px;
        color: #888;
        padding: 6px 12px !important;
        border: none !important;
    }
    #directionsPanel .adp-legal a { color: #1a73e8; }

    /* Highlighted / selected step */
    #directionsPanel .adp-listsel {
        background: #e8f0fe !important;
        border-radius: 6px;
    }

    /* ===== Google Places Autocomplete Fix ===== */
    .pac-container {
        z-index: 2147483647 !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2) !important;
        font-family: inherit !important;
        border: 1px solid #ddd !important;
    }
    .pac-item {
        padding: 6px 12px !important;
        cursor: pointer !important;
        font-size: 13px !important;
        border-top: 1px solid #f0f0f0 !important;
    }
    .pac-item:hover { background: #f0f6ff !important; }
    .pac-item-query {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #333 !important;
    }
    .pac-matched { color: #1a73e8 !important; font-weight: 700 !important; }
</style>
@endpush

@section('conten')

<div class="card">

    <div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">

        <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $hospital->name }}</h2>
            <span class="fw-bold"><b>Global Classification:</b> {{ $hospital->facility_category }} | <b>Country Classification:</b> {{ $hospital->facility_level }}</span>
        </div>

        <div class="d-flex gap-2 ms-auto">
            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill fs-3"></i>
                <small>Home</small>
            </a>

            <!-- Button 2 -->
            <a href="{{ url('hospitals') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/'.$hospital->id) ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <!-- Button 3 -->
            <a href="{{ url('hospitals/clinic') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/clinic/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-medical-facility-white.png') }}" style="width: 18px; height: 24px;">
                <small>Clinical</small>
            </a>

            <!-- Button 4 -->
            <a href="{{ url('hospitals/emergency') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/emergency/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-emergency-support-white.png') }}" style="width: 24px; height: 24px;">
                <small>Emergency</small>
            </a>

            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <!-- Button 5 -->
            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Aviation</small>
            </a>

            <a href="{{ url('police') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('police') ? 'active' : '' }}">
                <i class="bi bi-person-badge" style="width: 24px; height: 24px;"></i>
                <small>Police</small>
            </a>

            <!-- Button 7 -->
            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                <small>Embassies</small>
            </a>
        </div>
    </div>

    <div class="card mb-4 position-relative">
        <div class="card-body" style="padding:0 7px;">
            <small><i>Last Updated {{ $hospital->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('hospitaldata.edit', $hospital->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">
             <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-emergency-support.png') }}" style="width: 24px; height: 24px;"> Emergency Support Tools</div>

                 <div class="d-flex p-3" style="justify-content: center;">
                <div class="d-flex gap-2" style="display: grid; grid-template-columns: auto auto auto; justify-content: space-between; column-gap: 16px; row-gap: 10px; align-items: start; width: 100%;">

                      <!-- Airport -->
                      <div class="class-column" style="justify-self: start;">

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
                      <div style="justify-self: start; flex-direction: column;">
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
                      <div class="class-column" style="justify-self: end;">
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

                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header fw-bold"><img src="https://concord-consulting.com/static/img/cmt/icon/radar-icon.png" style="width: 24px; height: 24px;"> Nearest Support Facilities</div>
                <div class="card-body overflow-auto">
                    <?php echo $hospital->nearest_airfield; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/hotlines-icon.png') }}" style="width: 24px; height: 24px;"> Emergency Hotline</div>
                <div class="card-body">
                    <?php echo $hospital->travel_agent; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-medical-support-website.png') }}" style="width: 24px; height: 24px;"> Emergency Medical Support</div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                    <?php echo $hospital->medical_support_website; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-police.png') }}" style="width: 24px; height: 24px;"> Nearest Police station</div>
                <div class="card-body overflow-auto">
                    <?php echo $hospital->nearest_police_station; ?>
                </div>
            </div>
        </div>

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
        <p class="p-modal text-justify">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal text-justify">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
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
            <h5 class="modal-title" id="disclaimerLabel">Combined (Civil-Military) Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal text-justify">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
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
        <p class="p-modal text-justify">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
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
        <p class="p-modal text-justify">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal text-justify">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level11Modal" tabindex="-1" aria-labelledby="level11ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" alt="Health clinic" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level11ModalLabel">Clinic / Health Clinic (Community Primary-Care Level)</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <h6 class="alert-heading fw-bold">Disclaimer</h6>
          <p class="p-modal mb-2">Malaysia does not use “Health Center” as a separate current national facility grade equivalent to a hospital classification. In the Ministry of Health public system, the principal comprehensive primary-care facility is the Health Clinic – Klinik Kesihatan (KK). Other community primary-care facilities include Rural Clinics (Klinik Desa), Maternal and Child Health Clinics, Community Clinics (Klinik Komuniti), dental clinics, and mobile services.</p>
          <p class="p-modal mb-0">Public Health Clinics are organized through Ministry of Health administrative, workload, service, infrastructure, and catchment-planning frameworks. Private medical clinics follow a separate statutory framework under the Private Healthcare Facilities and Services Act 1998 [Act 586] and are registered as private medical clinics rather than classified as KK1–KK7.</p>
        </div>

        <h6 class="fw-bold mt-4">Overview</h6>
        <p class="p-modal">A Health Clinic – Klinik Kesihatan (KK) is a Ministry of Health community-based primary healthcare facility providing first-contact outpatient care, preventive services, continuing treatment, maternal and child healthcare, health promotion, disease-control programs, and referral to hospitals or specialist services when treatment exceeds clinic capability.</p>
        <p class="p-modal">Health Clinics form the principal comprehensive clinic level of Malaysia's public primary-care network. Their service capacity varies according to patient workload, population served, available medical personnel, infrastructure, diagnostic support, and local healthcare requirements. Larger Health Clinics may provide Family Medicine Specialist services, emergency assessment, laboratory services, radiography, rehabilitation, pharmacy, dental services, and extended maternal-child healthcare, while smaller clinics provide a more limited primary-care package.</p>
        <p class="p-modal">The Ministry of Health classifies Health Clinics from KK1 to KK7 according to average daily patient attendance. Standard facility planning separately considers the services provided and estimated catchment population. These classifications determine clinic scale and planning requirements; they do not represent hospital grades or inpatient levels.</p>

        <h6 class="fw-bold mt-4">Role</h6>
        <ul class="p-modal">
          <li>Provide first-contact medical assessment, diagnosis, treatment, monitoring, and follow-up for common illnesses and minor injuries.</li>
          <li>Provide continuing management of chronic and noncommunicable diseases, including diabetes, hypertension, cardiovascular risk conditions, and other priority conditions.</li>
          <li>Deliver maternal, antenatal, postnatal, newborn, child, adolescent, reproductive, adult, elderly, and family-health services.</li>
          <li>Provide immunization, screening, health promotion, nutrition, preventive healthcare, communicable-disease control, and public-health programs.</li>
          <li>Provide basic emergency assessment, initial resuscitation, stabilization, and coordination of ambulance or hospital referral.</li>
          <li>Conduct minor outpatient procedures and basic diagnostic investigations according to clinic capability.</li>
          <li>Coordinate referrals to district, specialist, state, university, or other referral hospitals when patients require inpatient care, surgery, specialist management, advanced diagnostics, or critical care.</li>
          <li>Support community outreach, surveillance, domiciliary services, health education, and population-health programs.</li>
        </ul>

        <h6 class="fw-bold mt-4">Clinical Services</h6>
        <h6 class="mt-3">Approximate Bed Capacity</h6>
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
  <div class="modal-dialog modal-dialog-centered">
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
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" alt="Primary medical facility" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level44ModalLabel">Primary Medical Facilities (Community Level)</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <h6 class="alert-heading fw-bold">Disclaimer</h6>
          <p class="p-modal mb-2">Malaysia’s public primary-care network is classified mainly by facility type, service scope, workload, standard facility plan, and catchment population. The KK1–KK7 classification applies to Ministry of Health Health Clinics and does not represent a hospital grade.</p>
          <p class="p-modal mb-0"><strong>Source:</strong> <a href="https://hq.moh.gov.my/bpkk/index.php/klasifikasi-klinik-kesihatan" class="text-primary" target="_blank" rel="noopener noreferrer">Malaysia Ministry of Health: Health Clinic Classification</a></p>
        </div>

        <h6 class="fw-bold mt-4">Overview</h6>
        <p class="p-modal">Primary medical facilities provide the main community entry point into Malaysia’s public health system. Health Clinics deliver broad outpatient, maternal-child, emergency, preventive, chronic-disease, pharmacy, laboratory, dental, rehabilitation, and public-health services according to clinic type. Rural Clinics, Maternal and Child Health Clinics, Community Clinics, mobile services, and dental facilities extend access closer to local populations.</p>
        <p class="p-modal">As of 31 December 2024, the Ministry of Health reported 1,131 Health Clinics, 1,656 Rural Clinics, 77 Maternal and Child Health Clinics, and 205 Community Clinics. Mobile clinic services operated through bus, boat, and helicopter teams.</p>
        <p class="p-modal"><strong>Note:</strong> Health Clinic classification has two related uses. The workload classification places clinics into KK1–KK7 by average daily attendance. Standard facility planning uses service scope and estimated catchment population. The two approaches support planning and do not create inpatient hospital levels.</p>

        <h6 class="fw-bold mt-4">Role</h6>
        <ul class="p-modal">
          <li>Provide first-contact assessment and treatment for common acute, chronic, communicable, and noncommunicable conditions.</li>
          <li>Deliver maternal, newborn, child, adolescent, reproductive, adult, elderly, disability, and family-health services.</li>
          <li>Provide immunisation, screening, disease prevention, surveillance, health promotion, nutrition, and community outreach.</li>
          <li>Manage hypertension, diabetes, cardiovascular risk, tuberculosis, HIV, mental-health conditions, and other priority programmes according to clinic capability.</li>
          <li>Provide basic emergency response, ambulance coordination, stabilisation, and referral for hospital treatment.</li>
        </ul>

        <h6 class="fw-bold mt-4">Clinical Services</h6>
        <h6 class="mt-3">Approximate Bed Capacity</h6>
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

<div class="modal fade" id="level55Modal" tabindex="-1" aria-labelledby="level55ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" alt="Secondary medical facility" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level55ModalLabel">Secondary Medical Facilities (State/District Referral Level)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <h6 class="alert-heading fw-bold">Disclaimer</h6>
          <p class="p-modal mb-2">Secondary facilities are described by their hospital-level referral and treatment role. The Ministry of Health administratively separates hospitals with specialists from hospitals without specialists. Hospitals with specialists are further divided into major and minor specialist hospitals for administrative purposes. These categories are not defined by a single bed threshold.</p>
          <p class="p-modal mb-0"><strong>Source:</strong> <a href="https://www.moh.gov.my/images/04-penerbitan/pelan-strategik/Pelan_Strategik_KKM_compressed.pdf" class="text-primary" target="_blank" rel="noopener noreferrer">Malaysia Ministry of Health: Strategic Framework of the Medical Programme 2021–2025</a></p>
        </div>

        <h6 class="fw-bold mt-4">Overview</h6>
        <p class="p-modal">Secondary medical facilities provide hospital-level emergency, outpatient, inpatient, medical, surgical, maternity, paediatric, diagnostic, rehabilitative, and stabilisation services for state, divisional, district, or multi-district catchments. They receive referrals from health clinics, rural clinics, community clinics, private clinics, pre-hospital services, and smaller hospitals. Complex cases move to a state hospital, major referral centre, special medical institution, or university hospital.</p>
        <p class="p-modal"><strong>Note:</strong> The boundary between secondary and tertiary care is not rigid. Major specialist hospitals may deliver both levels, while minor specialist hospitals concentrate on core resident specialties. Hospitals without resident specialists provide general hospital care and rely on visiting specialists, hospital clusters, teleconsultation, or upward referral.</p>

        <h6 class="fw-bold mt-4">Role</h6>
        <ul class="p-modal">
          <li>Provide hospital-level referral care for a defined state, district, or multi-district population.</li>
          <li>Manage common and moderately complex medical, surgical, obstetric, paediatric, and emergency conditions.</li>
          <li>Provide inpatient admission, observation, diagnostics, essential surgery, maternity care, rehabilitation, and specialist consultation according to local capability.</li>
          <li>Stabilise critically ill, injured, high-risk obstetric, neonatal, or surgical patients before transfer.</li>
          <li>Support clinical supervision, outreach, referral communication, and service integration with primary-care facilities.</li>
        </ul>

        <h6 class="fw-bold mt-4">Clinical Services</h6>
        <h6 class="mt-3">Approximate Bed Capacity</h6>
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

<div class="modal fade" id="level66Modal" tabindex="-1" aria-labelledby="level66ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" alt="Tertiary medical facility" class="me-2" style="width:30px; height:30px;">
            <h5 class="modal-title" id="level66ModalLabel">Tertiary Medical Facilities (National/State/Regional Referral Level)</h5>
        </div>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert border-0 border-start border-3 rounded-1" role="alert" style="background-color: #eef2f6; border-left-color: #536f91 !important; color: #4f5f72;">
          <h6 class="alert-heading fw-bold">Disclaimer</h6>
          <p class="p-modal mb-2">The care-level classifications in this document organise Malaysia’s medical facilities according to clinical capability, referral responsibility, and position in the patient-care pathway. They must be read together with the Ministry of Health administrative hospital categories. State hospital, major specialist hospital, minor specialist hospital, hospital without specialist, and special medical institution are administrative or functional categories; they are not statutory bed grades.</p>
          <p class="p-modal mb-0"><strong>Source:</strong> <a href="https://www.moh.gov.my/images/04-penerbitan/pelan-strategik/Pelan_Strategik_Bahagian_Perkembangan_Perubatan.pdf" class="text-primary" target="_blank" rel="noopener noreferrer">Malaysia Ministry of Health: Specialty &amp; Subspecialty Framework of Ministry of Health Hospitals</a></p>
        </div>

        <h6 class="fw-bold mt-4">Overview</h6>
        <p class="p-modal">Tertiary medical care forms Malaysia’s highest specialist and subspecialist referral platform. It is concentrated in state hospitals, selected major specialist hospitals, national or regional referral centres, special medical institutions, and university teaching hospitals. These facilities manage complex disease, advanced surgery, critical care, multidisciplinary treatment, and referrals that exceed the capability of district hospitals and primary-care services.</p>
        <p class="p-modal"><strong>Note:</strong> Tertiary status is determined by actual service capability and referral role rather than the hospital’s administrative label alone. A state hospital normally provides broad tertiary services, but selected major specialist hospitals and special medical institutions may act as national or regional centres for defined disciplines. Hospital clustering allows specialised services to be distributed across nearby hospitals regardless of category.</p>

        <h6 class="fw-bold mt-4">Role</h6>
        <ul class="p-modal">
          <li>Provide national, state, or regional referral care for complex, severe, high-risk, and uncommon conditions.</li>
          <li>Receive referrals from specialist hospitals, non-specialist hospitals, health clinics, private providers, ambulance services, and direct emergency presentation.</li>
          <li>Deliver advanced medical, surgical, obstetric, paediatric, neonatal, diagnostic, intensive-care, rehabilitative, and palliative services.</li>
          <li>Coordinate multidisciplinary treatment, subspecialist consultation, advanced diagnostics, and long-term follow-up.</li>
          <li>Support teaching, specialist training, clinical research, national protocols, and service-development programmes.</li>
          <li>Refer selected patients abroad or to another national centre when a required procedure or subspecialty is unavailable locally.</li>
        </ul>

        <h6 class="fw-bold mt-4">Clinical Services</h6>
        <h6 class="mt-3">Approximate Bed Capacity</h6>
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

<!-- ===== Police Classification Modals ===== -->

<div class="modal fade" id="police1Modal" tabindex="-1" aria-labelledby="police1ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="width: calc(100vw - 2rem); max-width: 1050px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer4.png') }}" alt="Police Station" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police1ModalLabel">Police Station</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs flex-nowrap w-100" id="police1Tabs" role="tablist">
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
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="width: calc(100vw - 2rem); max-width: 850px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer3.png') }}" alt="District Police Headquarters" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police2ModalLabel">District Police Headquarters (IPD)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs flex-nowrap w-100" id="police2Tabs" role="tablist">
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
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer2.png') }}" alt="State police contingent headquarters" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police3ModalLabel">State Police Contingent Headquarters (IPK)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs flex-nowrap overflow-auto" id="police3Tabs" role="tablist">
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
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="width: calc(100vw - 2rem); max-width: 1050px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer1.png') }}" alt="National Police HQ" class="me-2" style="width:20px; height:20px;">
            <h5 class="modal-title" id="police4ModalLabel">Royal Malaysia Police / Polis Diraja Malaysia (PDRM)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs flex-nowrap" id="police4Tabs" role="tablist" style="font-size: 13px;">
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const hospitalData = {!! json_encode([
        'id'        => $hospital->id,
        'name'      => $hospital->name,
        'latitude'  => $hospital->latitude,
        'longitude' => $hospital->longitude,
        'icon'      => $hospital->icon ?? '',
    ]) !!};

    const nearbyHospitals = @json($nearbyHospitals);
    const nearbyAirports = @json($nearbyAirports);
    const nearbyPolices = @json($nearbyPolices);
    const nearbyEmbassy = @json($nearbyEmbassy);
    const DEFAULT_RADIUS_KM = {{ $radius_km }};
    let radiusKm = DEFAULT_RADIUS_KM;

    let map, mainMarker, radiusCircle, directionsService, directionsRenderer;
    let nearbyMarkersGroup = [];
    let searchLocation = null;
    let searchMarker = null;

    // === ICON DEFAULT ===
    const DEFAULT_MAIN_HOSPITAL_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
    const DEFAULT_HOSPITAL_ICON_URL      = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
    const DEFAULT_AIRPORT_ICON_URL       = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
    const DEFAULT_POLICE_ICON_URL        = 'https://png.pngtree.com/png-vector/20221211/ourmid/pngtree-minimal-location-map-icon-logo-symbol-vector-design-transparent-background-png-image_6520892.png';
    const DEFAULT_EMBASSY_ICON_URL       = '/images/embassy-icon-new.png';

    // === INISIALISASI PETA ===
    function initializeMap() {
        const center = new google.maps.LatLng(hospitalData.latitude, hospitalData.longitude);
        map = new google.maps.Map(document.getElementById('map'), {
            center: center,
            zoom: 11,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: true,
            fullscreenControl: true,
            streetViewControl: false
        });

        const directionsPanel = document.createElement('div');
        directionsPanel.id = 'directionsPanel';
        directionsPanel.style.width = '370px';
        directionsPanel.style.maxHeight = '450px';
        directionsPanel.style.overflowY = 'auto';
        directionsPanel.style.backgroundColor = 'white';
        directionsPanel.style.display = 'none';
        directionsPanel.style.boxShadow = '0 4px 20px rgba(0,0,0,0.2)';
        directionsPanel.style.borderRadius = '12px';
        directionsPanel.style.margin = '10px';
        directionsPanel.style.padding = '0';
        directionsPanel.style.fontSize = '13px';

        // Header
        const dpHeader = document.createElement('div');
        dpHeader.className = 'dp-header';
        dpHeader.innerHTML = `
            <div class="dp-header-title">
                <i class="fas fa-route"></i> Route Directions
            </div>
            <button class="dp-close-btn" title="Close">
                <i class="fas fa-times"></i>
            </button>
        `;
        directionsPanel.appendChild(dpHeader);

        // Content area (Google renders steps here)
        const dpContent = document.createElement('div');
        dpContent.style.padding = '10px';
        directionsPanel.appendChild(dpContent);

        // Close button handler
        dpHeader.querySelector('.dp-close-btn').addEventListener('click', () => {
            directionsPanel.style.display = 'none';
            directionsRenderer.setDirections({routes: []});
        });

        google.maps.event.addDomListener(directionsPanel, 'click', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'dblclick', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'mousedown', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'touchstart', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'wheel', e => e.stopPropagation());

        map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(directionsPanel);

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            panel: dpContent,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#1a73e8',
                strokeOpacity: 0.8,
                strokeWeight: 5
            }
        });
    }

    // === MARKER UTAMA DAN RADIUS ===
    function addMainHospitalAndCircle() {
        mainMarker = new google.maps.Marker({
            position: new google.maps.LatLng(hospitalData.latitude, hospitalData.longitude),
            map: map,
            icon: {
                url: DEFAULT_MAIN_HOSPITAL_ICON_URL,
                scaledSize: new google.maps.Size(25, 41)
            },
            title: hospitalData.name
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `<b>${hospitalData.name}</b><br>This is the main hospital.`
        });

        mainMarker.addListener('click', () => {
            infoWindow.open(map, mainMarker);
        });

        radiusCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.1,
            map: map,
            center: { lat: parseFloat(hospitalData.latitude), lng: parseFloat(hospitalData.longitude) },
            radius: radiusKm * 1000
        });
    }

    function clearNearbyMarkers() {
        for (let i = 0; i < nearbyMarkersGroup.length; i++) {
            nearbyMarkersGroup[i].setMap(null);
        }
        nearbyMarkersGroup = [];
    }

    // === TAMBAH MARKER SEKITAR ===
    function addNearbyMarkers(data, defaultIconUrl, type, filters = {}) {
        data.forEach(item => {
            const distance = calculateDistance(
                hospitalData.latitude, hospitalData.longitude,
                item.latitude, item.longitude
            );
            if (distance > radiusKm) return;

            // Filter hospital
            if (type === 'Hospital' && filters.hospitalLevels?.length > 0) {
                const level = (item.facility_level || '').toLowerCase();
                const allowed = filters.hospitalLevels.map(l => l.toLowerCase());
                if (!allowed.includes(level)) return;
            }

            // Filter airport
            if (type === 'Airport' && filters.airportClassifications?.length > 0) {
                const categories = (item.category || '').split(',').map(c => c.trim().toLowerCase());
                const allowed = filters.airportClassifications.map(c => c.toLowerCase());
                if (!categories.some(cat => allowed.includes(cat))) return;
            }

            // Filter police
            if (type === 'Police' && filters.policeCategories?.length > 0) {
                const categories = (item.category || '').split(',').map(c => c.trim().toLowerCase());
                const allowed = filters.policeCategories.map(c => c.toLowerCase());
                if (!categories.some(cat => allowed.includes(cat))) return;
            }

            // Ikon police dibuat lebih kecil dari pin airfield / medical
            const iconSize = (type === 'Police')
                ? new google.maps.Size(12, 12)
                : new google.maps.Size(24, 24);

            const marker = new google.maps.Marker({
                position: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) },
                map: map,
                icon: {
                    url: item.icon || defaultIconUrl,
                    scaledSize: iconSize
                }
            });

            const name = item.name || item.airport_name || item.name_police || item.name_embassiees || 'N/A';
            const level = item.facility_level || item.category || 'N/A';

            let url = '#';
            if (type === 'Airport') {
                url = `/airports/${item.id}/detail`;
            } else if (type === 'Hospital') {
                url = `/hospitals/${item.id}`;
            } else if (type === 'Police') {
                url = `/police/${item.id}/detail`;
            } else if (type === 'Embassy') {
                url = `/embassiees/${item.id}/detail`;
            }

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="font-size:13px;">
                        <a href="${url}" target="_blank">${name}</a><br>
                        ${level}<br>
                        <strong>Distance:</strong> ${distance.toFixed(2)} km<br>
                        <button class="btn btn-sm btn-primary mt-2"
                            onclick="getDirection(${item.latitude}, ${item.longitude})">
                            Get Direction
                        </button>
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            nearbyMarkersGroup.push(marker);
        });
    }

    // === HITUNG JARAK ===
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    // === NEARBY HOTELS (muncul setelah lokasi dicari) ===
    let categoryMarkers   = [];
    let activeCategoryBtn = null;
    let categoryBar       = null;

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
        if (!searchLocation) return;
        clearCategoryMarkers();

        const center  = new google.maps.LatLng(searchLocation.lat, searchLocation.lng);
        const service = new google.maps.places.PlacesService(map);

        const iconColors = { lodging: '#1a73e8' };
        const color = iconColors[type] || '#555';

        function makeSvgIcon(col) {
            const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='32' height='40' viewBox='0 0 32 40'>`
                      + `<path d='M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24S32 28 32 16C32 7.16 24.84 0 16 0z' fill='${col}'/>`
                      + `<circle cx='16' cy='16' r='7' fill='#fff'/>`
                      + `</svg>`;
            return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
        }

        service.nearbySearch({ location: center, radius: 5000, type }, (results, status) => {
            if (status !== google.maps.places.PlacesServiceStatus.OK) {
                if (status === 'ZERO_RESULTS') {
                    alert(`No ${label.toLowerCase()} found within 5 km.`);
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

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="font-size:13px;min-width:190px;">
                            <h5 style="border-bottom:1px solid #ccc;margin:0 0 6px;font-size:14px;">${place.name}</h5>
                            <div style="color:#666;font-size:12px;margin-bottom:3px;">${label}</div>
                            ${rating  ? `<div style="font-size:12px;">${rating}</div>` : ''}
                            <div style="margin-top:4px;font-size:12px;color:#555;"> ${distText} from search location</div>
                            <button class="btn btn-sm btn-primary mt-2"
                                onclick="getDirection(${destLat}, ${destLng})">
                                Get Direction
                            </button>
                        </div>`
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                categoryMarkers.push(marker);
            });
        });
    }

    function setupNearbyCategoryBar() {
        categoryBar = document.createElement('div');
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
                    clearCategoryMarkers();
                    resetCategoryBtn(btn);
                    activeCategoryBtn = null;
                    return;
                }
                if (activeCategoryBtn) resetCategoryBtn(activeCategoryBtn);
                activeCategoryBtn = btn;
                btn.style.background = '#1a73e8';
                btn.style.color      = '#fff';
                btn.style.borderColor= '#1a73e8';
                showNearbyCategory(cat.type, cat.label);
            });

            categoryBar.appendChild(btn);
        });

        map.controls[google.maps.ControlPosition.TOP_CENTER].push(categoryBar);
    }

    // === ROUTING ===
    window.getDirection = function(lat, lng) {
        const origin = searchLocation
            ? new google.maps.LatLng(searchLocation.lat, searchLocation.lng)
            : new google.maps.LatLng(hospitalData.latitude, hospitalData.longitude);

        directionsService.route({
            origin: origin,
            destination: new google.maps.LatLng(lat, lng),
            travelMode: 'DRIVING'
        }, (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                const panel = document.getElementById('directionsPanel');
                if(panel) panel.style.display = 'block';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Route Not Found',
                    text: status === 'ZERO_RESULTS'
                        ? 'No driving route could be found between these two locations.'
                        : 'Directions request failed (' + status + ').',
                    confirmButtonColor: '#d33'
                });
            }
        });
    };

    // === FIT MAP ===
    function fitMapToBounds() {
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(hospitalData.latitude, hospitalData.longitude));
        if (searchLocation) {
            bounds.extend(new google.maps.LatLng(searchLocation.lat, searchLocation.lng));
        }
        nearbyMarkersGroup.forEach(m => bounds.extend(m.getPosition()));

        const circleBounds = radiusCircle.getBounds();
        if(circleBounds) {
            bounds.union(circleBounds);
        }

        map.fitBounds(bounds);
    }

    // === UPDATE MARKER ===
    function updateMarkers(filterType, hospitalLevels, airportClassifications, policeCategories) {
        clearNearbyMarkers();
        if (radiusCircle) radiusCircle.setMap(null);
        addMainHospitalAndCircle();

        const filters = { hospitalLevels, airportClassifications, policeCategories };

        if (filterType === 'hospital') {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
        } else if (filterType === 'airport') {
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
        } else if (filterType === 'police') {
            addNearbyMarkers(nearbyPolices, DEFAULT_POLICE_ICON_URL, 'Police', filters);
        } else if (filterType === 'embassy') {
            addNearbyMarkers(nearbyEmbassy, DEFAULT_EMBASSY_ICON_URL, 'Embassy', filters);
        } else {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
            addNearbyMarkers(nearbyPolices, DEFAULT_POLICE_ICON_URL, 'Police', filters);
            addNearbyMarkers(nearbyEmbassy, DEFAULT_EMBASSY_ICON_URL, 'Embassy', filters);
        }

        fitMapToBounds();
    }

    // === FILTER CONTROL (Search Location + Radius + Filter) ===
    function setupFilterControl() {
        const container = document.createElement('div');
        container.className = 'p-2 bg-white rounded';
        container.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
        container.style.width = '260px';
        container.style.maxHeight = '85vh';
        container.style.overflowY = 'auto';
        container.style.marginRight = '10px';
        container.style.marginTop = '10px';
        container.style.cursor = 'default';

        container.innerHTML = `
            <h6 style="text-align:center;">Map Filters</h6>

            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Search Location</strong>
            <div style="position:relative;margin-top:5px;margin-bottom:8px;">
                <input type="text" id="gmSearchInput" class="form-control form-control-sm"
                    placeholder="Search Location..." autocomplete="off" style="padding-right:28px;">
                <i class="fas fa-times" id="gmClearBtn"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);color:#70757a;font-size:13px;cursor:pointer;display:none;"></i>
            </div>

            <label><strong>Radius:</strong> <span id="radiusLabel">${radiusKm}</span> km</label><br>
            <input type="range" id="radiusRange" min="10" max="500" step="10" value="${radiusKm}" class="form-range mb-2"><br>

            <select id="mapFilter" class="form-select form-select-sm mb-2">
                <option value="all">Show All</option>
                <option value="hospital">Hospitals</option>
                <option value="airport">Aviation</option>
                <option value="police">Police</option>
                <option value="embassy">Embassy</option>
            </select>

            <div id="hospitalFilter" style="display:none;">
                <strong>Facility Level:</strong><br>
                ${['Tertiary','Secondary','Primary','Clinic / Health Center']
                    .map(lvl => `
                    <label style="display:block;font-size:13px;">
                        <input type="checkbox" name="hospitalLevel" value="${lvl}"> ${lvl}
                    </label>`).join('')}
            </div>

            <div id="airportFilter" style="display:none;margin-top:8px;">
                <strong>Category:</strong><br>
                ${['International','Domestic','Military','Regional','Private']
                    .map(cls => `
                    <label style="display:block;font-size:13px;">
                        <input type="checkbox" name="airportClass" value="${cls}"> ${cls}
                    </label>`).join('')}
            </div>

            <div id="policeFilter" style="display:none;margin-top:8px;">
                <strong>Police Category:</strong><br>
                ${[
                    'National Police HQ',
                    'State police contingent headquarters (IPK)',
                    'District Police Force (IPD)',
                    'Police Station'
                ].map(cat => `
                    <label style="display:block;font-size:13px;">
                        <input type="checkbox" name="policeCategory" value="${cat}"> ${cat}
                    </label>
                `).join('')}
            </div>

            <button id="resetFilter" class="btn btn-sm btn-secondary mt-3 w-100">Reset All</button>
        `;

        // Cegah event diteruskan ke peta
        google.maps.event.addDomListener(container, 'click', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'dblclick', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'mousedown', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'touchstart', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'wheel', e => e.stopPropagation());

        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(container);

        const radiusSlider = container.querySelector('#radiusRange');
        const radiusLabel = container.querySelector('#radiusLabel');
        const filterSelect = container.querySelector('#mapFilter');
        const hospitalDiv = container.querySelector('#hospitalFilter');
        const airportDiv = container.querySelector('#airportFilter');
        const policeDiv = container.querySelector('#policeFilter');
        const resetBtn = container.querySelector('#resetFilter');

        function refresh() {
            const selectedType = filterSelect.value;
            const selectedHospitalLevels = Array.from(container.querySelectorAll('input[name="hospitalLevel"]:checked')).map(el => el.value);
            const selectedAirportClasses = Array.from(container.querySelectorAll('input[name="airportClass"]:checked')).map(el => el.value);
            const selectedPoliceCategories = Array.from(container.querySelectorAll('input[name="policeCategory"]:checked')).map(el => el.value);
            updateMarkers(selectedType, selectedHospitalLevels, selectedAirportClasses, selectedPoliceCategories);
        }

        radiusSlider.addEventListener('input', () => {
            radiusKm = parseInt(radiusSlider.value);
            radiusLabel.textContent = radiusKm;
            refresh();
        });

        filterSelect.addEventListener('change', () => {
            const val = filterSelect.value;
            hospitalDiv.style.display = val === 'hospital' ? 'block' : 'none';
            airportDiv.style.display = val === 'airport' ? 'block' : 'none';
            policeDiv.style.display = val === 'police' ? 'block' : 'none';
            refresh();
        });

        container.querySelectorAll('input[name="hospitalLevel"]').forEach(chk => chk.addEventListener('change', refresh));
        container.querySelectorAll('input[name="airportClass"]').forEach(chk => chk.addEventListener('change', refresh));
        container.querySelectorAll('input[name="policeCategory"]').forEach(chk => chk.addEventListener('change', refresh));

        resetBtn.addEventListener('click', () => {
            container.querySelectorAll('input[type="checkbox"]').forEach(chk => chk.checked = false);
            filterSelect.value = 'all';
            hospitalDiv.style.display = 'none';
            airportDiv.style.display = 'none';
            policeDiv.style.display = 'none';
            radiusKm = DEFAULT_RADIUS_KM;
            radiusSlider.value = radiusKm;
            radiusLabel.textContent = radiusKm;

            const gmInput = container.querySelector('#gmSearchInput');
            if(gmInput) gmInput.value = '';
            const gmClear = container.querySelector('#gmClearBtn');
            if(gmClear) gmClear.style.display = 'none';

            if (searchMarker) {
                searchMarker.setMap(null);
                searchMarker = null;
            }
            searchLocation = null;

            if (categoryBar) categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            directionsRenderer.setDirections({routes: []});
            const panel = document.getElementById('directionsPanel');
            if(panel) panel.style.display = 'none';

            refresh();
        });

        return container;
    }

    // === SEARCH LOCATION (bagian dari panel filter) ===
    function setupSearchControl(filterContainer) {
        const input = filterContainer.querySelector('#gmSearchInput');
        const clearBtn = filterContainer.querySelector('#gmClearBtn');
        if (!input || !clearBtn) return;

        input.addEventListener('keydown', (e) => {
            if(e.key === 'Enter') e.preventDefault();
        });

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        // Input-nya berada di dalam custom map control, sehingga dropdown
        // ".pac-container" milik Google (di-append ke <body> dengan
        // position:absolute) ikut terpotong / tertutup control pane peta.
        // Paksa position:fixed dan terus terapkan ulang, karena Google me-reset
        // inline style container tiap kali daftar prediksi diperbarui.
        let pacContainer = null;

        function fixPacPosition() {
            if (!pacContainer) return;
            if (pacContainer.parentElement !== document.body) {
                document.body.appendChild(pacContainer);
            }
            const rect = input.getBoundingClientRect();
            pacContainer.style.position = 'fixed';
            pacContainer.style.zIndex = '2147483647';
            pacContainer.style.top = (rect.bottom + 2) + 'px';
            pacContainer.style.left = rect.left + 'px';
            pacContainer.style.width = rect.width + 'px';
            pacContainer.style.visibility = 'visible';
            pacContainer.style.opacity = '1';
            pacContainer.style.pointerEvents = 'auto';
        }

        function claimPacContainer() {
            if (pacContainer) return true;
            pacContainer = document.querySelector('.pac-container');
            if (pacContainer) {
                fixPacPosition();
                new MutationObserver(fixPacPosition).observe(
                    pacContainer, { attributes: true, attributeFilter: ['style'] }
                );
                return true;
            }
            return false;
        }

        const pacObserver = new MutationObserver(() => claimPacContainer());
        pacObserver.observe(document.body, { childList: true, subtree: true });

        // Cadangan kalau Google membuat ".pac-container" sebelum observer di atas
        // mulai memantau (MutationObserver hanya melaporkan mutasi berikutnya).
        if (!claimPacContainer()) {
            const pollId = setInterval(() => {
                if (claimPacContainer()) clearInterval(pollId);
            }, 200);
            setTimeout(() => clearInterval(pollId), 10000);
        }

        window.addEventListener('scroll', fixPacPosition, true);
        window.addEventListener('resize', fixPacPosition);
        input.addEventListener('focus', fixPacPosition);
        input.addEventListener('input', fixPacPosition);

        input.addEventListener('input', (e) => {
            if (e.target.value.length > 0) {
                clearBtn.style.display = 'block';
            } else {
                clearBtn.style.display = 'none';
            }
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            clearBtn.style.display = 'none';
            input.focus();
            if (pacContainer) pacContainer.style.display = 'none';

            if (searchMarker) {
                searchMarker.setMap(null);
                searchMarker = null;
            }
            searchLocation = null;

            if (categoryBar) categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            directionsRenderer.setDirections({routes: []});
            const panel = document.getElementById('directionsPanel');
            if(panel) panel.style.display = 'none';
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                return;
            }

            if (searchMarker) searchMarker.setMap(null);

            searchMarker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                icon: {
                    url: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    scaledSize: new google.maps.Size(25, 41)
                }
            });

            const lat = place.geometry.location.lat();
            const lon = place.geometry.location.lng();
            searchLocation = { lat: lat, lng: lon };

            if (categoryBar) categoryBar.style.display = 'flex';

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="font-size:13px;">
                        <b>${place.name}</b><br>
                        <small>Lat: ${lat.toFixed(5)}, Lng: ${lon.toFixed(5)}</small><br>
                        <button class="btn btn-sm btn-primary mt-2"
                            onclick="getDirection(${hospitalData.latitude}, ${hospitalData.longitude})">
                            Get Direction to Main Hospital
                        </button>
                    </div>
                `
            });

            infoWindow.open(map, searchMarker);
            searchMarker.addListener('click', () => {
                infoWindow.open(map, searchMarker);
            });

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(14);
            }
        });
    }

    // === JALANKAN ===
    initializeMap();
    addMainHospitalAndCircle();
    updateMarkers('all', [], [], []);
    const filterContainer = setupFilterControl();
    setupSearchControl(filterContainer);
    setupNearbyCategoryBar();
});
</script>

@endpush
