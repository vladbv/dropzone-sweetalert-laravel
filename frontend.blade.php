@extends('admin.layouts.app')


@section('content'


<div id="cst" class="xst">
<div class="modal-style modal-patients" id="add-patient-modal">
<div class="card p-3">
    <div class="card-header">
        <h5>ADD USER <span class="float-right close-modal" id="cls-modal" onclick="clsM()">X</span></h5> </div>
        <div class="card-body">
        <form class="storeuser dropzone" id="storeuser" data-flag="0" action="{{route('admin.patients.uploadphoto')}}" method="POST" enctype="multipart/form-data">
        @csrf
<div class="row">
<div class="col-md-6">

    <div class="form-group">
        <input type="text" value="{{$patient->id}}" name="id" hidden />


        <label for="name">Name:</label>
        <input type="text" class="form-control {{$errors ->has('name') ? 'is-invalid' : ''}}" name="name" value="{{old('name')}}" />
@if ($errors-> has('name'))
<div class="invalid-feedback">
{{$errors->first('name')}}
</div>
@endif
        </div>

         <div class="form-group">

        <label for="email">Email (of choice)</label>
        <input type="email" class="form-control" name="email" />


        </div>
        <div class="form-group">

        <label for="phone">Telephone (of choice)</label>
        <input type="text" class="form-control" name="phone" />


        </div>

          <div class="form-group">

        <label for="address">Адрес (по избор)</label>
        <input type="text" class="form-control" name="address" />

        </div>








</div>

{{-- /.col-md-6 first column--}}

<div class="col-md-6">

@if(Auth::user() -> role == 'photostudio' || Auth::user() -> role == 'admin')
{{-- USER SEARCH --}}
<br>
<label for="search_upatientcity">City</label>
          {{--  <input type="text" name="search_upatientcity" id="search_upatientcity" class="form-control form-control-lg" placeholder="Enter the city of the user" /> --}}
            {{ Form::text('search_upatientcity',null,array('class'=>'form-control form-control-lg','id'=>'search_upatientcity','name'=>'search_upatientcity')) }}
</div>



        <div class="form-group">
Attach to another type of user	    
{{--<input type="text" name="search_upatient" id="search_upatient" class="form-control form-control-lg" /> --}}
         <label for="search_upatient">Name / ID / Telephone / Address</label>

            {{ Form::text('search_upatient',null,array('class'=>'form-control form-control-lg','id'=>'search_upatient','name'=>'search_upatient', 'placeholder' =>"Въведете Име / ID / Телефон на зъболекар / Адрес")) }}

            Users with your criteria <span id="total_records2"></span><br>
            <label for="assigned">User:</label>

                <select name="assigned" class="form-control browser-default custom-select custom-select-lg mb-3" id="optpatient">
                </select>

            </div>

            {{-- END OF USER SEARCH --}}
            @endif
            @if(Auth::user() -> role == 'user')
            <div class="form-group">

             Attach to a user account <span id="total_records2"></span>
                <label for="assigned">Your user account</label>

                    <select name="assigned" class="form-control browser-default custom-select custom-select-lg mb-3" id="optpatient">
                    <option value="{{Auth::user()-> id}}">{{Auth::user()->name}}</option>
                    </select>

                </div>
            @endif

            <div class="form-group">

                <div class="dz-message">
Click the button to upload files
                   </div>


               </div>



</div>

            <button class="btn btn-primary btn-block adduser" id="addpatient">ADD A USER</button>
            </form>

	    @endsection

	    @push ('admin.layouts.scripts.scripts')

{{-- CONFIRM FORM SUBMISSION --}}
<script>
$(document).ready(function(){
    $("#storepatient").submit(function (e) {

           //additional input validations can be done hear
           e.preventDefault();

var data = $(this).serialize();
    swal({
                title: "Please confirm the sending",
                text: "Do you want to send " + $('input[name="name"]').val() + " to another type of user " + $('.optionuserp:selected').attr('data-name') + ' who is located at: ' + $('.optionuserp:selected').attr('data-city') + ' to address ' + $('.optionuserp:selected').attr('data-address'),
                icon: "warning",
buttons: {
    cancel: {
    text: "Cancel",
    value: null,
    visible: true,
    className: "",
    closeModal: true,
  },
  confirm: {
    text: "Accept",
    value: true,
    visible: true,
    className: "",
    closeModal: true
  }
}
            })
            .then((isConfirm) => {
                if (isConfirm) {
                    $.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
});
                    $.ajax({
        type: 'POST',
          url: '/admin/patients/store',
          data: data,
          success: function (data) {
            swal("Successfully added data", "Success.", "success");
$('#success-msg').show();
          },
          error: function (data) {
            swal("The user is not send", "You have rejected the sending", "error");
          }
        });
                        } else {

              //additional run on cancel  functions can be done hear

            }
        });
});

});


</script>

{{-- END OF CONFIRM --}}

{{-- DROPZONE --}}
<script>
var segments = location.href.split('/');
var action = segments[4];
alert(action);
console.log(action);
if(action == 'patients'){
var acceptedFileTypes = "image/*, .psd";
var fileList = new Array;
var i = 0;
var callForDzReset = false;
$("#storepatient").dropzone({
url: '/admin/patients/uploadphoto',
addRemoveLinks: true,
maxFiles: 2000,
parallelUploads: 10000,
//paramName: "image[]",
uploadMultiple: true,
acceptedFiles: 'image/*',
maxFilesize:30,
init: function(){
this.on("success", function( file, serverFileName){
file.serverFn = serverFileName;
fileList[i] = {
"serverFileName": serverFileName,
"fileName": file.name,
"fileId": i
}
i++;

})
}

})

}

</script>

{{--<script>
Dropzone.options.storepatient = {
    autoProcessQueue: false,

    init: function (e) {

        var myDropzone = this;

        $('#addpatient').on("click", function() {
            myDropzone.processQueue(); // Tell Dropzone to process all queued files.
        });

        // Event to send your custom data to your server
        myDropzone.on("sending", function(file, xhr, data) {

            // First param is the variable name used server side
            // Second param is the value, you can add what you what
            // Here I added an input value
            data.append(serverFileName, $('#' + i).val());
        });

    }
};
</script> --}}
{{-- END OF DROPZONE  --}}

