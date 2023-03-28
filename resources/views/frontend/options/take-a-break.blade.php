<style>
    .popup-content-take-a-break {
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
    .popup-overlay-take-a-break{
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
<div class="popup-overlay-take-a-break">
    <div class="popup-content-take-a-break" >
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">{{__('sunshine.Take A Break')}}</h4>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button"  class="close-popup-take-a-break close-tab" >&times;</button>
                        </div>
                    </div>
                    <p class="card-description">
                        {{__('sunshine.You want to take a break')}}!
                    </p>
                    <form class="forms-sample" action="{{route('option-post-take-a-break')}}" id="edit-form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="from" class="col-sm-3 col-form-label">{{__('sunshine.Time take a break')}}:</label>
                        <div class="col-sm-9">
                        <input type="date" class="form-control" id="fromTakeABreak" name="from">
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="reason" class="col-sm-3 col-form-label">{{__('sunshine.Reason')}}:</label>
                        <div class="col-sm-9">
                            <textarea name="reason" class="form-control" id="reason" rows="4"></textarea>  
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" >{{__('sunshine.Submit')}}</button>
                    <a class="btn btn-light close-popup-take-a-break">{{__('sunshine.Cancel')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //pop up xin nghỉ phép
        $(".show-popup-take-a-break").click(function() {
            $(".popup-content-take-a-break").show(200);
            $(".popup-overlay-take-a-break").show();
        });
        
        $(".close-popup-take-a-break").click(function() {
            $(".popup-content-take-a-break").hide(200);
            $(".popup-overlay-take-a-break").hide();
        });

        $(".take-a-break-button").click(function() {
        let date = $(this).data("date");
        $.ajax({
            url: "/client/take-a-break/" + date,
            method: "GET",
            success: function(response) {
                // Hiển thị dữ liệu trên popup
                $("#fromTakeABreak").val(response);
            }
        });
        });
    });
</script>