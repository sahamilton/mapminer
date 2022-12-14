<li>
    <a class="dropdown-item dropdown-toggle" href="#">
        <i class="fab fa-pagelines"></i> Branches</a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" 
            href="{{ route('branches.index') }}">
                <i class="fab fa-pagelines" aria-hidden="true"></i> All Branches
            </a>
        </li>
        <li>
            <a class="dropdown-item" 
            href="{{ route('branches.create') }}">
                <i class="fas fa-plus-circle"></i> Add Branch
            </a>
        </li>
        <li>
            <a class="dropdown-item" 
            href="{{ route('branch.management') }}">
                <i class="fas fa-wrench"></i> Manage Branches
            </a>
        </li>
        

        
        <li>
            <a class="dropdown-item" 
            href="{{ route('branch.check') }}">
            <i class="fas fa-stethoscope"></i> Check Assignments
        </a>
        </li>

        <li>
            <a class="dropdown-item" href="{{route('branchassignment.check')}}">
                <i class="far fa-envelope"></i> Confirm Assignments
            </a>
        </li>
    </ul>
</li>