@extends('template')

@section('caption', 'Text Analyzer')

@section('content')
   <p>Simple text analyzer including character info, character count, and siblings each character.</p>

   <div class="row">
      <div class="col-lg-6">
         <form id="form--text-analyzer" method="post" action="{{ url('/api/generate-text-info') }}">
            <div class="form-group">
               <label>Input text:</label>
               <textarea class="form-control" rows="5" minlength="2" maxlength="255" name="text" required></textarea>
               <small class="form-text text-muted">min 2 characters, max 255 characters.</small>
            </div>
            <div class="form-group form-check">
               <input type="checkbox" class="form-check-input" id="special_chars" name="special_chars">
               <label for="special_chars" class="form-check-label">Include special characters ?</label>
               <small class="form-text text-muted">default: no, all special characters (including &lt;space&gt;) except underscore "_" will remove.</small>
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
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th scope="col" rowspan="2" class="align-middle">Character Symbol</th>
                     <th scope="col" rowspan="2" class="align-middle">Count</th>
                     <th scope="col" colspan="3" class="text-center">Info</th>
                  </tr>
                  <tr>
                     <th>siblings before</th>
                     <th>siblings after</th>
                     <th>max distance</th>
                  </tr>
               </thead>
               <tbody>
                  
               </tbody>
            </table>
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

               $table = $result.find('table:first'); // table
               $tbody = $table.find('tbody'); // tbody
               $tr = ''; // new row

               res.data.forEach(item => {
                  $tr += `
                     <tr>
                        <td>${item.character}</td>
                        <td>${item.count}</td>
                        <td>${item.siblings.before ? item.siblings.before.join(', ') : '<i>None</i>'}</td>
                        <td>${item.siblings.after ? item.siblings.after.join(', ') : '<i>None</i>'}</td>
                        <td>${item.max_distance}</td>
                     </tr>
                  `;
               });

               $tbody.empty() // clear tbody
                  .append($tr); // append row

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