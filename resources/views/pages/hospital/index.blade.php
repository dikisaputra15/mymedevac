@extends('layouts.master')

@section('title','Hospitals')
@section('page-title', 'Malaysia Medical Facility')

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
    .total-hospital {
        background: white;
        padding: 8px 12px;
        border-radius: 8px;
        box-shadow: 0 0 6px rgba(0,0,0,0.2);
        font-weight: bold;
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

        /* Classification */
        .advanced{
            border-bottom: 3px solid #397fff;
        }

        .intermediete{
            border-bottom: 3px solid #48d12c;
        }

        .basic{
            border-bottom: 3px solid #b4a911ff;
        }

        /* Boder */
        .bl{
            border-left: 2px solid #DDDDDD;
        }

        .br{
            border-right: 2px solid #DDDDDD;
        }

        /* ===== Filter panel: district dropdown ===== */
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

    <div class="d-flex justify-content-end p-3" style="background-color: #dfeaf1;">

        <div class="d-flex gap-2 mt-2">

            <a href="{{ url('home') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill fs-3"></i>
                <small>Home</small>
            </a>

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

    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center gap-3 my-2">

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-link p-0 fw-bold text-decoration-underline text-dark" data-bs-toggle="modal" data-bs-target="#disclaimerModal">
                <i class="bi bi-info-circle text-primary fs-5"></i>
                Disclaimer
            </button>
        </div>

        <div class="d-flex align-items-end gap-3">
            <div style="margin-right:20px;">
                <span class="fw-bold pb-2 d-inline-block">Classification:</span>
            </div>
            <!-- Classification -->
            <div class="text-end" style="min-width: 700px;">
                <div class="row">
                    <div class="col-3 text-center fw-bold advanced br">Advanced</div>
                    <div class="col-4 text-center fw-bold intermediete br">Intermediate</div>
                    <div class="col-5 text-center fw-bold basic">Basic</div>
                </div>

                <div class="row text-center">
                <!-- Advanced -->
                    <div class="col-3 text-danger br">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level66Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:30px; height:30px;">
                            <small>Tertiary</small>
                        </button>
                    </div>

                    <!-- Intermediete -->
                     <div class="col-2 text-primary">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level55Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:30px; height:30px;">
                            <small>Secondary</small>
                        </button>
                    </div>
                    <div class="col-2 text-purple br">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level44Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:30px; height:30px;">
                            <small>Primary </small>
                        </button>
                    </div>

                    <!-- Basic -->
                    <div class="col-3 text-info">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level11Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:30px; height:30px;">
                            <small>Clinic / Health Center</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

</div>


<div class="modal fade" id="disclaimerModal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="disclaimerLabel">Disclaimer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Every attempt has been made to ensure the completeness and accuracy of the most updated information and data available. Clients are advised, however, that provided information, and data is subject to change.</p>
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

</div>


@endsection

@push('service')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry,drawing"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// === Inisialisasi Peta ===
const map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 3.772323891603972, lng: 101.48789037130577 },
    zoom: 6,
    mapTypeId: 'roadmap',
    mapTypeControl: true,
    fullscreenControl: true,
    streetViewControl: false
});

const infoWindow = new google.maps.InfoWindow();

// === Directions (in-map routing) ===
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
    closeRoutePanel();
});
map.controls[google.maps.ControlPosition.TOP_CENTER].push(clearRouteBtn);

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

            const leg = result.routes[0].legs[0];
            const panel = document.getElementById('routePanel');
            document.getElementById('routePanelTitle').textContent = destName || 'Destination';
            document.getElementById('routeDistance').textContent  = leg.distance.text;
            document.getElementById('routeDuration').textContent  = leg.duration.text;

            const stepsEl = document.getElementById('routeSteps');
            stepsEl.innerHTML = leg.steps.map((step, i) => {
                const raw = (step.html_instructions || step.instructions || '');
                const instruction = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                if (!instruction) return '';
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

// --- Nearby Category Bar (Google Maps style) — Hotels only ---
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

// === Variabel Global ===
let hospitalMarkers = [];
let radiusCircle = null;
let radiusPinMarker = null;
let lastClickedLocation = null;
let drawnPolygonGeoJSON = null;

// === Polygon Draw (Custom Point-by-Point) ===
let isDrawingPolygon = false;
let polygonLatLngs = [];
let activePolygon = null;
let activePolyline = null;
let cursorPolyline = null;
let startMarker = null;

const drawButton = document.createElement('div');
drawButton.innerHTML = '⬟';
Object.assign(drawButton.style, {
    backgroundColor: 'white', border: '2px solid rgba(0,0,0,0.2)', borderRadius: '4px',
    width: '34px', height: '34px', textAlign: 'center', lineHeight: '30px',
    fontSize: '18px', cursor: 'pointer', margin: '10px'
});
drawButton.title = 'Draw Polygon (Click point by point, click starting point to finish)';
map.controls[google.maps.ControlPosition.LEFT_TOP].push(drawButton);

const clearButton = document.createElement('div');
clearButton.innerHTML = '🗑️';
Object.assign(clearButton.style, {
    backgroundColor: 'white', border: '2px solid rgba(0,0,0,0.2)', borderRadius: '4px',
    width: '34px', height: '34px', textAlign: 'center', lineHeight: '30px',
    fontSize: '16px', cursor: 'pointer', margin: '10px 0'
});
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
            path: polygonLatLngs, strokeColor: '#ff6600', strokeOpacity: 0.8, strokeWeight: 3, clickable: false, map
        });
        cursorPolyline = new google.maps.Polyline({
            path: [], strokeColor: '#ff6600', strokeOpacity: 0.5, strokeWeight: 3, clickable: false, map
        });
        startMarker = null;
        drawnPolygonGeoJSON = null;
    } else {
        finishPolygon();
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
            paths: polygonLatLngs, strokeColor: '#ff6600', strokeOpacity: 0.8, strokeWeight: 3,
            fillColor: '#ff6600', fillOpacity: 0.2, editable: true, map
        });

        const coordinates = polygonLatLngs.map(p => [p.lng(), p.lat()]);
        coordinates.push([polygonLatLngs[0].lng(), polygonLatLngs[0].lat()]);

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
                await applyHospitalFilters();
            }
        };

        google.maps.event.addListener(activePolygon.getPath(), 'set_at', updatePolygonFilter);
        google.maps.event.addListener(activePolygon.getPath(), 'insert_at', updatePolygonFilter);
        google.maps.event.addListener(activePolygon.getPath(), 'remove_at', updatePolygonFilter);

        await applyHospitalFilters();
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
    await applyHospitalFilters();
});

// === Radius Circle & Location Pin ===
function updateRadiusCircleAndPin(radius = 0) {
    if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }

    if (radius > 0 && lastClickedLocation) {
        radiusCircle = new google.maps.Circle({
            strokeColor: '#FF0000', strokeOpacity: 0.8, strokeWeight: 2,
            fillColor: '#FF0000', fillOpacity: 0.2,
            map, center: lastClickedLocation, radius: radius * 1000
        });
    }
}

function placeLocationPin(location, label) {
    if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
    radiusPinMarker = new google.maps.Marker({
        position: location,
        map,
        title: label || 'Selected Location',
        icon: {
            url: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            scaledSize: new google.maps.Size(25, 41)
        },
        zIndex: 9999,
        animation: google.maps.Animation.DROP
    });
}

map.addListener('click', e => {
    if (isDrawingPolygon) {
        polygonLatLngs.push(e.latLng);
        activePolyline.setPath(polygonLatLngs);

        if (polygonLatLngs.length === 1) {
            startMarker = new google.maps.Marker({
                position: e.latLng,
                map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE, scale: 6,
                    fillColor: '#FFFFFF', fillOpacity: 1, strokeColor: '#ff6600', strokeWeight: 2
                },
                zIndex: 999
            });
            startMarker.addListener('click', () => {
                if (isDrawingPolygon) finishPolygon();
            });
        }
        return;
    }

    lastClickedLocation = { lat: e.latLng.lat(), lng: e.latLng.lng() };
    placeLocationPin(lastClickedLocation, 'Selected Location');
    const radius = parseInt(document.querySelector('#radiusRangeMap')?.value || 0);
    const radiusValEl = document.querySelector('#radiusValueMap');
    if (radiusValEl) radiusValEl.textContent = radius;
    updateRadiusCircleAndPin(radius);
    categoryBar.style.display = 'flex';
    applyHospitalFilters();
});

// === Fetch Data Hospital ===
async function fetchHospitalData(filters = {}) {
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([k, v]) => {
        if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
        else if (v !== '' && v != null) params.append(k, v);
    });
    if (drawnPolygonGeoJSON) params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));

    try {
        const res = await fetch(`/api/hospital?${params.toString()}`);
        return res.ok ? await res.json() : [];
    } catch (e) {
        console.error('Error fetching hospital data:', e);
        return [];
    }
}

// === Tambah Marker Hospital ===
function addHospitalMarkers(data) {
    hospitalMarkers.forEach(m => m.setMap(null));
    hospitalMarkers = [];

    const bounds = new google.maps.LatLngBounds();

    data.forEach(h => {
        if (!h.latitude || !h.longitude) return;

        const position = { lat: parseFloat(h.latitude), lng: parseFloat(h.longitude) };

        const marker = new google.maps.Marker({
            position,
            map,
            icon: {
                url: h.icon || 'https://unpkg.com/leaflet/dist/images/marker-icon.png',
                scaledSize: new google.maps.Size(24, 24)
            }
        });

        const itemName  = h.name || 'N/A';
        const detailUrl = h.id ? `/hospitals/${h.id}` : '';

        const popupContent = `
            <h5 style="border-bottom:1px solid #ccc;">${h.name || 'N/A'}</h5>
            <strong>Global Classification:</strong> ${h.facility_category || 'N/A'}<br>
            <strong>Country Classification:</strong> ${h.facility_level || 'N/A'}<br>
            <strong>Address:</strong>
                ${h.address || 'N/A'}
                ${h.city ? ', ' + h.city : ''}
                ${h.provinces_region ? ', ' + h.provinces_region : ''}, Malaysia <br>
        `;

        marker.addListener('click', () => {
            const destLat = parseFloat(h.latitude);
            const destLng = parseFloat(h.longitude);

            const readMoreBtn = detailUrl ? `
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
                </a>` : '';

            let actionButtons = '';
            if (lastClickedLocation && !isNaN(destLat) && !isNaN(destLng)) {
                const oLat = lastClickedLocation.lat;
                const oLng = lastClickedLocation.lng;
                actionButtons = `
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;display:flex;gap:6px;flex-wrap:wrap;">
                        <button onclick="showRouteOnMap(${oLat},${oLng},${destLat},${destLng},'${(itemName||'').replace(/'/g,"\\'")}')"
                           style="display:inline-flex;align-items:center;gap:5px;
                                  background:#1a73e8;color:#fff;border:none;
                                  padding:5px 12px;border-radius:6px;font-size:12px;
                                  font-weight:500;cursor:pointer;">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                            </svg>
                            Get Directions
                        </button>
                        ${readMoreBtn}
                    </div>`;
            } else if (readMoreBtn) {
                actionButtons = `
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;">
                        ${readMoreBtn}
                    </div>`;
            }

            infoWindow.setContent(`<div style="font-size:13px; min-width: 200px;">${popupContent}${actionButtons}</div>`);
            infoWindow.open(map, marker);
        });

        hospitalMarkers.push(marker);
        bounds.extend(position);
    });

    if (hospitalMarkers.length > 0)
        map.fitBounds(bounds, 50);
}

// === Apply Filter ===
async function applyHospitalFilters() {
    const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
    const levels = [...document.querySelectorAll('input[name="hospitalLevel"]:checked')].map(e => e.value);
    const hospitalSelect = $('#hospital_name_map').val() || '';
    const hospitalName = Array.isArray(hospitalSelect) ? hospitalSelect[0] : hospitalSelect;
    const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);

    let filters = {};
    if (hospitalName) filters.name = hospitalName;
    if (provs.length > 0) filters.provinces = provs;
    if (radius > 0 && lastClickedLocation) {
        filters.radius = radius;
        filters.center_lat = lastClickedLocation.lat;
        filters.center_lng = lastClickedLocation.lng;
    }

    const result = await fetchHospitalData(filters);

    // /api/hospital mengembalikan array polos.
    // Tetap toleran kalau suatu saat endpoint mengirim { hospitals, levelCounts }.
    const hospitals   = Array.isArray(result) ? result : (result.hospitals || []);
    const levelCounts = (!Array.isArray(result) && result.levelCounts) ? result.levelCounts : null;

    const filteredHospitals = hospitals.filter(h => {
        if (levels.length === 0) return true;
        if (!h.facility_level) return false;
        const dbLevels = h.facility_level.split(',').map(c => c.trim().toLowerCase());
        return levels.some(sel => dbLevels.includes(sel.toLowerCase()));
    });

    addHospitalMarkers(filteredHospitals);

    const totalEl = document.getElementById('totalCountDisplay');
    if (totalEl) {
        totalEl.innerHTML = `<strong>Hospitals:</strong> ${filteredHospitals.length}`;
    }

    // Pakai hitungan dari server kalau ada, kalau tidak hitung sendiri dari data.
    const counts = levelCounts || (() => {
        const local = {};
        hospitals.forEach(h => {
            if (!h.facility_level) return;
            h.facility_level.split(',').forEach(lvl => {
                lvl = lvl.trim();
                if (!lvl) return;
                local[lvl] = (local[lvl] || 0) + 1;
            });
        });
        return local;
    })();

    // Ambil level langsung dari checkbox yang ter-render, supaya daftarnya
    // tidak pernah beda dengan yang ada di panel filter.
    document.querySelectorAll('input[name="hospitalLevel"]').forEach(cb => {
        const badge = document.getElementById(`count-${cb.value.replace(/\s+/g, '-')}`);
        if (badge) badge.textContent = counts[cb.value] || 0;
    });
}

// === Filter Panel (Custom Google Maps Control) ===
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
        <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Search Location</strong>
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

    <!-- Radius -->
    <div id="radiusSection" style="padding:0 10px 0 10px;">
        <hr style="margin:8px 0;">
        <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Radius: <span id="radiusValueMap">0</span> km</strong>
        <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin:4px 0;">
        <div style="display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:5px;">
            <span>0</span><span>250 km</span><span>500 km</span>
        </div>
        <div style="display:flex;gap:5px;margin-bottom:6px;">
            <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
            <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
        </div>
    </div>

    <!-- Scrollable filters -->
    <div id="filterPanel" style="padding:0 10px 10px 10px;max-height:52vh;overflow-y:auto;border-top:1px solid #eee;">
        <div style="padding-top:8px;">
            <label>Hospital Name:</label>
            <select id="hospital_name_map" class="form-select form-select-sm mb-2 select-search-hospital">
                <option value="">Select Hospital</option>
                @foreach($hospitalNames as $n)
                    <option value="{{ $n }}">{{ $n }}</option>
                @endforeach
            </select>
            <label>Facility Level:</label>
            ${['Tertiary','Secondary','Primary','Clinic / Health Center'].map(c => `
            <label style="display:block;font-size:13px;margin-bottom:5px;">
                <input type="checkbox" name="hospitalLevel" value="${c}">
                ${c} (<span id="count-${c.replace(/\s+/g,'-')}">0</span>)
            </label>
            `).join('')}
            <hr>
            <div class="filter-box" id="provinceSelect">
                <label class="filter-label">State</label>

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
                        @foreach ($provinces as $p)
                        <li>
                            <label>
                                <input
                                    type="checkbox"
                                    class="province-checkbox"
                                    value="{{ $p->id }}"
                                >
                                {{ $p->provinces_region }}
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <hr>
            <button id="resetMapFilter" class="btn btn-sm btn-secondary w-100">Reset All</button>
            <div id="totalCountDisplay" style="margin-top:8px;text-align:center;font-size:13px;"></div>
        </div>
    </div>`;

google.maps.event.addDomListener(combinedPanelDiv, 'click', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'dblclick', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'mousedown', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'touchstart', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'wheel', e => e.stopPropagation());
map.controls[google.maps.ControlPosition.RIGHT_TOP].push(combinedPanelDiv);

// === Init Select2 (retry sampai panel benar-benar ada di DOM) ===
function initHospitalSelect2() {
    const el = document.getElementById('hospital_name_map');
    if (typeof $ === 'undefined' || !$.fn || !$.fn.select2 || !el) {
        setTimeout(initHospitalSelect2, 200);
        return;
    }
    if ($(el).hasClass('select2-hidden-accessible')) return;
    $(el).select2({
        width: '100%',
        placeholder: 'Search Hospital',
        allowClear: true
    });
}
initHospitalSelect2();

// Event select2 (delegated, jadi tidak tergantung timing DOM)
$(document).on('change', '#hospital_name_map', function() {
    applyHospitalFilters();
});

// === Init Location Search — Google Places Autocomplete ===
// .pac-container is repositioned to position:fixed via MutationObserver
// to bypass Google Maps container overflow:hidden clipping.
function initLocationSearch() {
    const input = document.getElementById('locationSearchMap');
    if (!input) {
        setTimeout(initLocationSearch, 300);
        return;
    }

    const clearBtn = document.getElementById('locationSearchClear');

    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['geocode', 'establishment'],
        fields: ['geometry', 'name', 'formatted_address']
    });

    let pacContainer = null;

    function fixPacPosition() {
        if (!pacContainer) return;
        const rect = input.getBoundingClientRect();
        pacContainer.style.position   = 'fixed';
        pacContainer.style.zIndex     = '2147483647';
        pacContainer.style.top        = (rect.bottom + 2) + 'px';
        pacContainer.style.left       = rect.left + 'px';
        pacContainer.style.width      = rect.width + 'px';
        pacContainer.style.borderRadius = '0 0 8px 8px';
        pacContainer.style.boxShadow  = '0 8px 24px rgba(0,0,0,0.2)';
        pacContainer.style.fontFamily = 'inherit';
    }

    const observer = new MutationObserver(() => {
        if (!pacContainer) {
            pacContainer = document.querySelector('.pac-container');
            if (pacContainer) {
                fixPacPosition();
                new MutationObserver(fixPacPosition).observe(
                    pacContainer, { attributes: true, attributeFilter: ['style'] }
                );
            }
        }
    });
    observer.observe(document.body, { childList: true, subtree: false });

    window.addEventListener('scroll', fixPacPosition, true);
    window.addEventListener('resize', fixPacPosition);
    input.addEventListener('focus',  fixPacPosition);
    input.addEventListener('input',  fixPacPosition);

    google.maps.event.addDomListener(input, 'keydown',   e => e.stopPropagation());
    google.maps.event.addDomListener(input, 'mousedown', e => e.stopPropagation());

    input.addEventListener('focus', () => {
        input.style.borderColor = '#1a73e8';
        input.style.boxShadow   = '0 0 0 3px rgba(26,115,232,0.15)';
    });
    input.addEventListener('blur', () => {
        input.style.borderColor = '#ddd';
        input.style.boxShadow   = 'none';
    });

    input.addEventListener('input', () => {
        if (clearBtn) clearBtn.style.display = input.value.length ? 'inline' : 'none';
    });

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

        const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
        updateRadiusCircleAndPin(radius);
        categoryBar.style.display = 'flex';
        applyHospitalFilters();
    });

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

            categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            const rEl    = document.getElementById('radiusRangeMap');
            const rValEl = document.getElementById('radiusValueMap');
            if (rEl)    rEl.value          = 0;
            if (rValEl) rValEl.textContent = '0';

            applyHospitalFilters();
            input.focus();
        });
    }
}

// === Events ===
document.addEventListener('input', e => {
    if (e.target.id === 'radiusRangeMap') {
        const r = parseInt(e.target.value || 0);
        document.getElementById('radiusValueMap').textContent = r;
        updateRadiusCircleAndPin(r);
    }
});

document.addEventListener('click', async e => {
    if (e.target.id === 'applyRadiusMap') {
        const radius = parseInt(document.getElementById('radiusRangeMap').value || 0);
        if (radius > 0 && !lastClickedLocation) {
            alert('Cari lokasi terlebih dahulu menggunakan kolom "Search Location", atau klik langsung pada peta untuk menentukan titik radius.');
            return;
        }
        await applyHospitalFilters();
    }

    if (e.target.id === 'resetRadiusMap') {
        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

        await applyHospitalFilters();
    }

    if (e.target.id === 'resetMapFilter') {
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => cb.checked = false);
        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            $('.select-search-hospital').val(null).trigger('change');
        } else {
            document.getElementById('hospital_name_map').value = '';
        }

        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            provinceSearch.value = '';
            provinceSearch.placeholder = 'Select State';
        }
        const provinceSearchInput = document.getElementById('provinceSearchInput');
        if (provinceSearchInput) provinceSearchInput.value = '';
        document.querySelectorAll('#provinceList li').forEach(li => { li.style.display = ''; });
        const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');
        if (provinceDropdown) provinceDropdown.classList.remove('show');

        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

        closeRoutePanel();

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

        await applyHospitalFilters();
    }
}, true);

// === Checkbox & select change auto apply ===
document.addEventListener('change', e => {
    if (e.target.classList.contains('province-checkbox') || e.target.name === 'hospitalLevel') {
        applyHospitalFilters();
    }
});

// === District: Select - Search Checkbox ===
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

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('province-checkbox')) {
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

// === Inisialisasi Awal ===
setTimeout(() => {
    initLocationSearch();
}, 350);

// Retry sampai panel filter benar-benar ter-attach ke DOM oleh Google Maps.
function initialApplyFilters() {
    if (!document.getElementById('totalCountDisplay')) {
        setTimeout(initialApplyFilters, 200);
        return;
    }
    applyHospitalFilters();
}
initialApplyFilters();
</script>

@endpush
