<style>
    .popup-content-forget {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        width: 700px;
        height: 600px;
        z-index: 10000;
        border-radius: 20px;
    
    }
    
    /* Hiển thị popup khi được kích hoạt */
    .popup-overlay-forget{
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
<div class="popup-overlay-forget">
    <div class="popup-content-forget" >
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">{{__('sunshine.Update Checkout')}}</h4>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button"  class="close-popup-forget close-tab" >&times;</button>
                        </div>
                    </div>
                    <p class="card-description">
                        {{__('sunshine.Enter your data!')}}
                    </p>
                    <form class="forms-sample" action="{{route('option-post-forget')}}" id="forget-form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label">{{__('sunshine.Date')}}:</label>
                        <div class="col-sm-9">
                        <input type="date" readonly class="form-control" id="dateForget" name="date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="first_checkin" class="col-sm-3 col-form-label">{{__('sunshine.First Checkin')}}:</label>
                        <div class="col-sm-9">
                        <input type="time" class="form-control" readonly id="first_checkin" name="first_checkin">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="last_checkout" class="col-sm-3 col-form-label">{{__('sunshine.Last Checkout')}}:</label>
                        <div class="col-sm-9">
                        <input type="time" class="form-control" id="last_checkout" name="last_checkout">
                        <div id="validationMessage" style="color: red"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reason" class="col-sm-3 col-form-label">{{__('sunshine.Reason')}}:</label>
                        <div class="col-sm-9">
                            <textarea name="reason" class="form-control" id="reason" rows="4"></textarea>  
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" >{{__('sunshine.Submit')}}</button>
                    <a class="btn btn-light close-popup-forget">{{__('sunshine.Cancel')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //pop up update checkout
        $(".show-popup-forget").click(function() {
            $(".popup-content-forget").show(200);
            $(".popup-overlay-forget").show();
        });

        $(".close-popup-forget").click(function() {
            $(".popup-content-forget").hide(200);
            $(".popup-overlay-forget").hide();
        });

        $(".forget-button").click(function() {
        let id = $(this).data("id");
        $.ajax({
            url: "/client/forget/" + id,
            method: "GET",
            success: function(response) {
                // Hiển thị dữ liệu trên popup
                $('#dateForget').val(response.date)
                $('#first_checkin').val(response.first_checkin)
                console.log(response);
            }
        });
        });
    
    $("#forget-form").on("submit", function (e) {
        e.preventDefault();

        // Xác thực dữ liệu
        let inputData = $("#last_checkout").val();
        if (inputData.trim() === "") {
            $("#validationMessage").text("Dữ liệu không được bỏ trống.");
            return;
        } else {
            $("#validationMessage").text("");
        }
    });
    });
</script>