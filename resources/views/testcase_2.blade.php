@extends('template')

@section('caption', 'N-th Pos of Digit')

@section('content')
   <p>Find a character of n-th in sequence digit.</p>

   <div class="row">
      <div class="col-lg-6">
         <form id="form--text-analyzer" method="post" action="{{ url('/api/find-nth-digit') }}">
            <div class="form-group">
               <label>Input number:</label>
               <input type="number" class="form-control" placeholder="Enter a positive number" min="0" name="number" required>
               <small class="form-text text-muted">it will reproduce a sequnce of decimal digit.</small>
               <small class="form-text text-muted">ex: <i>n</i>=15, result: 123456789101112131415</small>
               <small class="form-text text-muted">the chararacter of n-th pos digit is "2"</small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
         </form>
      </div>
   </div>

   <br>
   <div class="row">
      <div class="col-lg-6">
         <i id="time"></i>

         <div id="result" style="display: none">
            <p class="display-4">N-th pos digit is: <span id="nth"></span></p>
         </div>
      </div>
   </div>
@endsection

@section('script')
@parent
   <script>
      // event submit form
      $(document).on('submit', '#form--text-analyzer', function (e) {
         e.preventDefault();

         let $this = $(this); // this form
         let $submit = $this.find('[type="submit"]:first'); // submit button
         let $result = $('#result'); // result container
         let $time_elapsed = $('#time'); // execution time

         let form_data = $this.serialize(); // form data
         let ajax_config = {
            url: $this.attr('action'),
            type: 'post',
            dataType: 'json',
            data: form_data,
            beforeSend: function (params) {
               $submit.prop('disabled', true)
                  .html(`<div class="spinner-border spinner-border-sm text-info text-center"></div>&nbsp;Loading...`);
            },
            success: function (res) {
               // check error
               if ('error' in res) {
                  alert(res.error);
                  return;
               }

               $nth = $result.find('#nth'); // table

               $nth.text(res.data); // assign result
               $time_elapsed.html(`execution time: ${res.execution_time} sec`); // execution time
               $result.show(); // result show
            },
            error: function (xhr) {
               alert(xhr.responseText);
            },
            complete: function () {
               $submit.prop('disabled', false).html('Submit');
            }
         };

         $.ajax(ajax_config); // do ajax
      });
   </script>
@endsection