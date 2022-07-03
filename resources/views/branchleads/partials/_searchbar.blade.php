<div class="col-sm-6">
    
            <form class="form-inline" method="post"
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
                <input type="hidden" name="branch_id" value = "{{session('branch')}}" />
            </form>
        
</div>



