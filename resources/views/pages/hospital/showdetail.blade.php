@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'Malaysia Medical Facility')

@push('styles')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />

<style>
    #map {
        height: 600px;
    }

    p{
        margin-bottom: 8px;
        line-height: 18px;
    }

    .info-modal-tabs .nav-item:not(:last-child) {
        margin-right: 8px;
    }

    .info-modal-tabs .nav-link {
        font-size: 12px;
        font-weight: 600;
        padding: 8px 14px;
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

     .leaflet-routing-container-hide .leaflet-routing-collapse-btn
    {
        left: 8px;
        top: 8px;
    }

    .leaflet-control-container .leaflet-routing-container-hide {
        width: 48px;
        height: 48px;
    }

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
    .class-medical-classification {border: none; text-align: center;}
    .class-airport-category {border: none;}
    .class-advanced { border-bottom: 3px solid #0070c0; }
    .class-intermediate { border-bottom: 3px solid #00b050; }
    .class-basic { border-bottom: 3px solid #ffc000; }

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

            <a href="{{ url('hospitals') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <a href="{{ url('hospitals/clinic') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/clinic/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-medical-facility-white.png') }}" style="width: 18px; height: 24px;">
                <small>Clinical</small>
            </a>

            <a href="{{ url('hospitals/emergency') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/emergency/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-emergency-support-white.png') }}" style="width: 24px; height: 24px;">
                <small>Emergency</small>
            </a>

            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Aviation</small>
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
        <div class="col-md-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-general-info.png') }}" style="width: 24px; height: 24px;"> General Medical Facility Info</div>
                <div class="card-body overflow-auto">
                    <p>
                        <strong>Status:</strong> {{ $hospital->status }}
                    </p>
                    <p>
                        <strong>Number Of Beds:</strong> {{ $hospital->number_of_beds }}
                    </p>
                    <p>
                        <strong>Population Catchment:</strong> {{ $hospital->population_catchment }}
                    </p>
                    <p>
                        <strong>Ownership:</strong> {{ $hospital->ownership }}
                    </p>
                    <p>
                        <strong>Hours Of Operation:</strong><br>
                        <?php echo $hospital->hrs_of_operation; ?>
                    </p>
                    <p>
                        <strong>Note:</strong>
                        <?php echo $hospital->others; ?>
                    </p>
                    <p>
                        <strong>Medical Services Info:</strong> <?php echo $hospital->other_medical_info; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-location.png') }}" style="width: 18px; height: 24px;"> Location</div>
                <div class="card-body overflow-auto">
                    <p>
                        <strong>Address:</strong>
                        {{ $hospital->address }},
                        {{ $city->city }},
                        {{ $province->provinces_region }}, Malaysia
                    </p>
                    <p>
                        <strong>Latitude:</strong> {{ $hospital->latitude }}
                    </p>
                    <p>
                        <strong>Longitude:</strong> {{ $hospital->longitude }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/contact-icon.png') }}" style="width: 24px; height: 24px;"> Contact Details</div>
                <div class="card-body overflow-auto">
                    <p>
                        <strong>Telephone:</strong> <?php echo $hospital->telephone; ?>
                    </p>
                    <p>
                        <strong>Fax:</strong> <?php echo $hospital->fax; ?>
                    </p>
                    <p>
                        <strong>Email:</strong> <?php echo $hospital->email; ?>
                    </p>
                    <p>
                        <strong>Website:</strong> <?php echo $hospital->website; ?>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-nearest-accomodation.png') }}" style="width: 24px; height: 18px;">  Nearest Accommodation</div>
                <div class="card-body overflow-auto">
                    <?php echo $hospital->nearest_accommodation; ?>
                </div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="card">

             <div class="classification" style="flex-direction: column; width:100%;">
                      <div class="class-header class-medical-classification">Medical Facility Classification</div>
                      <div class="classification">
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

                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
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

@endsection

@push('service')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const latitude = {{ $hospital->latitude }};
    const longitude = {{ $hospital->longitude }};
    const embassyName = '{{ $hospital->name }}';

    const map = L.map('map', {
        fullscreenControl: true
    }).setView([latitude, longitude], 17);

    // --- Define Tile Layers ---
    // 1. Street Map (OpenStreetMap)
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18 // OSM generally goes up to zoom level 22
    });

    // 2. Satellite Map (Esri World Imagery) - Recommended, no API key needed
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri',
        maxZoom: 18 // Esri World Imagery also typically goes up to zoom level 22
    });

    // Add the satellite layer to the map by default
    satelliteLayer.addTo(map);

    // --- Add Layer Control ---
    // Define the base layers that the user can switch between
   const baseLayers = {
        "Satelit Map": satelliteLayer,
        "Street Map": osmLayer
    };

    // Add the layer control to the map. This will appear in the top-right corner.
    L.control.layers(baseLayers).addTo(map);

    // Add a marker at the embassy's location
    L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup(embassyName) // Display the embassy's name when the marker is clicked
        .openPopup(); // Automatically open the popup when the map loads
</script>
@endpush
