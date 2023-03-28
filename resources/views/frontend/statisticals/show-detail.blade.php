<style>
    /* pop up */
    .popup-content {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    width: 1000px;
    height: 600px;
    z-index: 10000;
    border-radius: 20px;

    }

    /* Hiển thị popup khi được kích hoạt */
    .popup-overlay{
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
    .close-tab {
    position: absolute;
  
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
    }

    .close-tab:hover {
    color: red;
    }
</style>
{{-- Pop up xem chi tiết  --}}
<div class="popup-overlay">
    <div class="popup-content" >
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">{{__('sunshine.Detailed Table')}}</h4>
                            
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button"  class="close-popup close-tab" >&times;</button>
                        </div>
                    </div>
                    <p class="card-description">
                        {{__('sunshine.Detailed statistics of')}} <span class="text-primary">{{$monthFilter}}</span>
                    </p>
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total working day')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="working_date" class="form-control" name="working_date" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->working_date: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">{{__('sunshine.day')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>{{__('sunshine.Total working hours')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="working_hour" class="form-control" name="working_hour" readonly
                                        value="{{($statisticalDeatil)?number_format($statisticalDeatil->working_hour/3600000,1):0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">{{__('sunshine.hour')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total overtime hours')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="overtime_hour" class="form-control" name="overtime_hour" readonly
                                        value="{{($statisticalDeatil)?number_format($statisticalDeatil->overtime_hour/3600000,1):0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">{{__('sunshine.hour')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>{{__('sunshine.Total last checkin')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="last_checkin" class="form-control" name="last_checkin" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->last_checkin: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total early checkout')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="early_checkout" class="form-control" name="early_checkout" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->early_checkout: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>{{__('sunshine.Total day off')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" id="day_off" class="form-control" name="day_off" readonly
                                        value="{{($statisticalDeatil)?$statisticalDeatil->day_off: 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label>{{__('sunshine.Total salary')}}:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">$</span>
                                        </div>
                                        <input type="text" id="salary" class="form-control" name="salary" readonly
                                        value="{{($statisticalDeatil)?number_format($statisticalDeatil->salary,0,',',' '): 0}}" aria-label="Amount (to the nearest dollar)"/>
                                        <div class="input-group-append">
                                        <span class="input-group-text">vnđ</span>
                                        </div>
                                    </div>
                                </div>
                              
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="col">
                                <h4 class="card-title">{{__('sunshine.Payroll Cost Table')}}</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless table-hover">
                                        <thead>
                                            <tr>
                                              <th>{{__('sunshine.Type Cost')}}</th>
                                              <th>{{__('sunshine.Value')}}</th>
                                              <th>{{__('sunshine.Cost')}} (vnđ)</th>
                                              <th>{{__('sunshine.Salary Type')}}</th>
                                              <th>{{__('sunshine.Total')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($payroll)
                                            @foreach($payroll as $item)
                                            <tr>
                                                <td>{{$item['name']}}</td>
                                                <td>{{$item['value']}}</td>
                                                <td>{{$item['cost']}}</td>
                                                <td>{!!$item['salary_type'] =='Penalty' ?  
                                                    '<label class="badge badge-success">Penalty</label>':
                                                    '<label class="badge badge-danger">Reward</label>'!!}
                                                </td>
                                                <td>{{$item['total']}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4"><b>{{__('sunshine.Total salary')}}</b></td>
                                                <td>{{($statisticalDeatil)?number_format($statisticalDeatil->salary,0,',',' '): 0}}</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td colspan="5">{{__('sunshine.There is no data')}}!</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-end d-flex">
                        <a class="btn btn-light close-popup ">{{__('sunshine.Cancel')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.date_filter').change(function() {
            $('#form_filter').submit();
        })
    })

    $(document).ready(function() {
        const time = document.querySelector(".date_filter").value
        $(".show-popup").click(function() {
            $(".popup-content").show(200);
            $(".popup-overlay").show();
        });

        $(".close-popup").click(function() {
            $(".popup-content").hide(200);
            $(".popup-overlay").hide();
        });

    });
</script>