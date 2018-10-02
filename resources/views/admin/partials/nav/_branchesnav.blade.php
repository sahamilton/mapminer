<li>
    <a class="dropdown-item dropdown-toggle" href="#">
        <i class="fab fa-pagelines"></i> Branches</a>
    <ul class="dropdown-menu">
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