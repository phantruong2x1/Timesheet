<style>
    .popup-content-come-back-soon {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        width: 700px;
        height: 400px;
        z-index: 10000;
        border-radius: 20px;
    
    }
    
    /* Hiển thị popup khi được kích hoạt */
    .popup-overlay-come-back-soon{
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 9999;
      /* transition: opacity 0.9s ease; */
    }
    
</style>
<div class="popup-overlay-come-back-soon">
    <div class="popup-content-come-back-soon" >
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">{{__('sunshine.Come Back Soon')}}</h4>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button"  class="close-popup-come-back-soon close-tab" >&times;</button>
                        </div>
                    </div>
                    <p class="card-description">
                        {{__('sunshine.You want to come back soon')}}!
                    </p>
                    <form class="forms-sample" action="{{route('option-post-please-come-back-soon')}}" id="edit-form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="to" class="col-sm-3 col-form-label">{{__('sunshine.Time you want to come back')}}:</label>
                        <div class="col-sm-9">
                        <input type="datetime-local" class="form-control" id="to" name="to">
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="reason" class="col-sm-3 col-form-label">{{__('sunshine.Reason')}}:</label>
                        <div class="col-sm-9">
                            <textarea name="reason" class="form-control" rows="4"></textarea>  
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" >{{__('sunshine.Submit')}}</button>
                    <a class="btn btn-light close-popup-come-back-soon">{{__('sunshine.Cancel')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //pop up xin về sớm
        $(".show-popup-come-back-soon").click(function() {
            $(".popup-content-come-back-soon").show(200);
            $(".popup-overlay-come-back-soon").show();
        });
        
        $(".close-popup-come-back-soon").click(function() {
            $(".popup-content-come-back-soon").hide(200);
            $(".popup-overlay-come-back-soon").hide();
        });

        $(".come-back-soon-button").click(function() {
        let date = $(this).data("date");
        $.ajax({
            url: "/client/please-come-back-soon/" + date,
            method: "GET",
            success: function(response) {
                // Hiển thị dữ liệu trên popup
                $("#to").val(response);
            }
        });
        });
    });
</script>