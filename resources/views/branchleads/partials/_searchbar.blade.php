
    <div class="col-sm-6 col-sm-offset-3">
        <div id="imaginary_container"> 
            <div class="input-group stylish-input-group">
                <form method="post"
                action="{{route('searchleads')}}"
                >
                @csrf
                <input type="text" 
                class="form-control" 
                    id="leadsearch" 
                    name="companyname" 
                    placeholder="Search My Leads"
                    required 
                    autocomplete="off" >
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>



