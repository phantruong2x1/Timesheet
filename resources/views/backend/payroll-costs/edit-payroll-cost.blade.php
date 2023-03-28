<style>
    .popup-content {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        width: 700px;
        height: 450px;
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
    
    </style>
<div class="popup-overlay">
    <div class="popup-content" >
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Payroll Cost</h4>
                    <p class="card-description">
                        You can only edit the amount.
                    </p>
                    <form class="forms-sample" action="{{route('payroll-costs.update')}}" id="edit-form" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="type_cost" class="col-sm-3 col-form-label">Type Cost</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="type_cost" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cost" class="col-sm-3 col-form-label">Cost</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">$</span>
                                </div>
                                <input type="text" id="cost" class="form-control" name="cost" value="{{old('cost')}}" aria-label="Amount (to the nearest dollar)"/>
                                <div class="input-group-append">
                                <span class="input-group-text">vnđ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="salary_type" class="col-sm-3 col-form-label">Salary Type</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="salary_type" readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" >Submit</button>
                    <a class="btn btn-light close-popup">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
  