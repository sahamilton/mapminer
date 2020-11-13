<div class="row">
        <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;">
            <h4>Active Opportunities</h4>
            <table class="table">
                <thead>
                    <th>Company</th>
                    <th># Opportunities</th>
                    <th>$ Value</th>
                </thead>
                <tbody>

                    @foreach ($companies as $company)
                    
                    @if($company->active_opportunities > 0)
                    <tr>
                        <td>{{$company->companyname}}</td>
                        <td align="center">{{$company->active_opportunities}}</td>
                        <td align="right">${{number_format($company->active_value)}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                
            </table>
        </div>
        <div style="width: 40%;float:right;border:solid 1px #aaaaaa;margin:5px;margin-left:5px">
            <h4>Opportunities Won</h4>
            <table class="table">
                <thead>
                    <th>Company</th>
                    <th># Won</th>
                    <th>$ Won</th>
                </thead>
                <tbody>
                    
                    @foreach ($companies as $company)
                   
                    @if($company->won_opportunities > 0)
                    <tr>
                        <td>{{$company->companyname}}</td>
                        <td align="center">{{$company->won_opportunities}}</td>
                        <td align="right">${{number_format($company->won_value,0)}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <td></td>
                    <td align="center"><strong>{{$companies->sum('won_opportunities')}}</strong></td>
                    <td align="right"><strong>${{number_format($companies->sum('won_value'))}}</strong></td>
                </tfoot>
            </table>
            
        </div>
    </div>
